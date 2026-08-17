<?php

namespace App\Livewire\Admin\Applications;

use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\Student;
use App\Notifications\ApplicationStatusChanged;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class Show extends Component
{
    public Application $application;

    public string $newStatus = '';

    public string $comment = '';

    public array $statuses = [
        'nouveau' => 'Nouveau',
        'en_etude' => 'En étude',
        'incomplet' => 'Incomplet',
        'pieces_complementaires_demandees' => 'Pièces complémentaires demandées',
        'admis' => 'Admis',
        'refuse' => 'Refusé',
        'inscrit' => 'Inscrit',
    ];

    public function mount(Application $application): void
    {
        $this->application = $application->load(['candidate', 'formation', 'documents', 'statusHistory' => fn ($q) => $q->latest()]);
        $this->newStatus = $application->status;
    }

    public function updateStatus(): void
    {
        $this->validate([
            'newStatus' => ['required', 'in:'.implode(',', array_keys($this->statuses))],
            'comment' => ['nullable', 'string'],
        ]);

        $this->application->update(['status' => $this->newStatus, 'decision_at' => now()]);

        $this->application->statusHistory()->create([
            'status' => $this->newStatus,
            'comment' => $this->comment ?: $this->statuses[$this->newStatus],
            'changed_by' => auth()->id(),
        ]);

        if ($this->newStatus === 'inscrit') {
            $this->convertToStudent();
        }

        $this->application->candidate->user?->notify(new ApplicationStatusChanged($this->application));

        $this->comment = '';
        $this->application->refresh()->load(['statusHistory' => fn ($q) => $q->latest()]);

        session()->flash('status', 'Statut mis à jour.');
    }

    public function reviewDocument(ApplicationDocument $document, string $status): void
    {
        abort_unless($document->application_id === $this->application->id, 403);

        $document->update(['status' => $status]);
        $this->application->refresh()->load('documents');
    }

    private function convertToStudent(): void
    {
        $candidate = $this->application->candidate;
        $user = $candidate->user;

        if (! $user || $user->student()->exists()) {
            return;
        }

        $matricule = 'ETU-'.str_pad((string) (Student::max('id') + 1), 4, '0', STR_PAD_LEFT);

        Student::create([
            'user_id' => $user->id,
            'matricule' => $matricule,
            'first_name' => $candidate->first_name,
            'last_name' => $candidate->last_name,
            'gender' => $candidate->gender,
            'date_of_birth' => $candidate->date_of_birth,
            'phone' => $candidate->phone,
            'email' => $candidate->email,
            'address' => $candidate->address,
            'status' => 'actif',
            'enrolled_at' => now(),
        ]);

        $user->syncRoles(['etudiant']);
    }

    public function render()
    {
        return view('livewire.admin.applications.show');
    }
}
