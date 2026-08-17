<?php

namespace App\Livewire\Admin\Communication\Gallery;

use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    use WithFileUploads;

    // New category form
    public string $categoryName = '';

    // New item form
    public ?int $gallery_category_id = null;

    public string $itemTitle = '';

    public string $type = 'image';

    public $file;

    public string $video_url = '';

    protected function rules(): array
    {
        return [
            'gallery_category_id' => ['nullable', 'exists:gallery_categories,id'],
            'itemTitle' => ['nullable', 'string', 'max:150'],
            'type' => ['required', 'in:image,video'],
            'file' => [$this->type === 'image' ? 'required' : 'nullable', 'image', 'max:8192'],
            'video_url' => [$this->type === 'video' ? 'required' : 'nullable', 'url'],
        ];
    }

    public function addCategory(): void
    {
        $this->validate(['categoryName' => ['required', 'string', 'max:150']]);

        GalleryCategory::create([
            'name' => $this->categoryName,
            'slug' => Str::slug($this->categoryName).'-'.Str::random(4),
        ]);

        $this->reset('categoryName');
        session()->flash('status', 'Catégorie ajoutée.');
    }

    public function deleteCategory(GalleryCategory $category): void
    {
        $category->delete();

        session()->flash('status', 'Catégorie supprimée.');
    }

    public function addItem(): void
    {
        $this->validate();

        $item = GalleryItem::create([
            'gallery_category_id' => $this->gallery_category_id,
            'title' => $this->itemTitle ?: null,
            'type' => $this->type,
            'video_url' => $this->type === 'video' ? $this->video_url : null,
        ]);

        if ($this->type === 'image' && $this->file) {
            $item->addMedia($this->file->getRealPath())
                ->usingFileName($this->file->getClientOriginalName())
                ->toMediaCollection('media');
        }

        $this->reset(['itemTitle', 'file', 'video_url']);
        session()->flash('status', 'Élément ajouté à la galerie.');
    }

    public function deleteItem(GalleryItem $item): void
    {
        $item->delete();

        session()->flash('status', 'Élément supprimé.');
    }

    public function render()
    {
        return view('livewire.admin.communication.gallery.index', [
            'categories' => GalleryCategory::withCount('items')->orderBy('name')->get(),
            'items' => GalleryItem::with('category')->latest()->get(),
        ]);
    }
}
