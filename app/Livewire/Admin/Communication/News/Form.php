<?php

namespace App\Livewire\Admin\Communication\News;

use App\Models\News;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class Form extends Component
{
    use WithFileUploads;

    public ?News $news = null;

    public string $title = '';

    public string $category = '';

    public string $excerpt = '';

    public string $content = '';

    public string $status = 'brouillon';

    public $cover;

    public function mount(?News $news = null): void
    {
        if ($news?->exists) {
            $this->news = $news;
            $this->fill($news->only(['title', 'category', 'excerpt', 'content', 'status']));
        }
    }

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'category' => ['nullable', 'string', 'max:100'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'status' => ['required', 'in:brouillon,publie,archive'],
            'cover' => ['nullable', 'image', 'max:4096'],
        ];
    }

    public function save(): void
    {
        $data = $this->validate();
        unset($data['cover']);

        $data['slug'] = Str::slug($this->title).'-'.Str::random(4);

        if ($this->news) {
            unset($data['slug']);
            $this->news->update($data);
        } else {
            $data['author_id'] = Auth::id();
            $data['published_at'] = $this->status === 'publie' ? now() : null;
            $this->news = News::create($data);
        }

        if ($this->status === 'publie' && ! $this->news->published_at) {
            $this->news->update(['published_at' => now()]);
        }

        if ($this->cover) {
            $this->news->clearMediaCollection('cover');
            $this->news->addMedia($this->cover->getRealPath())
                ->usingFileName($this->cover->getClientOriginalName())
                ->toMediaCollection('cover');
        }

        session()->flash('status', 'Actualité enregistrée.');

        $this->redirectRoute('admin.communication.news.index', navigate: false);
    }

    public function render()
    {
        return view('livewire.admin.communication.news.form');
    }
}
