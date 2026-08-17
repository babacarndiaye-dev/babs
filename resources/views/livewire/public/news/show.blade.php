<div>
    <div class="bg-primary">
        <div class="mx-auto max-w-3xl px-6 py-16 text-white">
            @if ($news->category)
                <span class="text-xs font-semibold uppercase tracking-wide text-white/70">{{ $news->category }}</span>
            @endif
            <h1 class="mt-2 text-3xl sm:text-4xl font-semibold">{{ $news->title }}</h1>
            <p class="mt-4 text-sm text-white/60">{{ $news->published_at?->format('d/m/Y') }}</p>
        </div>
    </div>

    <div class="mx-auto max-w-3xl px-6 py-16">
        @if ($news->getFirstMediaUrl('cover'))
            <img src="{{ $news->getFirstMediaUrl('cover') }}" class="rounded-2xl w-full aspect-[16/9] object-cover mb-10">
        @endif

        <div class="prose max-w-none text-ink/80 whitespace-pre-line leading-relaxed">
            {{ $news->content }}
        </div>

        <a href="{{ route('news.index') }}" class="mt-10 inline-block text-sm font-medium text-primary hover:underline">
            &larr; Retour aux actualités
        </a>
    </div>
</div>
