<?php

namespace App\Livewire\Admin\Subjects;

use App\Models\Subject;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    public ?Subject $editing = null;

    public string $name = '';

    public string $code = '';

    public string $description = '';

    public function edit(Subject $subject): void
    {
        $this->editing = $subject;
        $this->name = $subject->name;
        $this->code = $subject->code;
        $this->description = $subject->description ?? '';
    }

    public function cancel(): void
    {
        $this->reset(['editing', 'name', 'code', 'description']);
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:150'],
            'code' => ['required', 'string', 'max:50', 'unique:subjects,code,'.$this->editing?->id],
            'description' => ['nullable', 'string'],
        ]);

        if ($this->editing) {
            $this->editing->update($data);
        } else {
            Subject::create($data);
        }

        $this->cancel();
        session()->flash('status', 'Matière enregistrée.');
    }

    public function delete(Subject $subject): void
    {
        $subject->delete();

        session()->flash('status', 'Matière supprimée.');
    }

    public function render()
    {
        return view('livewire.admin.subjects.index', [
            'subjects' => Subject::orderBy('name')->get(),
        ]);
    }
}
