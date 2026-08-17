<?php

namespace App\Livewire\Admin\Teachers;

use App\Models\Teacher;
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

    public function delete(Teacher $teacher): void
    {
        $teacher->delete();

        session()->flash('status', 'Enseignant supprimé.');
    }

    public function render()
    {
        return view('livewire.admin.teachers.index', [
            'teachers' => Teacher::when($this->search, fn ($q) => $q->where(fn ($w) => $w->where('first_name', 'like', "%{$this->search}%")
                ->orWhere('last_name', 'like', "%{$this->search}%")
                ->orWhere('matricule', 'like', "%{$this->search}%")))
                ->latest()
                ->paginate(15),
        ]);
    }
}
