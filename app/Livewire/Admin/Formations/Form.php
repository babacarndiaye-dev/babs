<?php

namespace App\Livewire\Admin\Formations;

use App\Models\Domain;
use App\Models\Formation;
use App\Models\FormationType;
use App\Models\Subject;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class Form extends Component
{
    use WithFileUploads;

    public ?Formation $formation = null;

    public string $title = '';

    public ?int $formation_type_id = null;

    public ?int $domain_id = null;

    public string $short_description = '';

    public string $description = '';

    public string $objectives = '';

    public string $curriculum = '';

    public string $skills = '';

    public string $career_opportunities = '';

    public string $admission_requirements = '';

    public ?int $duration_value = null;

    public string $duration_unit = 'mois';

    public ?string $level_label = null;

    public ?float $tuition_fee = null;

    public ?int $seats_available = null;

    public ?string $start_date = null;

    public bool $is_published = true;

    /** @var array<int> */
    public array $subject_ids = [];

    public $cover;

    public function mount(?Formation $formation = null): void
    {
        if ($formation?->exists) {
            $this->formation = $formation;
            $this->fill($formation->only([
                'title', 'formation_type_id', 'domain_id', 'short_description', 'description',
                'objectives', 'curriculum', 'skills', 'career_opportunities', 'admission_requirements',
                'duration_value', 'duration_unit', 'level_label', 'tuition_fee', 'seats_available',
                'is_published',
            ]));
            $this->start_date = $formation->start_date?->format('Y-m-d');
            $this->subject_ids = $formation->subjects()->pluck('subjects.id')->all();
        }
    }

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'formation_type_id' => ['required', 'exists:formation_types,id'],
            'domain_id' => ['nullable', 'exists:domains,id'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'objectives' => ['nullable', 'string'],
            'curriculum' => ['nullable', 'string'],
            'skills' => ['nullable', 'string'],
            'career_opportunities' => ['nullable', 'string'],
            'admission_requirements' => ['nullable', 'string'],
            'duration_value' => ['nullable', 'integer', 'min:1'],
            'duration_unit' => ['nullable', 'in:mois,ans'],
            'level_label' => ['nullable', 'string', 'max:100'],
            'tuition_fee' => ['nullable', 'numeric', 'min:0'],
            'seats_available' => ['nullable', 'integer', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'is_published' => ['boolean'],
            'subject_ids' => ['array'],
            'cover' => ['nullable', 'image', 'max:4096'],
        ];
    }

    public function save(): void
    {
        $data = $this->validate();
        unset($data['subject_ids'], $data['cover']);

        $data['slug'] = Str::slug($this->title);

        if ($this->formation) {
            $this->formation->update($data);
        } else {
            $this->formation = Formation::create($data);
        }

        $this->formation->subjects()->sync($this->subject_ids);

        if ($this->cover) {
            $this->formation->clearMediaCollection('cover');
            $this->formation->addMedia($this->cover->getRealPath())
                ->usingFileName($this->cover->getClientOriginalName())
                ->toMediaCollection('cover');
        }

        session()->flash('status', 'Formation enregistrée.');

        $this->redirectRoute('admin.formations.index', navigate: false);
    }

    public function render()
    {
        return view('livewire.admin.formations.form', [
            'formationTypes' => FormationType::orderBy('name')->get(),
            'domains' => Domain::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
        ]);
    }
}
