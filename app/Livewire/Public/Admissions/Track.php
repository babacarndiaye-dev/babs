<?php

namespace App\Livewire\Public\Admissions;

use App\Models\Application;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public')]
class Track extends Component
{
    public string $application_number = '';

    public string $email = '';

    public ?Application $result = null;

    public bool $searched = false;

    public function search(): void
    {
        $this->validate([
            'application_number' => ['required', 'string'],
            'email' => ['required', 'email'],
        ]);

        $this->searched = true;

        $this->result = Application::with(['formation', 'statusHistory' => fn ($q) => $q->latest()])
            ->where('application_number', $this->application_number)
            ->whereHas('candidate', fn ($q) => $q->where('email', $this->email))
            ->first();
    }

    public function render()
    {
        return view('livewire.public.admissions.track');
    }
}
