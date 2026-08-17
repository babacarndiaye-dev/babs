<?php

namespace App\Livewire\Auth;

use App\Livewire\Concerns\ThrottlesLogins;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public')]
class Login extends Component
{
    use ThrottlesLogins;

    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $this->ensureIsNotRateLimited($this->email);

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password, 'is_active' => true], $this->remember)) {
            $this->hitRateLimiter($this->email);

            throw ValidationException::withMessages([
                'email' => 'Identifiants incorrects ou compte désactivé.',
            ]);
        }

        $this->clearRateLimiter($this->email);

        session()->regenerate();

        $this->redirectRoute(auth()->user()->homeRoute(), navigate: false);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
