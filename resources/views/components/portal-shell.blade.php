@props(['title' => null, 'nav' => []])

<x-layouts.app :title="$title">
    <div class="flex min-h-screen">
        <aside class="hidden lg:flex w-64 flex-col border-r border-black/5 bg-white">
            <a href="{{ route('home') }}" class="flex items-center gap-2 px-6 py-5 font-semibold text-primary border-b border-black/5">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-white text-xs font-bold">
                    {{ setting('identity.acronym', 'EC') }}
                </span>
                {{ setting('identity.acronym') }}
            </a>

            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
                @foreach ($nav as $item)
                    <a href="{{ $item['route'] ? route($item['route']) : '#' }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                              {{ request()->routeIs($item['route'].'*') ? 'bg-primary/10 text-primary' : 'text-ink/70 hover:bg-surface hover:text-ink' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <form method="POST" action="{{ route('logout') }}" class="border-t border-black/5 px-3 py-4">
                @csrf
                <button type="submit" class="w-full rounded-lg px-3 py-2.5 text-left text-sm font-medium text-ink/70 hover:bg-surface hover:text-ink transition">
                    Se déconnecter
                </button>
            </form>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="flex items-center justify-between border-b border-black/5 bg-white px-6 py-4">
                <h1 class="text-lg font-semibold text-ink">{{ $title }}</h1>
                <div class="flex items-center gap-3 text-sm text-ink/70">
                    <span>{{ auth()->user()->name ?? '' }}</span>
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-primary/10 text-primary font-semibold">
                        {{ Illuminate\Support\Str::of(auth()->user()->name ?? '?')->substr(0, 1) }}
                    </span>
                </div>
            </header>

            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</x-layouts.app>
