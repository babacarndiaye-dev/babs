<div class="mx-auto max-w-7xl px-6 py-16">
    <div class="max-w-2xl">
        <h1 class="text-3xl font-semibold text-ink">Galerie</h1>
        <p class="mt-3 text-ink/60">La vie de l'établissement en images : ateliers, événements, remises de diplômes.</p>
    </div>

    <div class="mt-10 space-y-12">
        @forelse ($categories as $category)
            <div wire:key="cat-{{ $category->id }}">
                <h2 class="font-semibold text-ink mb-4">{{ $category->name }}</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($category->items as $item)
                        <div class="aspect-square rounded-xl overflow-hidden bg-surface">
                            @if ($item->type === 'image' && $item->getFirstMediaUrl('media'))
                                <img src="{{ $item->getFirstMediaUrl('media') }}" class="h-full w-full object-cover">
                            @elseif ($item->type === 'video' && $item->video_url)
                                <a href="{{ $item->video_url }}" target="_blank" class="flex h-full w-full items-center justify-center bg-ink/80 text-white text-sm font-medium">
                                    ▶ Voir la vidéo
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <p class="text-center text-ink/50 py-12">La galerie sera bientôt alimentée.</p>
        @endforelse
    </div>
</div>
