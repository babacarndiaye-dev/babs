<x-layouts.app :title="$title ?? null">
    <header class="border-b border-black/5 bg-white/80 backdrop-blur sticky top-0 z-40">
        <div class="mx-auto max-w-7xl px-6 py-4 flex items-center justify-between gap-6">
            <a href="{{ route('home') }}" class="flex items-center gap-3 font-semibold text-lg text-primary">
                @if ($logo = setting('identity.logo'))
                    <img src="{{ $logo }}" alt="{{ setting('identity.acronym') }}" class="h-9 w-auto">
                @else
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary text-white text-sm font-bold">
                        {{ setting('identity.acronym', 'EC') }}
                    </span>
                @endif
                <span class="hidden sm:inline">{{ setting('identity.acronym', setting('identity.name')) }}</span>
            </a>

            <nav class="hidden lg:flex items-center gap-8 text-sm font-medium text-ink/80">
                <a href="{{ route('home') }}" class="hover:text-primary transition">Accueil</a>
                <a href="{{ route('about') }}" class="hover:text-primary transition">L'Établissement</a>
                <a href="{{ route('formations.index') }}" class="hover:text-primary transition">Formations</a>
                <a href="{{ route('admissions') }}" class="hover:text-primary transition">Admissions</a>
                <a href="{{ route('news.index') }}" class="hover:text-primary transition">Actualités</a>
                <a href="{{ route('gallery') }}" class="hover:text-primary transition">Galerie</a>
                <a href="{{ route('contact') }}" class="hover:text-primary transition">Contact</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="hidden sm:inline text-sm font-medium text-ink/70 hover:text-primary transition">
                    Se connecter
                </a>
                <a href="{{ route('admissions.apply') }}"
                   class="rounded-full bg-accent px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:opacity-90 transition">
                    Candidater maintenant
                </a>
            </div>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer class="mt-24 border-t border-black/5 bg-primary text-white/90">
        <div class="mx-auto max-w-7xl px-6 py-14 grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <div class="flex items-center gap-3">
                    @if ($logo = setting('identity.logo'))
                        <img src="{{ $logo }}" alt="{{ setting('identity.acronym') }}" class="h-10 w-10 rounded-lg">
                    @endif
                    <p class="font-semibold text-white text-lg">{{ setting('identity.acronym') }}</p>
                </div>
                <p class="mt-3 text-sm text-white/70">{{ setting('identity.slogan') }}</p>
            </div>
            <div>
                <p class="font-semibold text-white">Contact</p>
                <ul class="mt-3 space-y-1 text-sm text-white/70">
                    <li>{{ setting('identity.address') }}</li>
                    <li>{{ setting('identity.phone') }}</li>
                    <li>{{ setting('identity.email') }}</li>
                </ul>
            </div>
            <div>
                <p class="font-semibold text-white">Liens</p>
                <ul class="mt-3 space-y-1 text-sm text-white/70">
                    <li><a href="{{ route('formations.index') }}" class="hover:text-white">Formations</a></li>
                    <li><a href="{{ route('admissions') }}" class="hover:text-white">Admissions</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white">Contact</a></li>
                </ul>
            </div>
            <div>
                <p class="font-semibold text-white">Espaces</p>
                <ul class="mt-3 space-y-1 text-sm text-white/70">
                    <li><a href="{{ route('login') }}" class="hover:text-white">Espace étudiant</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-white">Espace enseignant</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-white">Administration</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-white/10 py-4 text-center text-xs text-white/60">
            &copy; {{ now()->year }} {{ setting('identity.name') }}. Tous droits réservés.
        </div>
    </footer>
</x-layouts.app>
