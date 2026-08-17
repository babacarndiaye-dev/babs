<?php

namespace App\Livewire\Student;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.student')]
class Attendances extends Component
{
    public function render()
    {
        $student = auth()->user()->student;

        $attendances = $student
            ?->attendances()
            ->with('subject')
            ->orderByDesc('date')
            ->get() ?? collect();

        $total = $attendances->count();
        $present = $attendances->whereIn('status', ['present', 'retard'])->count();

        return view('livewire.student.attendances', [
            'attendances' => $attendances,
            'presenceRate' => $total > 0 ? round($present / $total * 100) : 100,
        ]);
    }
}
