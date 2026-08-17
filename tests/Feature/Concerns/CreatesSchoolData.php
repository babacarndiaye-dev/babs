<?php

namespace Tests\Feature\Concerns;

use App\Models\AcademicYear;
use App\Models\Candidate;
use App\Models\ClassSubjectTeacher;
use App\Models\Formation;
use App\Models\FormationType;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SettingsSeeder;
use Illuminate\Support\Str;

trait CreatesSchoolData
{
    protected function seedBase(): void
    {
        $this->seed(SettingsSeeder::class);
        $this->seed(RolePermissionSeeder::class);
    }

    protected function createAcademicYear(): AcademicYear
    {
        return AcademicYear::create([
            'name' => '2026-2027',
            'start_date' => '2026-10-01',
            'end_date' => '2027-07-31',
            'is_current' => true,
        ]);
    }

    protected function createFormation(): Formation
    {
        $type = FormationType::create(['name' => 'BTS', 'code' => 'BTS-'.Str::random(6)]);

        return Formation::create([
            'formation_type_id' => $type->id,
            'title' => 'BTS Informatique',
            'slug' => 'bts-informatique-'.Str::random(6),
            'duration_value' => 2,
            'duration_unit' => 'ans',
            'is_published' => true,
        ]);
    }

    protected function createSchoolClass(?Formation $formation = null, ?AcademicYear $year = null): SchoolClass
    {
        $formation ??= $this->createFormation();
        $year ??= $this->createAcademicYear();

        return SchoolClass::create([
            'name' => 'Classe '.Str::random(6),
            'formation_id' => $formation->id,
            'academic_year_id' => $year->id,
        ]);
    }

    protected function createSubject(): Subject
    {
        return Subject::create([
            'name' => 'Mathématiques '.Str::random(4),
            'code' => Str::upper(Str::random(8)),
        ]);
    }

    protected function createAdminUser(string $role = 'super-admin'): User
    {
        $user = User::create([
            'name' => 'Admin '.Str::random(4),
            'email' => Str::random(10).'@eeht.test',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
        $user->assignRole($role);

        return $user;
    }

    /**
     * @return array{0: User, 1: Teacher}
     */
    protected function createTeacher(): array
    {
        $user = User::create([
            'name' => 'Enseignant '.Str::random(4),
            'email' => Str::random(10).'@eeht.test',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
        $user->assignRole('enseignant');

        $teacher = Teacher::create([
            'user_id' => $user->id,
            'matricule' => 'ENS-'.Str::random(6),
            'first_name' => 'Prénom',
            'last_name' => 'Nom',
            'is_active' => true,
        ]);

        return [$user, $teacher];
    }

    /**
     * @return array{0: User, 1: Student}
     */
    protected function createStudent(?SchoolClass $class = null): array
    {
        $class ??= $this->createSchoolClass();

        $user = User::create([
            'name' => 'Étudiant '.Str::random(4),
            'email' => Str::random(10).'@eeht.test',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
        $user->assignRole('etudiant');

        $student = Student::create([
            'user_id' => $user->id,
            'matricule' => 'ETU-'.Str::random(6),
            'first_name' => 'Prénom',
            'last_name' => 'Nom',
            'current_class_id' => $class->id,
            'status' => 'actif',
        ]);

        $student->classes()->attach($class->id, [
            'academic_year_id' => $class->academic_year_id,
            'status' => 'inscrit',
        ]);

        return [$user, $student];
    }

    /**
     * @return array{0: User, 1: Candidate}
     */
    protected function createCandidate(): array
    {
        $user = User::create([
            'name' => 'Candidat '.Str::random(4),
            'email' => Str::random(10).'@eeht.test',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
        $user->assignRole('candidat');

        $candidate = Candidate::create([
            'user_id' => $user->id,
            'first_name' => 'Prénom',
            'last_name' => 'Nom',
            'email' => $user->email,
        ]);

        return [$user, $candidate];
    }

    protected function assignTeacherToClass(Teacher $teacher, SchoolClass $class, Subject $subject): ClassSubjectTeacher
    {
        return ClassSubjectTeacher::create([
            'class_id' => $class->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'academic_year_id' => $class->academic_year_id,
        ]);
    }
}
