<?php

namespace App\Livewire\Teacher;

use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\SchoolClass;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.teacher')]
class Dashboard extends Component
{
    public function render()
    {
        $teacher = auth()->user()->teacher;
        $today = now();

        $assignments = $teacher?->classAssignments()->with('schoolClass', 'subject')->get() ?? collect();
        $classIds = $assignments->pluck('class_id')->unique()->values();

        $studentsTotal = SchoolClass::whereIn('id', $classIds)
            ->withCount('students')
            ->get()
            ->sum('students_count');

        $todaysAttendance = Attendance::whereIn('class_id', $classIds)->whereDate('date', $today)->get();
        $presentToday = $todaysAttendance->whereIn('status', ['present', 'retard'])->pluck('student_id')->unique()->count();
        $absentToday = $todaysAttendance->where('status', 'absent')->pluck('student_id')->unique()->count();
        $attendanceRate = $todaysAttendance->isNotEmpty()
            ? (int) round($todaysAttendance->whereIn('status', ['present', 'retard'])->count() / $todaysAttendance->count() * 100)
            : null;

        $grades = Grade::whereNotNull('score')
            ->whereHas('assessment', fn ($q) => $q->where('teacher_id', $teacher?->id))
            ->get();
        $averageGeneral = $grades->isNotEmpty() ? round((float) $grades->avg('score'), 1) : null;

        $todaysSchedules = $teacher?->schedules()
            ->with(['schoolClass', 'subject', 'room'])
            ->where('day_of_week', $today->isoWeekday())
            ->orderBy('start_time')
            ->get() ?? collect();

        $studentsToWatch = Attendance::whereIn('class_id', $classIds)
            ->where('status', 'absent')
            ->where('date', '>=', $today->copy()->subDays(30))
            ->selectRaw('student_id, COUNT(*) as absences')
            ->groupBy('student_id')
            ->havingRaw('COUNT(*) >= 3')
            ->get()
            ->count();

        $assessments = Assessment::where('teacher_id', $teacher?->id)->withCount('grades')->get();
        $rosterSizes = SchoolClass::whereIn('id', $assessments->pluck('class_id')->unique())
            ->withCount('students')
            ->get()
            ->keyBy('id');

        $pendingGrading = $assessments->filter(function ($assessment) use ($rosterSizes) {
            $roster = $rosterSizes->get($assessment->class_id)?->students_count ?? 0;

            return $roster > 0 && $assessment->grades_count < $roster;
        })->count();

        $fullyGraded = $assessments->filter(function ($assessment) use ($rosterSizes) {
            $roster = $rosterSizes->get($assessment->class_id)?->students_count ?? 0;

            return $roster > 0 && $assessment->grades_count >= $roster;
        })->count();

        $strugglingStudents = $grades->groupBy('student_id')
            ->filter(fn ($studentGrades) => $studentGrades->avg('score') < 10)
            ->count();

        return view('livewire.teacher.dashboard', [
            'studentsTotal' => $studentsTotal,
            'presentToday' => $presentToday,
            'absentToday' => $absentToday,
            'attendanceRate' => $attendanceRate,
            'averageGeneral' => $averageGeneral,
            'gradesCount' => $grades->count(),
            'todaysSchedules' => $todaysSchedules,
            'classesCount' => $classIds->count(),
            'studentsToWatch' => $studentsToWatch,
            'pendingGrading' => $pendingGrading,
            'strugglingStudents' => $strugglingStudents,
            'fullyGraded' => $fullyGraded,
        ]);
    }
}
