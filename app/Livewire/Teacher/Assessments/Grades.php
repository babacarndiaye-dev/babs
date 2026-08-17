<?php

namespace App\Livewire\Teacher\Assessments;

use App\Models\Assessment;
use App\Models\Grade;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.teacher')]
class Grades extends Component
{
    public Assessment $assessment;

    /** @var array<int, string> */
    public array $scores = [];

    public function mount(Assessment $assessment): void
    {
        abort_unless($assessment->teacher_id === auth()->user()->teacher?->id, 403);

        $this->assessment = $assessment->load('schoolClass.students', 'subject');

        $existing = Grade::where('assessment_id', $assessment->id)->pluck('score', 'student_id');

        $this->scores = $this->assessment->schoolClass->students
            ->mapWithKeys(fn ($student) => [$student->id => $existing[$student->id] ?? ''])
            ->all();
    }

    public function save(): void
    {
        $this->validate([
            'scores.*' => ['nullable', 'numeric', 'min:0', 'max:'.$this->assessment->max_score],
        ]);

        // scores is a public array keyed by student_id and therefore
        // attacker-controllable — reject any key that isn't actually on
        // this assessment's class roster before writing a grade.
        $rosterIds = $this->assessment->schoolClass->students->pluck('id')->all();

        foreach ($this->scores as $studentId => $score) {
            if ($score === '' || $score === null) {
                continue;
            }

            if (! in_array((int) $studentId, $rosterIds, true)) {
                continue;
            }

            Grade::updateOrCreate(
                ['assessment_id' => $this->assessment->id, 'student_id' => $studentId],
                ['score' => $score]
            );
        }

        session()->flash('status', 'Notes enregistrées.');
    }

    public function render()
    {
        return view('livewire.teacher.assessments.grades');
    }
}
