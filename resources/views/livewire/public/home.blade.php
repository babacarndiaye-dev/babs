<div>
    {{-- HERO --}}
    <section class="relative overflow-hidden bg-primary">
        <div class="absolute inset-0 bg-gradient-to-br from-primary via-primary-dark to-black/40"></div>
        <div class="relative mx-auto max-w-7xl px-6 py-24 sm:py-32 grid lg:grid-cols-2 gap-12 items-center">
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
            <div class="relative hidden lg:block">
                <div class="aspect-[4/3] rounded-3xl bg-white/10 backdrop-blur border border-white/10 shadow-2xl"></div>
            </div>
        </div>
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
                   class="group rounded-2xl border border-black/5 bg-white overflow-hidden shadow-sm hover:shadow-lg transition">
                    <div class="aspect-[4/3] bg-surface"></div>
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
