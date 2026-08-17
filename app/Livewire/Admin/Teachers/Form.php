<?php

namespace App\Livewire\Admin\Teachers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class Form extends Component
{
    use WithFileUploads;

    public ?Teacher $teacher = null;

    public string $matricule = '';

    public string $first_name = '';

    public string $last_name = '';

    public ?string $gender = null;

    public ?string $date_of_birth = null;

    public string $phone = '';

    public string $email = '';

    public string $address = '';

    public string $specialty = '';

    public string $qualifications = '';

    public ?string $hire_date = null;

    public bool $is_active = true;

    public $photo;

    public bool $createAccount = false;

    public function mount(?Teacher $teacher = null): void
    {
        if ($teacher?->exists) {
            $this->teacher = $teacher;
            $this->fill($teacher->only([
                'matricule', 'first_name', 'last_name', 'gender', 'phone', 'email',
                'address', 'specialty', 'qualifications', 'is_active',
            ]));
            $this->date_of_birth = $teacher->date_of_birth?->format('Y-m-d');
            $this->hire_date = $teacher->hire_date?->format('Y-m-d');
        } else {
            $this->matricule = 'ENS-'.str_pad((string) (Teacher::max('id') + 1), 4, '0', STR_PAD_LEFT);
            $this->hire_date = now()->format('Y-m-d');
        }
    }

    protected function rules(): array
    {
        return [
            'matricule' => ['required', 'string', 'max:50', 'unique:teachers,matricule,'.$this->teacher?->id],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'gender' => ['nullable', 'in:M,F'],
            'date_of_birth' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string'],
            'specialty' => ['nullable', 'string', 'max:150'],
            'qualifications' => ['nullable', 'string'],
            'hire_date' => ['nullable', 'date'],
            'is_active' => ['boolean'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ];
    }

    public function save(): void
    {
        $data = $this->validate();
        unset($data['photo']);

        $statusMessage = 'Enseignant enregistré.';

        if ($this->teacher) {
            $this->teacher->update($data);
        } else {
            $this->teacher = Teacher::create($data);

            if ($this->createAccount && $this->email) {
                $password = Str::password(12);
                $user = User::create([
                    'name' => "{$this->first_name} {$this->last_name}",
                    'email' => $this->email,
                    'password' => Hash::make($password),
                ]);
                $user->assignRole('enseignant');
                $this->teacher->update(['user_id' => $user->id]);

                $statusMessage = "Enseignant créé. Compte de connexion : {$this->email} / mot de passe temporaire : {$password}";
            }
        }

        if ($this->photo) {
            $this->teacher->clearMediaCollection('photo');
            $this->teacher->addMedia($this->photo->getRealPath())
                ->usingFileName($this->photo->getClientOriginalName())
                ->toMediaCollection('photo');
        }

        session()->flash('status', $statusMessage);

        $this->redirectRoute('admin.teachers.index', navigate: false);
    }

    public function render()
    {
        return view('livewire.admin.teachers.form');
    }
}
