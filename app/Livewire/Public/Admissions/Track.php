<?php

namespace App\Livewire\Public\Admissions;

use App\Models\Application;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
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

        $key = 'track-application|'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 15)) {
            throw ValidationException::withMessages([
                'application_number' => 'Trop de tentatives. Réessayez dans quelques minutes.',
            ]);
        }

        RateLimiter::hit($key, 300);

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
