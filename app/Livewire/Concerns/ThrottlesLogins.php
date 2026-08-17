<?php

namespace App\Livewire\Concerns;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait ThrottlesLogins
{
    protected function throttleKey(string $email): string
    {
        return Str::transliterate(Str::lower($email).'|'.request()->ip());
    }

    /**
     * @throws ValidationException
     */
    protected function ensureIsNotRateLimited(string $email, string $field = 'email'): void
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey($email), 5)) {
            $seconds = RateLimiter::availableIn($this->throttleKey($email));

            throw ValidationException::withMessages([
                $field => "Trop de tentatives. Réessayez dans {$seconds} secondes.",
            ]);
        }
    }

    protected function hitRateLimiter(string $email): void
    {
        RateLimiter::hit($this->throttleKey($email), 60);
    }

    protected function clearRateLimiter(string $email): void
    {
        RateLimiter::clear($this->throttleKey($email));
    }
}
