<div class="mx-auto max-w-7xl px-6 py-16">
    <div class="max-w-2xl">
        <h1 class="text-3xl font-semibold text-ink">Catalogue des formations</h1>
        <p class="mt-3 text-ink/60">CAP, BEP, BT, BTS et formations modulaires — filtrez par type pour trouver la formation qui vous correspond.</p>
    </div>

    <div class="mt-8 flex flex-wrap gap-2">
        <button wire:click="$set('type', null)"
                class="rounded-full px-4 py-2 text-sm font-medium transition {{ ! $type ? 'bg-primary text-white' : 'bg-surface text-ink/70 hover:bg-black/5' }}">
            Toutes
        </button>
        @foreach ($types as $formationType)
            <button wire:click="$set('type', {{ $formationType->id }})"
                    class="rounded-full px-4 py-2 text-sm font-medium transition {{ $type === $formationType->id ? 'bg-primary text-white' : 'bg-surface text-ink/70 hover:bg-black/5' }}">
                {{ $formationType->name }}
            </button>
        @endforeach
    </div>

    <div class="mt-10 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($formations as $formation)
            <a href="{{ route('formations.show', $formation->slug) }}"
               class="group rounded-2xl border border-black/5 bg-white overflow-hidden shadow-sm hover:shadow-lg transition">
                <div class="aspect-[4/3] bg-surface"></div>
                <div class="p-5">
                    <span class="text-xs font-semibold text-accent uppercase tracking-wide">
                        {{ $formation->formationType->name }}
                    </span>
                    <h3 class="mt-1.5 font-semibold text-ink group-hover:text-primary transition">
                        {{ $formation->title }}
                    </h3>
                    <p class="mt-1 text-sm text-ink/60">{{ $formation->short_description }}</p>
                    <p class="mt-3 text-sm font-medium text-ink/80">{{ $formation->durationLabel() }}</p>
                </div>
            </a>
        @empty
            <p class="col-span-full text-center text-ink/50 py-12">Aucune formation ne correspond à ce filtre.</p>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $formations->links() }}
    </div>
</div>
