<?php

namespace Tests\Feature;

use App\Livewire\Candidate\ApplicationWizard;
use App\Livewire\Public\Admissions\Start;
use App\Livewire\Public\Admissions\Track;
use App\Models\Application;
use App\Models\ApplicationDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\Feature\Concerns\CreatesSchoolData;
use Tests\TestCase;

class AdmissionsFlowTest extends TestCase
{
    use CreatesSchoolData, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Rate-limiter counters live in the array cache store, which
        // persists across tests within the same process — start clean.
        Cache::flush();
    }

    public function test_a_candidate_can_register_and_submit_a_complete_application(): void
    {
        $this->seedBase();
        Storage::fake('local');
        $this->createAcademicYear();
        $formation = $this->createFormation();

        Livewire::test(Start::class)
            ->set('first_name', 'Awa')
            ->set('last_name', 'Diallo')
            ->set('email', 'awa.diallo@example.test')
            ->set('phone', '770000000')
            ->set('password', 'password123')
            ->call('register')
            ->assertRedirect();

        $this->assertDatabaseHas('users', ['email' => 'awa.diallo@example.test']);
        $this->assertTrue(auth()->check());
        $this->assertTrue(auth()->user()->hasRole('candidat'));

        Livewire::actingAs(auth()->user())
            ->test(ApplicationWizard::class)
            ->set('formation_id', $formation->id)
            ->call('nextStep')
            ->set('first_name', 'Awa')
            ->set('last_name', 'Diallo')
            ->set('phone', '770000000')
            ->set('email', 'awa.diallo@example.test')
            ->call('nextStep')
            ->call('nextStep')
            ->set('documents.piece_identite', UploadedFile::fake()->create('cni.pdf', 100, 'application/pdf'))
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('applications', [
            'formation_id' => $formation->id,
            'status' => 'nouveau',
        ]);

        $application = Application::first();
        $this->assertNotNull($application->submitted_at);
        $this->assertDatabaseHas('application_documents', [
            'application_id' => $application->id,
            'document_type' => "Pièce d'identité",
        ]);

        Storage::disk('local')->assertExists(
            ApplicationDocument::first()->file_path
        );
    }

    public function test_submitting_without_the_required_identity_document_fails_validation(): void
    {
        $this->seedBase();
        Storage::fake('local');
        [$user, $candidate] = $this->createCandidate();
        $formation = $this->createFormation();

        Livewire::actingAs($user)
            ->test(ApplicationWizard::class)
            ->set('formation_id', $formation->id)
            ->set('first_name', 'Awa')
            ->set('last_name', 'Diallo')
            ->set('phone', '770000000')
            ->set('email', 'awa.diallo@example.test')
            ->call('submit')
            ->assertHasErrors(['documents.piece_identite']);

        $this->assertDatabaseCount('applications', 0);
    }

    public function test_registration_is_rate_limited_per_ip(): void
    {
        $this->seedBase();

        for ($i = 0; $i < 10; $i++) {
            Livewire::test(Start::class)
                ->set('first_name', 'Test')
                ->set('last_name', 'User')
                ->set('email', "candidate{$i}@example.test")
                ->set('phone', '770000000')
                ->set('password', 'password123')
                ->call('register');

            auth()->logout();
        }

        Livewire::test(Start::class)
            ->set('first_name', 'Test')
            ->set('last_name', 'Overflow')
            ->set('email', 'overflow@example.test')
            ->set('phone', '770000000')
            ->set('password', 'password123')
            ->call('register')
            ->assertHasErrors(['email']);

        $this->assertDatabaseMissing('users', ['email' => 'overflow@example.test']);
    }

    public function test_public_tracking_requires_matching_application_number_and_email(): void
    {
        $this->seedBase();
        [, $candidate] = $this->createCandidate();
        $formation = $this->createFormation();
        $year = $this->createAcademicYear();

        Application::create([
            'application_number' => 'CAND-TEST-TRACK',
            'candidate_id' => $candidate->id,
            'formation_id' => $formation->id,
            'academic_year_id' => $year->id,
            'status' => 'nouveau',
            'submitted_at' => now(),
        ]);

        Livewire::test(Track::class)
            ->set('application_number', 'CAND-TEST-TRACK')
            ->set('email', 'wrong@example.test')
            ->call('search')
            ->assertSet('result', null);

        Livewire::test(Track::class)
            ->set('application_number', 'CAND-TEST-TRACK')
            ->set('email', $candidate->email)
            ->call('search')
            ->assertSet('result.application_number', 'CAND-TEST-TRACK');
    }
}
