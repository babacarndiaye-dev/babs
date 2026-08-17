<?php

namespace App\Livewire\Student;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.student')]
class Profile extends Component
{
    use WithFileUploads;

    public string $phone = '';

    public string $email = '';

    public string $address = '';

    public string $guardian_name = '';

    public string $guardian_phone = '';

    public $photo;

    public function mount(): void
    {
        $student = auth()->user()->student;

        $this->phone = $student->phone ?? '';
        $this->email = $student->email ?? '';
        $this->address = $student->address ?? '';
        $this->guardian_name = $student->guardian_name ?? '';
        $this->guardian_phone = $student->guardian_phone ?? '';
    }

    public function save(): void
    {
        $data = $this->validate([
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string'],
            'guardian_name' => ['nullable', 'string', 'max:150'],
            'guardian_phone' => ['nullable', 'string', 'max:30'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);

        $student = auth()->user()->student;
        unset($data['photo']);
        $student->update($data);

        if ($this->photo) {
            $student->clearMediaCollection('photo');
            $student->addMedia($this->photo->getRealPath())
                ->usingFileName($this->photo->getClientOriginalName())
                ->toMediaCollection('photo');
        }

        session()->flash('status', 'Profil mis à jour.');
    }

    public function render()
    {
        return view('livewire.student.profile', [
            'student' => auth()->user()->student,
        ]);
    }
}
