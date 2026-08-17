<?php

namespace App\Livewire\Teacher\Assessments;

use App\Models\AcademicYear;
use App\Models\Assessment;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.teacher')]
class Index extends Component
{
    public ?int $assignmentId = null;

    public string $type = 'devoir';

    public string $title = '';

    public ?string $date = null;

    public float $max_score = 20;

    public float $coefficient = 1;

    public array $types = [
        'devoir' => 'Devoir',
        'interrogation' => 'Interrogation',
        'examen' => 'Examen',
        'pratique' => 'Travaux pratiques',
        'projet' => 'Projet',
        'continu' => 'Contrôle continu',
        'stage' => 'Stage',
    ];

    public function mount(): void
    {
        $this->date = now()->format('Y-m-d');
    }

    protected function rules(): array
    {
        return [
            'assignmentId' => ['required', 'exists:class_subject_teacher,id'],
            'type' => ['required', 'string'],
            'title' => ['required', 'string', 'max:150'],
            'date' => ['nullable', 'date'],
            'max_score' => ['required', 'numeric', 'min:1'],
            'coefficient' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $teacher = auth()->user()->teacher;
        $assignment = $teacher->classAssignments()->findOrFail($this->assignmentId);

        Assessment::create([
            'class_id' => $assignment->class_id,
            'subject_id' => $assignment->subject_id,
            'teacher_id' => $teacher->id,
            'academic_year_id' => $assignment->academic_year_id ?? AcademicYear::current()?->id,
            'type' => $this->type,
            'title' => $this->title,
            'date' => $this->date,
            'max_score' => $this->max_score,
            'coefficient' => $this->coefficient,
        ]);

        $this->reset(['title']);
        session()->flash('status', 'Évaluation créée.');
    }

    public function render()
    {
        $teacher = auth()->user()->teacher;

        return view('livewire.teacher.assessments.index', [
            'assignments' => $teacher?->classAssignments()->with('schoolClass', 'subject')->get() ?? collect(),
            'assessments' => Assessment::where('teacher_id', $teacher?->id)
                ->with(['schoolClass', 'subject'])
                ->withCount('grades')
                ->latest('date')
                ->get(),
        ]);
    }
}
