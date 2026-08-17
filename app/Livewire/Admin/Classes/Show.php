<?php

namespace App\Livewire\Admin\Classes;

use App\Models\ClassSubjectTeacher;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class Show extends Component
{
    public SchoolClass $class;

    public ?int $subject_id = null;

    public ?int $teacher_id = null;

    public ?float $coefficient = 1;

    public function mount(SchoolClass $class): void
    {
        $this->class = $class->load(['formation', 'academicYear', 'room', 'students', 'subjectAssignments.subject', 'subjectAssignments.teacher']);
    }

    public function addAssignment(): void
    {
        $this->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'coefficient' => ['nullable', 'numeric', 'min:0'],
        ]);

        ClassSubjectTeacher::updateOrCreate(
            [
                'class_id' => $this->class->id,
                'subject_id' => $this->subject_id,
                'academic_year_id' => $this->class->academic_year_id,
            ],
            ['teacher_id' => $this->teacher_id, 'coefficient' => $this->coefficient ?? 1]
        );

        $this->reset(['subject_id', 'teacher_id', 'coefficient']);
        $this->coefficient = 1;
        $this->class->load('subjectAssignments.subject', 'subjectAssignments.teacher');

        session()->flash('status', 'Affectation ajoutée.');
    }

    public function removeAssignment(ClassSubjectTeacher $assignment): void
    {
        abort_unless($assignment->class_id === $this->class->id, 403);

        $assignment->delete();
        $this->class->load('subjectAssignments.subject', 'subjectAssignments.teacher');
    }

    public function render()
    {
        return view('livewire.admin.classes.show', [
            'subjects' => Subject::orderBy('name')->get(),
            'teachers' => Teacher::orderBy('first_name')->get(),
        ]);
    }
}
