<?php

namespace App\Livewire\Admin\Communication\Events;

use App\Models\Event;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class Form extends Component
{
    use WithFileUploads;

    public ?Event $event = null;

    public string $title = '';

    public string $description = '';

    public string $location = '';

    public string $start_at = '';

    public string $end_at = '';

    public string $status = 'brouillon';

    public $cover;

    public function mount(?Event $event = null): void
    {
        if ($event?->exists) {
            $this->event = $event;
            $this->fill($event->only(['title', 'description', 'location', 'status']));
            $this->start_at = $event->start_at?->format('Y-m-d\TH:i');
            $this->end_at = $event->end_at?->format('Y-m-d\TH:i');
        } else {
            $this->start_at = now()->addWeek()->format('Y-m-d\TH:i');
        }
    }

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:200'],
            'start_at' => ['required', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'status' => ['required', 'in:brouillon,publie,archive'],
            'cover' => ['nullable', 'image', 'max:4096'],
        ];
    }

    public function save(): void
    {
        $data = $this->validate();
        unset($data['cover']);

        if ($this->event) {
            $this->event->update($data);
        } else {
            $data['slug'] = Str::slug($this->title).'-'.Str::random(4);
            $this->event = Event::create($data);
        }

        if ($this->cover) {
            $this->event->clearMediaCollection('cover');
            $this->event->addMedia($this->cover->getRealPath())
                ->usingFileName($this->cover->getClientOriginalName())
                ->toMediaCollection('cover');
        }

        session()->flash('status', 'Événement enregistré.');

        $this->redirectRoute('admin.communication.events.index', navigate: false);
    }

    public function render()
    {
        return view('livewire.admin.communication.events.form');
    }
}
