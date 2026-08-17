<?php

namespace App\Livewire\Admin\ReportCards;

use App\Models\Grade;
use App\Models\GradingSystem;
use App\Models\ReportCard;
use App\Models\ReportCardLine;
use App\Models\SchoolClass;
use App\Notifications\ReportCardPublished;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    public ?int $classId = null;

    public string $period = 'Trimestre 1';

    /** @var array<int, array{decision: ?string, comment: ?string}> */
    public array $edits = [];

    public function mount(): void
    {
        $this->classId = SchoolClass::orderBy('name')->value('id');
    }

    public function generate(): void
    {
        $this->validate([
            'classId' => ['required', 'exists:classes,id'],
            'period' => ['required', 'string', 'max:100'],
        ]);

        $class = SchoolClass::with(['students', 'subjectAssignments'])->findOrFail($this->classId);
        $threshold = (float) (GradingSystem::where('is_default', true)->value('passing_threshold') ?? 10);

        foreach ($class->students as $student) {
            $weightedSum = 0;
            $coefSum = 0;
            $subjectAverages = [];

            foreach ($class->subjectAssignments as $assignment) {
                $grades = Grade::where('student_id', $student->id)
                    ->whereNotNull('score')
                    ->whereHas('assessment', fn ($q) => $q->where('class_id', $class->id)->where('subject_id', $assignment->subject_id))
                    ->with('assessment')
                    ->get();

                if ($grades->isEmpty()) {
                    continue;
                }

                $gWeighted = $grades->sum(fn ($g) => ($g->score / $g->assessment->max_score * 20) * $g->assessment->coefficient);
                $gCoefSum = $grades->sum(fn ($g) => $g->assessment->coefficient);

                if ($gCoefSum <= 0) {
                    continue;
                }

                $subjectAverage = round($gWeighted / $gCoefSum, 2);
                $coefficient = (float) ($assignment->coefficient ?? 1);

                $weightedSum += $subjectAverage * $coefficient;
                $coefSum += $coefficient;

                $subjectAverages[$assignment->subject_id] = ['average' => $subjectAverage, 'coefficient' => $coefficient];
            }

            $generalAverage = $coefSum > 0 ? round($weightedSum / $coefSum, 2) : null;

            $reportCard = ReportCard::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'class_id' => $class->id,
                    'academic_year_id' => $class->academic_year_id,
                    'period' => $this->period,
                ],
                [
                    'general_average' => $generalAverage,
                    'decision' => $generalAverage === null ? null : ($generalAverage >= $threshold ? 'Admis(e)' : 'Doit progresser'),
                ]
            );

            foreach ($subjectAverages as $subjectId => $data) {
                ReportCardLine::updateOrCreate(
                    ['report_card_id' => $reportCard->id, 'subject_id' => $subjectId],
                    ['average' => $data['average'], 'coefficient' => $data['coefficient']]
                );
            }
        }

        $this->computeRanks($class->id);

        // Force the editable decision/comment fields to reload from the
        // freshly generated data instead of keeping stale cached values.
        $this->edits = [];

        session()->flash('status', 'Bulletins générés pour '.$this->period.'.');
    }

    private function computeRanks(int $classId): void
    {
        $cards = ReportCard::where('class_id', $classId)
            ->where('period', $this->period)
            ->orderByDesc('general_average')
            ->get();

        $classSize = $cards->count();
        $rank = 0;
        $position = 0;
        $previousAverage = false; // sentinel distinct from a real null/float average so the first row always gets a rank

        foreach ($cards as $card) {
            $position++;

            if ($card->general_average !== $previousAverage) {
                $rank = $position;
                $previousAverage = $card->general_average;
            }

            $card->update(['rank' => $rank, 'class_size' => $classSize]);
        }
    }

    public function saveEdits(): void
    {
        foreach ($this->edits as $id => $data) {
            ReportCard::whereKey($id)->update([
                'decision' => $data['decision'] ?? null,
                'comment' => $data['comment'] ?? null,
            ]);
        }

        session()->flash('status', 'Commentaires enregistrés.');
    }

    public function publish(): void
    {
        $cards = ReportCard::where('class_id', $this->classId)
            ->where('period', $this->period)
            ->with('student.user')
            ->get();

        foreach ($cards as $card) {
            $card->update(['published_at' => now()]);
            $card->student->user?->notify(new ReportCardPublished($card));
        }

        session()->flash('status', 'Bulletins publiés — visibles par les étudiants.');
    }

    public function render()
    {
        $reportCards = collect();

        if ($this->classId) {
            $reportCards = ReportCard::where('class_id', $this->classId)
                ->where('period', $this->period)
                ->with('student')
                ->orderBy('rank')
                ->get();

            foreach ($reportCards as $card) {
                $this->edits[$card->id] ??= ['decision' => $card->decision, 'comment' => $card->comment];
            }
        }

        return view('livewire.admin.report-cards.index', [
            'classes' => SchoolClass::orderBy('name')->get(),
            'reportCards' => $reportCards,
        ]);
    }
}
