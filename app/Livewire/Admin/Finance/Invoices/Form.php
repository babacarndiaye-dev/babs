<?php

namespace App\Livewire\Admin\Finance\Invoices;

use App\Models\AcademicYear;
use App\Models\FeeType;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class Form extends Component
{
    public ?int $student_id = null;

    public ?string $due_date = null;

    /** @var array<int, array{fee_type_id: ?int, label: string, amount: ?float}> */
    public array $lines = [
        ['fee_type_id' => null, 'label' => '', 'amount' => null],
    ];

    public function mount(): void
    {
        $this->due_date = now()->addDays(30)->format('Y-m-d');
    }

    public function addLine(): void
    {
        $this->lines[] = ['fee_type_id' => null, 'label' => '', 'amount' => null];
    }

    public function removeLine(int $index): void
    {
        unset($this->lines[$index]);
        $this->lines = array_values($this->lines);
    }

    public function total(): float
    {
        return collect($this->lines)->sum(fn ($line) => (float) ($line['amount'] ?? 0));
    }

    protected function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'due_date' => ['nullable', 'date'],
            'lines' => ['required', 'array', 'min:1'],
            'lines.*.label' => ['required', 'string', 'max:150'],
            'lines.*.amount' => ['required', 'numeric', 'min:0'],
            'lines.*.fee_type_id' => ['nullable', 'exists:fee_types,id'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $year = AcademicYear::current() ?? AcademicYear::first();
        $number = 'FAC-'.now()->format('Y').'-'.str_pad((string) (Invoice::whereYear('created_at', now()->year)->count() + 1), 4, '0', STR_PAD_LEFT);

        $invoice = Invoice::create([
            'student_id' => $this->student_id,
            'academic_year_id' => $year->id,
            'invoice_number' => $number,
            'total_amount' => $this->total(),
            'status' => 'en_attente',
            'due_date' => $this->due_date,
        ]);

        foreach ($this->lines as $line) {
            InvoiceLine::create([
                'invoice_id' => $invoice->id,
                'fee_type_id' => $line['fee_type_id'] ?: null,
                'label' => $line['label'],
                'amount' => $line['amount'],
                'due_date' => $this->due_date,
            ]);
        }

        session()->flash('status', 'Facture créée.');

        $this->redirectRoute('admin.finance.invoices.show', $invoice, navigate: false);
    }

    public function render()
    {
        return view('livewire.admin.finance.invoices.form', [
            'students' => Student::orderBy('first_name')->get(),
            'feeTypes' => FeeType::orderBy('name')->get(),
        ]);
    }
}
