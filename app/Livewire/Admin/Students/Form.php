<?php

namespace App\Livewire\Admin\Students;

use App\Models\SchoolClass;
use App\Models\Student;
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

    public ?Student $student = null;

    public string $matricule = '';

    public string $first_name = '';

    public string $last_name = '';

    public ?string $gender = null;

    public ?string $date_of_birth = null;

    public ?string $place_of_birth = null;

    public string $phone = '';

    public string $email = '';

    public string $address = '';

    public string $guardian_name = '';

    public string $guardian_phone = '';

    public ?int $current_class_id = null;

    public string $status = 'actif';

    public ?string $enrolled_at = null;

    public $photo;

    public bool $createAccount = false;

    public function mount(?Student $student = null): void
    {
        if ($student?->exists) {
            $this->student = $student;
            $this->fill($student->only([
                'matricule', 'first_name', 'last_name', 'gender', 'place_of_birth',
                'phone', 'email', 'address', 'guardian_name', 'guardian_phone',
                'current_class_id', 'status',
            ]));
            $this->date_of_birth = $student->date_of_birth?->format('Y-m-d');
            $this->enrolled_at = $student->enrolled_at?->format('Y-m-d');
        } else {
            $this->matricule = $this->generateMatricule();
            $this->enrolled_at = now()->format('Y-m-d');
        }
    }

    private function generateMatricule(): string
    {
        $next = Student::max('id') + 1;

        return 'ETU-'.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    protected function rules(): array
    {
        return [
            'matricule' => ['required', 'string', 'max:50', 'unique:students,matricule,'.$this->student?->id],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'gender' => ['nullable', 'in:M,F'],
            'date_of_birth' => ['nullable', 'date'],
            'place_of_birth' => ['nullable', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string'],
            'guardian_name' => ['nullable', 'string', 'max:150'],
            'guardian_phone' => ['nullable', 'string', 'max:30'],
            'current_class_id' => ['nullable', 'exists:classes,id'],
            'status' => ['required', 'in:actif,suspendu,diplome,abandonne'],
            'enrolled_at' => ['nullable', 'date'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ];
    }

    public function save(): void
    {
        $data = $this->validate();
        unset($data['photo']);

        $statusMessage = 'Étudiant enregistré.';

        if ($this->student) {
            $this->student->update($data);
        } else {
            $this->student = Student::create($data);

            if ($this->createAccount && $this->email) {
                $password = Str::password(12);
                $user = User::create([
                    'name' => "{$this->first_name} {$this->last_name}",
                    'email' => $this->email,
                    'password' => Hash::make($password),
                ]);
                $user->assignRole('etudiant');
                $this->student->update(['user_id' => $user->id]);

                $statusMessage = "Étudiant créé. Compte de connexion : {$this->email} / mot de passe temporaire : {$password}";
            }

            if ($this->current_class_id) {
                $this->student->classes()->syncWithoutDetaching([
                    $this->current_class_id => [
                        'academic_year_id' => SchoolClass::find($this->current_class_id)?->academic_year_id,
                        'status' => 'inscrit',
                        'enrolled_at' => $this->enrolled_at,
                    ],
                ]);
            }
        }

        if ($this->photo) {
            $this->student->clearMediaCollection('photo');
            $this->student->addMedia($this->photo->getRealPath())
                ->usingFileName($this->photo->getClientOriginalName())
                ->toMediaCollection('photo');
        }

        session()->flash('status', $statusMessage);

        $this->redirectRoute('admin.students.index', navigate: false);
    }

    public function render()
    {
        return view('livewire.admin.students.form', [
            'classes' => SchoolClass::with('formation')->orderBy('name')->get(),
        ]);
    }
}
