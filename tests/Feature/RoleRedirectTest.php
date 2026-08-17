<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\CreatesSchoolData;
use Tests\TestCase;

class RoleRedirectTest extends TestCase
{
    use CreatesSchoolData, RefreshDatabase;

    public function test_admin_roles_are_redirected_to_the_admin_dashboard(): void
    {
        $this->seedBase();

        foreach (['super-admin', 'directeur', 'administrateur', 'responsable-academique', 'scolarite', 'comptable'] as $role) {
            $user = $this->createAdminUser($role);

            $this->assertSame('admin.dashboard', $user->homeRoute());
        }
    }

    public function test_teacher_is_redirected_to_the_teacher_dashboard(): void
    {
        $this->seedBase();
        [$user] = $this->createTeacher();

        $this->assertSame('teacher.dashboard', $user->homeRoute());
    }

    public function test_student_is_redirected_to_the_student_dashboard(): void
    {
        $this->seedBase();
        [$user] = $this->createStudent();

        $this->assertSame('student.dashboard', $user->homeRoute());
    }

    public function test_candidate_is_redirected_to_the_candidate_dashboard(): void
    {
        $this->seedBase();
        [$user] = $this->createCandidate();

        $this->assertSame('candidate.dashboard', $user->homeRoute());
    }

    public function test_a_student_cannot_access_the_admin_area(): void
    {
        $this->seedBase();
        [$user] = $this->createStudent();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertForbidden();
    }

    public function test_a_teacher_cannot_access_the_admin_area(): void
    {
        $this->seedBase();
        [$user] = $this->createTeacher();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertForbidden();
    }

    public function test_guests_are_redirected_away_from_the_admin_area(): void
    {
        $this->seedBase();

        $response = $this->get('/admin');

        $response->assertRedirect('/connexion');
    }
}
