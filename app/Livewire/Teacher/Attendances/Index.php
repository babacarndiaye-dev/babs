<?php

namespace App\Livewire\Teacher\Attendances;

use App\Models\Attendance;
use App\Models\ClassSubjectTeacher;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.teacher')]
class Index extends Component
{
    public ?int $assignmentId = null;

    public string $date;

    /** @var array<int, string> */
    public array $statuses = [];

    public function mount(): void
    {
        $this->date = now()->format('Y-m-d');

        $teacher = auth()->user()->teacher;
        $first = $teacher?->classAssignments()->first();
        $this->assignmentId = $first?->id;

        $this->loadStatuses();
    }

    public function updated($property): void
    {
        if (in_array($property, ['assignmentId', 'date'], true)) {
            $this->loadStatuses();
        }
    }

    private function loadStatuses(): void
    {
        $assignment = $this->currentAssignment();

        if (! $assignment) {
            $this->statuses = [];

            return;
        }

        $existing = Attendance::where('class_id', $assignment->class_id)
            ->where('subject_id', $assignment->subject_id)
            ->where('date', $this->date)
            ->pluck('status', 'student_id');

        $this->statuses = $assignment->schoolClass->students
            ->mapWithKeys(fn ($student) => [$student->id => $existing[$student->id] ?? 'present'])
            ->all();
    }

    private function currentAssignment(): ?ClassSubjectTeacher
    {
        if (! $this->assignmentId) {
            return null;
        }

        return ClassSubjectTeacher::with('schoolClass.students', 'subject')->find($this->assignmentId);
    }

    public function save(): void
    {
        $assignment = $this->currentAssignment();

        abort_unless($assignment, 404);

        foreach ($this->statuses as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_id' => $assignment->class_id,
                    'subject_id' => $assignment->subject_id,
                    'date' => $this->date,
                ],
                ['status' => $status, 'recorded_by' => Auth::id()]
            );
        }

        session()->flash('status', 'Présences enregistrées.');
    }

    public function render()
    {
        $teacher = auth()->user()->teacher;

        return view('livewire.teacher.attendances.index', [
            'assignments' => $teacher?->classAssignments()->with('schoolClass', 'subject')->get() ?? collect(),
            'assignment' => $this->currentAssignment(),
        ]);
    }
}
