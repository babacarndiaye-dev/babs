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

        $grades = $student?->grades()->with('assessment')->get() ?? collect();
        $weighted = $grades->sum(fn ($g) => $g->score * $g->assessment->coefficient);
        $totalCoef = $grades->sum(fn ($g) => $g->assessment->coefficient);

        $attendances = $student?->attendances ?? collect();
        $presentCount = $attendances->whereIn('status', ['present', 'retard'])->count();

        return view('livewire.student.dashboard', [
            'student' => $student,
            'balance' => $student ? $student->invoices->sum(fn ($invoice) => $invoice->balance()) : 0,
            'generalAverage' => $totalCoef > 0 ? round($weighted / $totalCoef, 2) : null,
            'presenceRate' => $attendances->isNotEmpty() ? round($presentCount / $attendances->count() * 100) : null,
        ]);
    }
}
