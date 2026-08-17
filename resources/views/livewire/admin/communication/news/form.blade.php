<div>
    <div class="mb-6">
        <a href="{{ route('admin.communication.news.index') }}" class="text-sm text-ink/60 hover:text-primary">&larr; Retour aux actualités</a>
    </div>

    <form wire:submit="save" class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-black/5 bg-white p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-ink/80">Titre</label>
                    <input type="text" wire:model="title" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-ink/80">Catégorie</label>
                    <input type="text" wire:model="category" placeholder="Vie scolaire, Admissions, Résultats..."
                           class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-ink/80">Résumé</label>
                    <textarea wire:model="excerpt" rows="2" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-ink/80">Contenu</label>
                    <textarea wire:model="content" rows="10" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none"></textarea>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-black/5 bg-white p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-ink/80">Image de couverture</label>
                    <input type="file" wire:model="cover" class="mt-1.5 w-full text-sm">
                    @error('cover') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    @if ($news?->getFirstMediaUrl('cover'))
                        <img src="{{ $news->getFirstMediaUrl('cover') }}" class="mt-2 rounded-lg h-32 w-full object-cover">
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink/80">Statut</label>
                    <select wire:model="status" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        <option value="brouillon">Brouillon</option>
                        <option value="publie">Publié</option>
                        <option value="archive">Archivé</option>
                    </select>
                </div>

                <button type="submit" class="w-full rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                    Enregistrer
                </button>
            </div>
        </div>
    </form>
</div>
