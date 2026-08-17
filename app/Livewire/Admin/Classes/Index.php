<?php

namespace App\Livewire\Admin\Classes;

use App\Models\SchoolClass;
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

    public function delete(SchoolClass $class): void
    {
        $class->delete();

        session()->flash('status', 'Classe supprimée.');
    }

    public function render()
    {
        return view('livewire.admin.classes.index', [
            'classes' => SchoolClass::with(['formation', 'academicYear'])
                ->withCount('students')
                ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
                ->orderBy('name')
                ->paginate(15),
        ]);
    }
}
