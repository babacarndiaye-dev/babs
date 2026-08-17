<div>
    <div class="mb-6">
        <a href="{{ route('admin.communication.index') }}" class="text-sm text-ink/60 hover:text-primary">&larr; Retour à la communication</a>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-lg bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('status') }}</div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6 mb-6">
        <div class="rounded-2xl border border-black/5 bg-white p-6">
            <h3 class="font-semibold text-ink mb-4">Catégories</h3>
            <div class="space-y-2 mb-4">
                @forelse ($categories as $category)
                    <div wire:key="cat-{{ $category->id }}" class="flex items-center justify-between rounded-lg border border-black/5 px-3 py-2 text-sm">
                        <span class="text-ink">{{ $category->name }} <span class="text-ink/40">({{ $category->items_count }})</span></span>
                        <button wire:click="deleteCategory({{ $category->id }})" class="text-red-600 hover:underline text-xs font-medium">Retirer</button>
                    </div>
                @empty
                    <p class="text-sm text-ink/50">Aucune catégorie.</p>
                @endforelse
            </div>
            <form wire:submit="addCategory" class="flex gap-2">
                <input type="text" wire:model="categoryName" placeholder="Nouvelle catégorie"
                       class="flex-1 rounded-lg border border-black/10 px-3 py-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                <button type="submit" class="rounded-lg bg-primary px-3 py-2 text-sm font-semibold text-white hover:opacity-90 transition">+</button>
            </form>
            @error('categoryName') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="lg:col-span-2 rounded-2xl border border-black/5 bg-white p-6">
            <h3 class="font-semibold text-ink mb-4">Ajouter un élément</h3>
            <form wire:submit="addItem" class="grid sm:grid-cols-2 gap-3">
                <select wire:model="gallery_category_id" class="rounded-lg border border-black/10 px-3 py-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    <option value="">Catégorie</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <input type="text" wire:model="itemTitle" placeholder="Titre (optionnel)"
                       class="rounded-lg border border-black/10 px-3 py-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                <select wire:model.live="type" class="rounded-lg border border-black/10 px-3 py-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    <option value="image">Image</option>
                    <option value="video">Vidéo (URL)</option>
                </select>

                @if ($type === 'image')
                    <div>
                        <input type="file" wire:model="file" class="w-full text-sm">
                        @error('file') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                @else
                    <div>
                        <input type="text" wire:model="video_url" placeholder="https://youtube.com/..."
                               class="w-full rounded-lg border border-black/10 px-3 py-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        @error('video_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                @endif

                <button type="submit" class="sm:col-span-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                    Ajouter à la galerie
                </button>
            </form>
        </div>
    </div>

    <div class="grid sm:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse ($items as $item)
            <div wire:key="item-{{ $item->id }}" class="rounded-2xl border border-black/5 bg-white overflow-hidden">
                <div class="aspect-square bg-surface flex items-center justify-center">
                    @if ($item->type === 'image' && $item->getFirstMediaUrl('media'))
                        <img src="{{ $item->getFirstMediaUrl('media') }}" class="h-full w-full object-cover">
                    @else
                        <span class="text-ink/30 text-xs">Vidéo</span>
                    @endif
                </div>
                <div class="p-3">
                    <p class="text-xs font-medium text-ink truncate">{{ $item->title ?? $item->category?->name ?? '—' }}</p>
                    <button wire:click="deleteItem({{ $item->id }})" class="mt-1 text-xs text-red-600 hover:underline">Supprimer</button>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-black/10 bg-white p-12 text-center text-ink/50">
                Aucun élément dans la galerie.
            </div>
        @endforelse
    </div>
</div>
