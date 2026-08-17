@props(['title' => null, 'nav' => [], 'roleLabel' => null])

<x-layouts.app :title="$title">
    <div class="flex min-h-screen bg-surface">
        <aside class="hidden lg:flex w-64 flex-col border-r border-black/5 bg-white">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 px-6 py-5 font-semibold text-ink border-b border-black/5">
                @if ($logo = setting('identity.logo'))
                    <img src="{{ $logo }}" alt="{{ setting('identity.acronym') }}" class="h-9 w-9 rounded-xl shadow-sm object-cover">
                @else
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary text-white text-sm font-bold shadow-sm">
                        {{ setting('identity.acronym', 'EC') }}
                    </span>
                @endif
                <span class="leading-tight">
                    <span class="block text-sm font-bold">{{ setting('identity.acronym') }}</span>
                    <span class="block text-[11px] font-normal text-ink/50">{{ setting('identity.name') }}</span>
                </span>
            </a>

            <p class="px-6 pt-5 pb-1 text-[11px] font-semibold uppercase tracking-wide text-ink/40">Navigation</p>

            <nav class="flex-1 overflow-y-auto px-3 py-2 space-y-1">
                @foreach ($nav as $item)
                    <a href="{{ $item['route'] ? route($item['route']) : '#' }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                              {{ request()->routeIs($item['route'].'*') ? 'bg-primary text-white shadow-sm' : 'text-ink/70 hover:bg-surface hover:text-ink' }}">
                        <x-nav-icon :name="$item['icon'] ?? 'home'" class="h-5 w-5 shrink-0" />
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <form method="POST" action="{{ route('logout') }}" class="border-t border-black/5 px-3 py-4">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm font-medium text-ink/70 hover:bg-surface hover:text-ink transition">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                        <path d="M15 17.5V19a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v1.5" />
                        <path d="M9 12h11.5M17 8.5 21 12l-4 3.5" />
                    </svg>
                    Se déconnecter
                </button>
            </form>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="flex items-center justify-between border-b border-black/5 bg-white px-6 py-3.5">
                <div class="flex items-center gap-2 text-sm text-ink/50">
                    <a href="{{ route('home') }}" class="flex h-6 w-6 items-center justify-center rounded-md hover:bg-surface hover:text-ink transition" title="Accueil">
                        <x-nav-icon name="home" class="h-4 w-4" />
                    </a>
                    <span>/</span>
                    <span class="font-medium text-ink">{{ $title }}</span>
                </div>

                <div class="flex items-center gap-4">
                    <livewire:shared.notification-bell />

                    <div class="flex items-center gap-2.5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-primary/10 text-primary font-semibold text-sm">
                            {{ Illuminate\Support\Str::of(auth()->user()->name ?? '?')->substr(0, 1) }}
                        </span>
                        <span class="hidden sm:flex flex-col leading-tight">
                            <span class="text-sm font-medium text-ink">{{ auth()->user()->name ?? '' }}</span>
                            @if ($roleLabel)
                                <span class="inline-block w-fit rounded-full bg-primary px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-white">
                                    {{ $roleLabel }}
                                </span>
                            @endif
                        </span>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</x-layouts.app>
