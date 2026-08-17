<?php

namespace App\Livewire\Public\Admissions;

use App\Livewire\Concerns\ThrottlesLogins;
use App\Models\Candidate;
use App\Models\Formation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.public')]
class Start extends Component
{
    use ThrottlesLogins;

    public string $mode = 'register';

    #[Url]
    public ?string $formation = null;

    // Register fields
    public string $first_name = '';

    public string $last_name = '';

    public string $email = '';

    public string $phone = '';

    public string $password = '';

    // Login fields
    public string $login_email = '';

    public string $login_password = '';

    public function mount(): void
    {
        if (auth()->check() && auth()->user()->hasRole('candidat')) {
            $this->redirectToWizard();
        }
    }

    public function register(): void
    {
        $ipKey = 'candidate-register|'.request()->ip();

        if (RateLimiter::tooManyAttempts($ipKey, 10)) {
            $seconds = RateLimiter::availableIn($ipKey);

            throw ValidationException::withMessages([
                'email' => "Trop de tentatives. Réessayez dans {$seconds} secondes.",
            ]);
        }

        $this->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        RateLimiter::hit($ipKey, 3600);

        $user = User::create([
            'name' => "{$this->first_name} {$this->last_name}",
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => Hash::make($this->password),
        ]);
        $user->assignRole('candidat');

        Candidate::create([
            'user_id' => $user->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        Auth::login($user);
        session()->regenerate();

        $this->redirectToWizard();
    }

    public function login(): void
    {
        $this->validate([
            'login_email' => ['required', 'email'],
            'login_password' => ['required'],
        ]);

        $this->ensureIsNotRateLimited($this->login_email, 'login_email');

        if (! Auth::attempt(['email' => $this->login_email, 'password' => $this->login_password, 'is_active' => true])) {
            $this->hitRateLimiter($this->login_email);

            throw ValidationException::withMessages(['login_email' => 'Identifiants incorrects.']);
        }

        if (! auth()->user()->hasRole('candidat')) {
            Auth::logout();

            throw ValidationException::withMessages(['login_email' => "Ce compte n'est pas un compte candidat."]);
        }

        $this->clearRateLimiter($this->login_email);

        session()->regenerate();

        $this->redirectToWizard();
    }

    private function redirectToWizard(): void
    {
        $this->redirectRoute('candidate.applications.create', ['formation' => $this->formation], navigate: false);
    }

    public function render()
    {
        return view('livewire.public.admissions.start', [
            'preselectedFormation' => $this->formation
                ? Formation::where('slug', $this->formation)->where('is_published', true)->first()
                : null,
        ]);
    }
}
