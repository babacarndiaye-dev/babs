<?php

namespace App\Livewire\Teacher\Classes;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.teacher')]
class Index extends Component
{
    public function render()
    {
        $teacher = auth()->user()->teacher;

        $assignments = $teacher
            ?->classAssignments()
            ->with(['schoolClass.formation', 'subject'])
            ->get()
            ->groupBy('class_id') ?? collect();

        return view('livewire.teacher.classes.index', [
            'assignments' => $assignments,
        ]);
    }
}
