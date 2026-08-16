<x-layouts.student :title="$title">
    <div class="rounded-2xl border border-dashed border-black/10 bg-white p-12 text-center">
        <p class="text-sm font-medium text-accent">{{ $phase }}</p>
        <h2 class="mt-2 text-xl font-semibold text-ink">{{ $title }}</h2>
        <p class="mt-2 text-sm text-ink/60 max-w-md mx-auto">
            Ce module sera implémenté à l'étape suivante de la feuille de route.
        </p>
    </div>
</x-layouts.student>
