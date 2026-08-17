<?php

namespace App\Livewire\Admin\Students;

use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class Show extends Component
{
    public Student $student;

    public function mount(Student $student): void
    {
        $this->student = $student->load(['currentClass.formation', 'user', 'invoices', 'internships.company']);
    }

    public function render()
    {
        return view('livewire.admin.students.show');
    }
}
