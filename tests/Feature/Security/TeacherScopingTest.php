<?php

namespace Tests\Feature\Security;

use App\Livewire\Teacher\Assessments\Grades;
use App\Livewire\Teacher\Attendances\Index as AttendancesIndex;
use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\Grade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Feature\Concerns\CreatesSchoolData;
use Tests\TestCase;

/**
 * Regression coverage for the IDOR class of bug fixed in this phase: a
 * teacher's public Livewire properties (assignmentId, scores[], statuses[])
 * are client-controllable and must never let a request write attendance or
 * grades for a student outside the teacher's own class assignment.
 */
class TeacherScopingTest extends TestCase
{
    use CreatesSchoolData, RefreshDatabase;

    public function test_teacher_cannot_record_attendance_for_a_student_outside_their_class(): void
    {
        $this->seedBase();

        $year = $this->createAcademicYear();
        $ownClass = $this->createSchoolClass(null, $year);
        $otherClass = $this->createSchoolClass(null, $year);
        $subject = $this->createSubject();

        [, $teacher] = $this->createTeacher();
        $assignment = $this->assignTeacherToClass($teacher, $ownClass, $subject);

        [, $ownStudent] = $this->createStudent($ownClass);
        [, $foreignStudent] = $this->createStudent($otherClass);

        Livewire::actingAs($teacher->user)
            ->test(AttendancesIndex::class)
            ->set('assignmentId', $assignment->id)
            ->set('date', now()->format('Y-m-d'))
            // Tamper the statuses array with a student who is not on this
            // teacher's roster — simulates a crafted Livewire payload.
            ->set('statuses', [
                $ownStudent->id => 'present',
                $foreignStudent->id => 'absent',
            ])
            ->call('save');

        $this->assertDatabaseHas('attendances', [
            'student_id' => $ownStudent->id,
            'class_id' => $ownClass->id,
        ]);

        $this->assertDatabaseMissing('attendances', [
            'student_id' => $foreignStudent->id,
        ]);
    }

    public function test_teacher_cannot_target_another_teachers_class_assignment_for_attendance(): void
    {
        $this->seedBase();

        $year = $this->createAcademicYear();
        $class = $this->createSchoolClass(null, $year);
        $subject = $this->createSubject();

        [, $owningTeacher] = $this->createTeacher();
        $assignment = $this->assignTeacherToClass($owningTeacher, $class, $subject);

        [, $attackerTeacher] = $this->createTeacher();
        [, $student] = $this->createStudent($class);

        Livewire::actingAs($attackerTeacher->user)
            ->test(AttendancesIndex::class)
            ->set('assignmentId', $assignment->id)
            ->set('date', now()->format('Y-m-d'))
            ->set('statuses', [$student->id => 'absent'])
            ->call('save');

        $this->assertDatabaseMissing('attendances', [
            'student_id' => $student->id,
        ]);
    }

    public function test_teacher_cannot_record_a_grade_for_a_student_outside_the_assessments_class(): void
    {
        $this->seedBase();

        $year = $this->createAcademicYear();
        $class = $this->createSchoolClass(null, $year);
        $otherClass = $this->createSchoolClass(null, $year);
        $subject = $this->createSubject();

        [, $teacher] = $this->createTeacher();
        $this->assignTeacherToClass($teacher, $class, $subject);

        [, $ownStudent] = $this->createStudent($class);
        [, $foreignStudent] = $this->createStudent($otherClass);

        $assessment = Assessment::create([
            'class_id' => $class->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'academic_year_id' => $year->id,
            'type' => 'devoir',
            'title' => 'Devoir 1',
            'max_score' => 20,
            'coefficient' => 1,
        ]);

        Livewire::actingAs($teacher->user)
            ->test(Grades::class, ['assessment' => $assessment])
            ->set('scores', [
                $ownStudent->id => 15,
                $foreignStudent->id => 18,
            ])
            ->call('save');

        $this->assertSame(15.0, (float) Grade::where('assessment_id', $assessment->id)
            ->where('student_id', $ownStudent->id)->value('score'));

        $this->assertDatabaseMissing('grades', [
            'assessment_id' => $assessment->id,
            'student_id' => $foreignStudent->id,
        ]);
    }

    public function test_teacher_cannot_open_the_grade_entry_screen_for_another_teachers_assessment(): void
    {
        $this->seedBase();

        $year = $this->createAcademicYear();
        $class = $this->createSchoolClass(null, $year);
        $subject = $this->createSubject();

        [, $owningTeacher] = $this->createTeacher();
        [, $attackerTeacher] = $this->createTeacher();

        $assessment = Assessment::create([
            'class_id' => $class->id,
            'subject_id' => $subject->id,
            'teacher_id' => $owningTeacher->id,
            'academic_year_id' => $year->id,
            'type' => 'devoir',
            'title' => 'Devoir 1',
            'max_score' => 20,
            'coefficient' => 1,
        ]);

        $this->actingAs($attackerTeacher->user)
            ->get("/enseignant/evaluations/{$assessment->id}/notes")
            ->assertForbidden();
    }
}
