<?php

namespace App\Livewire\Student;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.student')]
class Dashboard extends Component
{
    public function render()
    {
        $student = auth()->user()->student;

        return view('livewire.student.dashboard', [
            'student' => $student,
            'balance' => $student ? $student->invoices->sum(fn ($invoice) => $invoice->balance()) : 0,
        ]);
    }
}
