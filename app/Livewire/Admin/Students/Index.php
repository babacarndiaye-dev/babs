<?php

namespace App\Livewire\Admin\Students;

use App\Models\SchoolClass;
use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $status = '';

    #[Url]
    public ?int $classId = null;

    public function delete(Student $student): void
    {
        $student->delete();

        session()->flash('status', 'Étudiant supprimé.');
    }

    public function render()
    {
        return view('livewire.admin.students.index', [
            'students' => Student::with('currentClass.formation')
                ->when($this->search, fn ($q) => $q->where(fn ($w) => $w->where('first_name', 'like', "%{$this->search}%")
                    ->orWhere('last_name', 'like', "%{$this->search}%")
                    ->orWhere('matricule', 'like', "%{$this->search}%")))
                ->when($this->status, fn ($q) => $q->where('status', $this->status))
                ->when($this->classId, fn ($q) => $q->where('current_class_id', $this->classId))
                ->latest()
                ->paginate(15),
            'classes' => SchoolClass::orderBy('name')->get(),
        ]);
    }
}
