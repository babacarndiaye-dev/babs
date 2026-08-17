<?php

namespace App\Livewire\Admin\Finance\Invoices;

use App\Models\Invoice;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $status = '';

    #[Url]
    public string $search = '';

    public array $statuses = [
        'en_attente' => 'En attente',
        'partiel' => 'Partiel',
        'paye' => 'Payé',
        'en_retard' => 'En retard',
        'annule' => 'Annulé',
    ];

    public function render()
    {
        $invoices = Invoice::with('student')
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->search, fn ($q) => $q->where('invoice_number', 'like', "%{$this->search}%")
                ->orWhereHas('student', fn ($s) => $s->where('first_name', 'like', "%{$this->search}%")
                    ->orWhere('last_name', 'like', "%{$this->search}%")))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.finance.invoices.index', [
            'invoices' => $invoices,
            'kpis' => [
                'billed' => Invoice::sum('total_amount'),
                'outstanding' => Invoice::whereIn('status', ['en_attente', 'partiel', 'en_retard'])->count(),
            ],
        ]);
    }
}
