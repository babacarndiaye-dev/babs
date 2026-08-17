<?php

namespace App\Livewire\Teacher;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.teacher')]
class ScheduleView extends Component
{
    public array $days = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'];

    public function render()
    {
        $teacher = auth()->user()->teacher;

        $schedules = $teacher
            ?->schedules()
            ->with(['schoolClass', 'subject', 'room'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week') ?? collect();

        return view('livewire.teacher.schedule', [
            'schedulesByDay' => $schedules,
        ]);
    }
}
