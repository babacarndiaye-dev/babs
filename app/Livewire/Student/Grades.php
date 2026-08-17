<?php

namespace App\Livewire\Student;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.student')]
class Grades extends Component
{
    public function render()
    {
        $student = auth()->user()->student;

        $grades = $student
            ?->grades()
            ->with('assessment.subject')
            ->latest()
            ->get() ?? collect();

        $bySubject = $grades->groupBy(fn ($grade) => $grade->assessment->subject->name)
            ->map(function ($group) {
                $weighted = $group->sum(fn ($g) => $g->score * $g->assessment->coefficient);
                $totalCoef = $group->sum(fn ($g) => $g->assessment->coefficient);

                return [
                    'grades' => $group,
                    'average' => $totalCoef > 0 ? round($weighted / $totalCoef, 2) : null,
                ];
            });

        return view('livewire.student.grades', [
            'bySubject' => $bySubject,
        ]);
    }
}
