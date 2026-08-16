<x-layouts.public :title="$title">
    <div class="mx-auto max-w-3xl px-6 py-24 text-center">
        <p class="text-sm font-medium text-accent">{{ $phase }}</p>
        <h1 class="mt-2 text-3xl font-semibold text-ink">{{ $title }}</h1>
        <p class="mt-4 text-ink/60">
            Cette page sera enrichie à l'étape suivante de la feuille de route.
        </p>
        <a href="{{ route('home') }}" class="mt-8 inline-block rounded-full bg-primary px-6 py-3 text-sm font-semibold text-white hover:opacity-90 transition">
            Retour à l'accueil
        </a>
    </div>
</x-layouts.public>
