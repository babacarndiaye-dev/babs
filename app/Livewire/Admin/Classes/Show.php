<?php

namespace App\Livewire\Admin\Classes;

use App\Models\ClassSubjectTeacher;
use App\Models\Room;
use App\Models\Schedule;
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

    public ?int $schedule_subject_id = null;

    public ?int $schedule_room_id = null;

    public int $day_of_week = 1;

    public string $start_time = '08:00';

    public string $end_time = '10:00';

    public array $days = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'];

    public function mount(SchoolClass $class): void
    {
        $this->class = $class->load([
            'formation', 'academicYear', 'room', 'students',
            'subjectAssignments.subject', 'subjectAssignments.teacher',
            'schedules.subject', 'schedules.teacher', 'schedules.room',
        ]);
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

    public function addSchedule(): void
    {
        $this->validate([
            'schedule_subject_id' => ['required', 'exists:subjects,id'],
            'day_of_week' => ['required', 'integer', 'min:1', 'max:7'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'schedule_room_id' => ['nullable', 'exists:rooms,id'],
        ]);

        $assignment = $this->class->subjectAssignments->firstWhere('subject_id', $this->schedule_subject_id);

        if (! $assignment) {
            $this->addError('schedule_subject_id', "Affectez d'abord un enseignant à cette matière pour cette classe.");

            return;
        }

        Schedule::create([
            'class_id' => $this->class->id,
            'subject_id' => $this->schedule_subject_id,
            'teacher_id' => $assignment->teacher_id,
            'room_id' => $this->schedule_room_id ?: $this->class->room_id,
            'academic_year_id' => $this->class->academic_year_id,
            'day_of_week' => $this->day_of_week,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ]);

        $this->reset(['schedule_subject_id', 'schedule_room_id']);
        $this->class->load('schedules.subject', 'schedules.teacher', 'schedules.room');

        session()->flash('status', 'Créneau ajouté.');
    }

    public function removeSchedule(Schedule $schedule): void
    {
        abort_unless($schedule->class_id === $this->class->id, 403);

        $schedule->delete();
        $this->class->load('schedules.subject', 'schedules.teacher', 'schedules.room');
    }

    public function render()
    {
        return view('livewire.admin.classes.show', [
            'subjects' => Subject::orderBy('name')->get(),
            'teachers' => Teacher::orderBy('first_name')->get(),
            'rooms' => Room::orderBy('name')->get(),
        ]);
    }
}
