<?php

namespace App\Livewire\Student;

use App\Models\Document;
use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.student')]
class Dashboard extends Component
{
    public function render()
    {
        $student = auth()->user()->student;

        $grades = $student?->grades()->with('assessment')->get() ?? collect();
        $weighted = $grades->sum(fn ($g) => $g->score * $g->assessment->coefficient);
        $totalCoef = $grades->sum(fn ($g) => $g->assessment->coefficient);

        $attendances = $student?->attendances ?? collect();
        $presentCount = $attendances->whereIn('status', ['present', 'retard'])->count();
        $unexcusedAbsences = $attendances->where('status', 'absent')->count();

        $invoices = $student?->invoices ?? collect();
        $balance = $invoices->sum(fn ($invoice) => $invoice->balance());
        $overdueInvoices = $invoices->where('status', 'en_retard')->count();

        $documentsCount = $student
            ? Document::where('documentable_type', Student::class)->where('documentable_id', $student->id)->count()
            : 0;

        $todaysSchedules = $student?->currentClass
            ?->schedules()
            ->with(['subject', 'teacher', 'room'])
            ->where('day_of_week', now()->isoWeekday())
            ->orderBy('start_time')
            ->get() ?? collect();

        return view('livewire.student.dashboard', [
            'student' => $student,
            'balance' => $balance,
            'generalAverage' => $totalCoef > 0 ? round($weighted / $totalCoef, 2) : null,
            'gradesCount' => $grades->count(),
            'presenceRate' => $attendances->isNotEmpty() ? round($presentCount / $attendances->count() * 100) : null,
            'unexcusedAbsences' => $unexcusedAbsences,
            'overdueInvoices' => $overdueInvoices,
            'documentsCount' => $documentsCount,
            'reportCardsPublished' => $student?->reportCards()->whereNotNull('published_at')->count() ?? 0,
            'todaysSchedules' => $todaysSchedules,
        ]);
    }
}
