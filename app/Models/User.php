<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'phone', 'is_active', 'locale'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }

    public function candidate(): HasOne
    {
        return $this->hasOne(Candidate::class);
    }

    public function homeRoute(): string
    {
        return match (true) {
            $this->hasAnyRole(['super-admin', 'directeur', 'administrateur', 'responsable-academique', 'scolarite', 'comptable']) => 'admin.dashboard',
            $this->hasRole('enseignant') => 'teacher.dashboard',
            $this->hasRole('etudiant') => 'student.dashboard',
            $this->hasRole('candidat') => 'candidate.dashboard',
            default => 'home',
        };
    }

    public function routeNotificationForWhatsApp(): ?string
    {
        return $this->phone;
    }

    public function routeNotificationForSms(): ?string
    {
        return $this->phone;
    }
}
