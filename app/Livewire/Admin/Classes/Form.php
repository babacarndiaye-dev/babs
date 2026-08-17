<?php

namespace App\Livewire\Admin\Classes;

use App\Models\AcademicYear;
use App\Models\Formation;
use App\Models\Level;
use App\Models\Room;
use App\Models\SchoolClass;
use App\Models\Specialty;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class Form extends Component
{
    public ?SchoolClass $class = null;

    public string $name = '';

    public ?int $formation_id = null;

    public ?int $specialty_id = null;

    public ?int $level_id = null;

    public ?int $academic_year_id = null;

    public ?int $room_id = null;

    public ?int $capacity = null;

    public function mount(?SchoolClass $class = null): void
    {
        if ($class?->exists) {
            $this->class = $class;
            $this->fill($class->only(['name', 'formation_id', 'specialty_id', 'level_id', 'academic_year_id', 'room_id', 'capacity']));
        } else {
            $this->academic_year_id = AcademicYear::current()?->id;
        }
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'formation_id' => ['required', 'exists:formations,id'],
            'specialty_id' => ['nullable', 'exists:specialties,id'],
            'level_id' => ['nullable', 'exists:levels,id'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'room_id' => ['nullable', 'exists:rooms,id'],
            'capacity' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->class) {
            $this->class->update($data);
        } else {
            $this->class = SchoolClass::create($data);
        }

        session()->flash('status', 'Classe enregistrée.');

        $this->redirectRoute('admin.classes.index', navigate: false);
    }

    public function render()
    {
        return view('livewire.admin.classes.form', [
            'formations' => Formation::orderBy('title')->get(),
            'specialties' => $this->formation_id ? Specialty::where('formation_id', $this->formation_id)->get() : collect(),
            'levels' => Level::orderBy('order')->get(),
            'academicYears' => AcademicYear::orderByDesc('start_date')->get(),
            'rooms' => Room::orderBy('name')->get(),
        ]);
    }
}
