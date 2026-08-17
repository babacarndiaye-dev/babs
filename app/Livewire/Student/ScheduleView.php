<?php

namespace App\Livewire\Student;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.student')]
class ScheduleView extends Component
{
    public array $days = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'];

    public function render()
    {
        $class = auth()->user()->student?->currentClass;

        $schedules = $class
            ?->schedules()
            ->with(['subject', 'teacher', 'room'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week') ?? collect();

        return view('livewire.student.schedule', [
            'schedulesByDay' => $schedules,
        ]);
    }
}
