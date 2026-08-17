<?php

namespace App\Livewire\Student;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.student')]
class Payments extends Component
{
    public function render()
    {
        $student = auth()->user()->student;

        $invoices = $student
            ?->invoices()
            ->with(['lines', 'payments.paymentMethod', 'payments.receipt'])
            ->latest()
            ->get() ?? collect();

        return view('livewire.student.payments', [
            'invoices' => $invoices,
            'totalBalance' => $invoices->sum(fn ($invoice) => $invoice->balance()),
        ]);
    }
}
