<div>
    {{-- HERO — auto-advancing slider: a welcome slide + one per featured formation --}}
    <section
        class="relative overflow-hidden bg-primary"
        x-data="{
            active: 0,
            total: {{ 1 + $this->heroFormations->count() }},
            timer: null,
            start() { this.timer = setInterval(() => { this.active = (this.active + 1) % this.total }, 6000) },
            stop() { clearInterval(this.timer) },
            go(i) { this.active = i; this.stop(); this.start() },
        }"
        x-init="start()"
        @mouseenter="stop()" @mouseleave="start()"
    >
        <div class="absolute inset-0 bg-gradient-to-br from-primary via-primary-dark to-black/40"></div>
        <div class="absolute -top-24 -right-24 h-96 w-96 rounded-full bg-accent/20 blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 h-96 w-96 rounded-full bg-white/10 blur-3xl"></div>

        <div class="relative mx-auto max-w-7xl px-6 pt-24 sm:pt-32 pb-16 min-h-[26rem]">
            {{-- Slide 0: general welcome --}}
            <div x-show="active === 0" x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                 class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="inline-block rounded-full bg-white/10 px-4 py-1.5 text-xs font-medium text-white/90">
                        {{ setting('identity.acronym') }} &mdash; Formation professionnelle au Sénégal
                    </span>
                    <h1 class="mt-6 text-4xl sm:text-5xl font-semibold leading-tight text-white">
                        Construisez votre avenir professionnel.
                    </h1>
                    <p class="mt-5 text-lg text-white/80 max-w-xl">
                        Des formations professionnelles adaptées aux métiers d'aujourd'hui et aux opportunités de demain.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="{{ route('formations.index') }}"
                           class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-primary hover:bg-white/90 transition">
                            Découvrir nos formations
                        </a>
                        <a href="{{ route('admissions.apply') }}"
                           class="rounded-full bg-accent px-6 py-3 text-sm font-semibold text-white hover:opacity-90 transition">
                            Candidater maintenant
                        </a>
                    </div>
                </div>
                <div class="relative hidden lg:flex aspect-[4/3] items-center justify-center rounded-3xl bg-white/10 backdrop-blur border border-white/10 shadow-2xl p-12">
                    @if ($logo = setting('identity.logo'))
                        <img src="{{ $logo }}" alt="{{ setting('identity.acronym') }}" class="max-h-full max-w-full rounded-2xl shadow-xl">
                    @else
                        <x-nav-icon name="academic-cap" class="h-24 w-24 text-white/70" />
                    @endif
                </div>
            </div>

            {{-- Slides 1..N: one per featured formation --}}
            @foreach ($this->heroFormations as $i => $formation)
                <div x-show="active === {{ $i + 1 }}" x-cloak
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                     class="grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <span class="inline-block rounded-full bg-accent/90 px-4 py-1.5 text-xs font-semibold text-white">
                            {{ $formation->formationType->name }} &middot; {{ $formation->durationLabel() }}
                        </span>
                        <h1 class="mt-6 text-4xl sm:text-5xl font-semibold leading-tight text-white">
                            {{ $formation->title }}
                        </h1>
                        <p class="mt-5 text-lg text-white/80 max-w-xl">
                            {{ $formation->short_description ?? "Une formation professionnelle pensée pour l'insertion sur le marché du travail." }}
                        </p>
                        <div class="mt-8 flex flex-wrap gap-4">
                            <a href="{{ route('formations.show', $formation->slug) }}"
                               class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-primary hover:bg-white/90 transition">
                                Découvrir cette formation
                            </a>
                            <a href="{{ route('admissions.apply', ['formation' => $formation->slug]) }}"
                               class="rounded-full bg-accent px-6 py-3 text-sm font-semibold text-white hover:opacity-90 transition">
                                Candidater maintenant
                            </a>
                        </div>
                    </div>
                    <div class="relative hidden lg:flex aspect-[4/3] items-center justify-center rounded-3xl bg-white/10 backdrop-blur border border-white/10 shadow-2xl">
                        <x-nav-icon name="book-open" class="h-24 w-24 text-white/70" />
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Controls --}}
        <div class="relative z-10 flex items-center justify-center gap-3 pb-8">
            <template x-for="i in total" :key="i">
                <button type="button" @click="go(i - 1)"
                        :class="active === i - 1 ? 'w-8 bg-white' : 'w-2 bg-white/40 hover:bg-white/60'"
                        class="h-2 rounded-full transition-all" :aria-label="'Aller à la diapositive ' + i"></button>
            </template>
        </div>

        <button type="button" @click="go((active - 1 + total) % total)"
                class="absolute left-3 top-1/2 -translate-y-1/2 z-10 hidden sm:flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 transition"
                aria-label="Diapositive précédente">&larr;</button>
        <button type="button" @click="go((active + 1) % total)"
                class="absolute right-3 top-1/2 -translate-y-1/2 z-10 hidden sm:flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 transition"
                aria-label="Diapositive suivante">&rarr;</button>
    </section>

    {{-- STATS --}}
    <section class="border-b border-black/5 bg-white">
        <div class="mx-auto max-w-7xl px-6 py-12 grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            @foreach ([
                ['value' => $this->stats['students'], 'suffix' => '+', 'label' => 'Étudiants'],
                ['value' => $this->stats['formations'], 'suffix' => '+', 'label' => 'Formations'],
                ['value' => $this->stats['teachers'], 'suffix' => '+', 'label' => 'Enseignants'],
                ['value' => $this->stats['success_rate'], 'suffix' => '%', 'label' => 'de réussite'],
            ] as $stat)
                <div>
                    <p class="text-3xl sm:text-4xl font-semibold text-primary">{{ $stat['value'] }}{{ $stat['suffix'] }}</p>
                    <p class="mt-1 text-sm text-ink/60">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- FORMATIONS --}}
    <section class="mx-auto max-w-7xl px-6 py-20">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-semibold text-ink">Nos formations</h2>
                <p class="mt-2 text-ink/60">CAP, BEP, BT, BTS et formations modulaires.</p>
            </div>
            <a href="{{ route('formations.index') }}" class="hidden sm:inline text-sm font-semibold text-primary hover:underline">
                Voir tout le catalogue &rarr;
            </a>
        </div>

        <div class="mt-10 grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($this->formations as $formation)
                <a href="{{ route('formations.show', $formation->slug) }}"
                   class="group rounded-2xl border border-black/5 bg-white overflow-hidden shadow-sm hover:shadow-lg hover:-translate-y-1 transition">
                    <div class="aspect-[4/3] bg-gradient-to-br from-primary/90 to-primary-dark flex items-center justify-center">
                        <x-nav-icon name="book-open" class="h-12 w-12 text-white/80" />
                    </div>
                    <div class="p-5">
                        <span class="text-xs font-semibold text-accent uppercase tracking-wide">
                            {{ $formation->formationType->name }}
                        </span>
                        <h3 class="mt-1.5 font-semibold text-ink group-hover:text-primary transition">
                            {{ $formation->title }}
                        </h3>
                        <p class="mt-1 text-sm text-ink/60">{{ $formation->durationLabel() }}</p>
                    </div>
                </a>
            @empty
                <p class="col-span-full text-center text-ink/50 py-12">Aucune formation publiée pour le moment.</p>
            @endforelse
        </div>
    </section>

    {{-- WHY CHOOSE US --}}
    <section class="bg-white border-y border-black/5">
        <div class="mx-auto max-w-7xl px-6 py-20">
            <h2 class="text-2xl sm:text-3xl font-semibold text-ink text-center">Pourquoi nous choisir</h2>

            <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ([
                    ['title' => 'Formation pratique', 'text' => 'Apprendre par la pratique et développer des compétences directement exploitables.'],
                    ['title' => 'Encadrement professionnel', 'text' => 'Des enseignants et formateurs expérimentés.'],
                    ['title' => 'Insertion professionnelle', 'text' => 'Une formation orientée vers les besoins du marché.'],
                    ['title' => 'Infrastructures modernes', 'text' => 'Des espaces adaptés à la formation professionnelle.'],
                    ['title' => 'Accompagnement personnalisé', 'text' => 'Un suivi pédagogique et administratif des apprenants.'],
                ] as $item)
                    <div class="rounded-2xl bg-surface p-6">
                        <div class="h-10 w-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-semibold">
                            ✓
                        </div>
                        <h3 class="mt-4 font-semibold text-ink">{{ $item['title'] }}</h3>
                        <p class="mt-2 text-sm text-ink/60">{{ $item['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ADMISSIONS PROCESS --}}
    <section class="mx-auto max-w-7xl px-6 py-20">
        <h2 class="text-2xl sm:text-3xl font-semibold text-ink text-center">Comment candidater</h2>

        <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ([
                ['n' => '01', 't' => 'Choisir une formation'],
                ['n' => '02', 't' => 'Déposer sa candidature'],
                ['n' => '03', 't' => 'Étude du dossier'],
                ['n' => '04', 't' => 'Inscription'],
            ] as $step)
                <div class="rounded-2xl border border-black/5 p-6">
                    <span class="text-3xl font-semibold text-primary/30">{{ $step['n'] }}</span>
                    <p class="mt-3 font-semibold text-ink">{{ $step['t'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-10 text-center">
            <a href="{{ route('admissions.apply') }}"
               class="inline-block rounded-full bg-primary px-8 py-3.5 text-sm font-semibold text-white hover:opacity-90 transition">
                Commencer ma candidature
            </a>
        </div>
    </section>
</div>
