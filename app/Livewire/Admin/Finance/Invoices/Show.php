<?php

namespace App\Livewire\Admin\Finance\Invoices;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Receipt;
use App\Notifications\PaymentRecorded;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class Show extends Component
{
    public Invoice $invoice;

    public ?float $amount = null;

    public ?int $payment_method_id = null;

    public string $reference = '';

    public function mount(Invoice $invoice): void
    {
        $this->invoice = $invoice->load(['student', 'lines.feeType', 'payments.paymentMethod', 'payments.receipt']);
        $this->amount = $this->invoice->balance() > 0 ? $this->invoice->balance() : null;
    }

    protected function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'reference' => ['nullable', 'string', 'max:150'],
        ];
    }

    public function recordPayment(): void
    {
        $this->validate();

        $payment = Payment::create([
            'invoice_id' => $this->invoice->id,
            'payment_method_id' => $this->payment_method_id,
            'amount' => $this->amount,
            'reference' => $this->reference ?: null,
            'status' => 'valide',
            'paid_at' => now(),
            'recorded_by' => Auth::id(),
        ]);

        Receipt::create([
            'payment_id' => $payment->id,
            'receipt_number' => 'REC-'.now()->format('Y').'-'.str_pad((string) Payment::whereYear('created_at', now()->year)->count(), 4, '0', STR_PAD_LEFT),
            'issued_at' => now(),
        ]);

        $newBalance = $this->invoice->fresh()->balance();
        $this->invoice->update([
            'status' => $newBalance <= 0 ? 'paye' : 'partiel',
        ]);

        $this->invoice->student->user?->notify(new PaymentRecorded($payment->fresh('invoice')));

        $this->invoice->load('payments.paymentMethod', 'payments.receipt');
        $this->reset(['amount', 'payment_method_id', 'reference']);
        $this->amount = $this->invoice->balance() > 0 ? $this->invoice->balance() : null;

        session()->flash('status', 'Paiement enregistré.');
    }

    public function render()
    {
        return view('livewire.admin.finance.invoices.show', [
            'paymentMethods' => PaymentMethod::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}
