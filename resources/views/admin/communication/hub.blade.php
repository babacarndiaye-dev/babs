<x-layouts.admin title="Actualités & Galerie">
    <div class="grid sm:grid-cols-3 gap-6">
        <a href="{{ route('admin.communication.news.index') }}" class="rounded-2xl border border-black/5 bg-white p-6 hover:shadow-md transition">
            <p class="text-2xl font-semibold text-primary">{{ $newsCount }}</p>
            <p class="mt-1 font-medium text-ink">Actualités</p>
            <p class="mt-1 text-sm text-ink/50">Gérer les articles et annonces</p>
        </a>
        <a href="{{ route('admin.communication.events.index') }}" class="rounded-2xl border border-black/5 bg-white p-6 hover:shadow-md transition">
            <p class="text-2xl font-semibold text-primary">{{ $eventsCount }}</p>
            <p class="mt-1 font-medium text-ink">Événements</p>
            <p class="mt-1 text-sm text-ink/50">Gérer le calendrier des événements</p>
        </a>
        <a href="{{ route('admin.communication.gallery.index') }}" class="rounded-2xl border border-black/5 bg-white p-6 hover:shadow-md transition">
            <p class="text-2xl font-semibold text-primary">{{ $galleryCount }}</p>
            <p class="mt-1 font-medium text-ink">Éléments de galerie</p>
            <p class="mt-1 text-sm text-ink/50">Gérer les photos et vidéos</p>
        </a>
    </div>
</x-layouts.admin>
