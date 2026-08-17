<?php

namespace App\Livewire\Student;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.student')]
class MyFormation extends Component
{
    public function render()
    {
        $student = auth()->user()->student->load('currentClass.formation.formationType', 'currentClass.room', 'currentClass.subjectAssignments.subject', 'currentClass.subjectAssignments.teacher');

        return view('livewire.student.my-formation', [
            'class' => $student->currentClass,
        ]);
    }
}
