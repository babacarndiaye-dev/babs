<div class="mx-auto max-w-7xl px-6 py-16">
    <div class="max-w-2xl">
        <h1 class="text-3xl font-semibold text-ink">Actualités</h1>
        <p class="mt-3 text-ink/60">Suivez la vie de l'établissement : annonces, résultats, campagnes d'admission.</p>
    </div>

    <div class="mt-10 grid lg:grid-cols-3 gap-10">
        <div class="lg:col-span-2 grid sm:grid-cols-2 gap-6">
            @forelse ($newsItems as $news)
                <a href="{{ route('news.show', $news->slug) }}" wire:key="news-{{ $news->id }}"
                   class="group rounded-2xl border border-black/5 bg-white overflow-hidden shadow-sm hover:shadow-lg transition">
                    <div class="aspect-[4/3] bg-surface">
                        @if ($news->getFirstMediaUrl('cover'))
                            <img src="{{ $news->getFirstMediaUrl('cover') }}" class="h-full w-full object-cover">
                        @endif
                    </div>
                    <div class="p-5">
                        @if ($news->category)
                            <span class="text-xs font-semibold text-accent uppercase tracking-wide">{{ $news->category }}</span>
                        @endif
                        <h3 class="mt-1.5 font-semibold text-ink group-hover:text-primary transition">{{ $news->title }}</h3>
                        <p class="mt-1 text-sm text-ink/60">{{ Illuminate\Support\Str::limit($news->excerpt ?? strip_tags($news->content), 100) }}</p>
                        <p class="mt-3 text-xs text-ink/40">{{ $news->published_at?->format('d/m/Y') }}</p>
                    </div>
                </a>
            @empty
                <p class="col-span-full text-center text-ink/50 py-12">Aucune actualité publiée pour le moment.</p>
            @endforelse

            <div class="col-span-full">{{ $newsItems->links() }}</div>
        </div>

        <div>
            <h2 class="font-semibold text-ink mb-4">Événements à venir</h2>
            <div class="space-y-3">
                @forelse ($upcomingEvents as $event)
                    <div wire:key="event-{{ $event->id }}" class="rounded-xl border border-black/5 bg-white p-4">
                        <p class="text-xs font-semibold text-primary">{{ $event->start_at->format('d/m/Y') }}</p>
                        <p class="mt-1 font-medium text-ink text-sm">{{ $event->title }}</p>
                        @if ($event->location)
                            <p class="mt-1 text-xs text-ink/50">{{ $event->location }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-ink/50">Aucun événement à venir.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
