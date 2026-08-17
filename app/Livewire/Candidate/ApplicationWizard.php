<?php

namespace App\Livewire\Candidate;

use App\Models\AcademicYear;
use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\Formation;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.candidate')]
class ApplicationWizard extends Component
{
    use WithFileUploads;

    public int $step = 1;

    public ?Application $application = null;

    // Step 1
    public ?int $formation_id = null;

    // Step 2 — personal info
    public string $first_name = '';

    public string $last_name = '';

    public ?string $gender = null;

    public ?string $date_of_birth = null;

    public string $phone = '';

    public string $email = '';

    public string $address = '';

    // Step 3 — academic background
    public ?string $previous_school = null;

    public ?string $last_diploma = null;

    // Step 4 — documents
    public array $documentTypes = [
        'diplome' => 'Diplôme ou attestation le plus récent',
        'piece_identite' => "Pièce d'identité",
        'photo_identite' => "Photo d'identité",
        'certificat_scolarite' => 'Certificat de scolarité (optionnel)',
    ];

    /** @var array<string, mixed> */
    public array $documents = [];

    public function mount(): void
    {
        $formation = request()->query('formation');

        $candidate = auth()->user()->candidate;

        $this->first_name = $candidate->first_name;
        $this->last_name = $candidate->last_name;
        $this->gender = $candidate->gender;
        $this->date_of_birth = $candidate->date_of_birth?->format('Y-m-d');
        $this->phone = $candidate->phone ?? '';
        $this->email = $candidate->email ?? '';
        $this->address = $candidate->address ?? '';
        $this->previous_school = $candidate->previous_school;
        $this->last_diploma = $candidate->last_diploma;

        if ($formation) {
            $this->formation_id = Formation::where('slug', $formation)->where('is_published', true)->value('id');
        }

        $draft = Application::where('candidate_id', $candidate->id)->whereNull('submitted_at')->latest()->first();
        if ($draft) {
            $this->application = $draft;
            $this->formation_id ??= $draft->formation_id;
        }
    }

    public function nextStep(): void
    {
        match ($this->step) {
            1 => $this->validate(['formation_id' => ['required', 'exists:formations,id']]),
            2 => $this->validate([
                'first_name' => ['required', 'string', 'max:100'],
                'last_name' => ['required', 'string', 'max:100'],
                'phone' => ['required', 'string', 'max:30'],
                'email' => ['required', 'email'],
            ]),
            default => null,
        };

        if ($this->step === 2) {
            $this->saveCandidateProfile();
        }

        $this->step = min($this->step + 1, 4);
    }

    public function previousStep(): void
    {
        $this->step = max($this->step - 1, 1);
    }

    private function saveCandidateProfile(): void
    {
        auth()->user()->candidate->update([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'previous_school' => $this->previous_school,
            'last_diploma' => $this->last_diploma,
        ]);
    }

    public function submit(): void
    {
        $this->validate([
            'formation_id' => ['required', 'exists:formations,id'],
            'documents.diplome' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'documents.piece_identite' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'documents.photo_identite' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'documents.certificat_scolarite' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $this->saveCandidateProfile();

        $candidate = auth()->user()->candidate;
        $year = AcademicYear::current() ?? AcademicYear::first();

        $application = $this->application ?? new Application;
        $application->fill([
            'candidate_id' => $candidate->id,
            'formation_id' => $this->formation_id,
            'academic_year_id' => $year->id,
            'status' => 'nouveau',
            'submitted_at' => now(),
        ]);

        if (! $application->exists) {
            $application->application_number = $this->generateApplicationNumber();
        }

        $application->save();

        foreach ($this->documents as $type => $file) {
            if (! $file) {
                continue;
            }

            // Identity documents are sensitive PII — stored on the private
            // disk, never under public/storage, and only ever served back
            // through an authenticated, ownership-checked route.
            $path = $file->store('candidatures/'.$application->application_number, 'local');

            ApplicationDocument::create([
                'application_id' => $application->id,
                'document_type' => $this->documentTypes[$type] ?? $type,
                'file_path' => $path,
                'status' => 'en_attente',
            ]);
        }

        $application->statusHistory()->create([
            'status' => 'nouveau',
            'comment' => 'Candidature soumise en ligne.',
            'changed_by' => auth()->id(),
        ]);

        session()->flash('status', "Candidature soumise avec succès ! Votre numéro de dossier est {$application->application_number}.");

        $this->redirectRoute('candidate.dashboard', navigate: false);
    }

    private function generateApplicationNumber(): string
    {
        $year = now()->format('Y');
        $count = Application::whereYear('created_at', now()->year)->count() + 1;

        return sprintf('CAND-%s-%04d', $year, $count);
    }

    public function render()
    {
        return view('livewire.candidate.application-wizard', [
            'formations' => Formation::where('is_published', true)->orderBy('title')->get(),
        ]);
    }
}
