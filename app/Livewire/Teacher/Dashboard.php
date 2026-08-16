<?php

namespace App\Livewire\Teacher;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.teacher')]
class Dashboard extends Component
{
    public function render()
    {
        $teacher = auth()->user()->teacher;

        return view('livewire.teacher.dashboard', [
            'classesCount' => $teacher?->schedules()->distinct('class_id')->count('class_id') ?? 0,
        ]);
    }
}
