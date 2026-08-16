<div>
    <div class="bg-primary">
        <div class="mx-auto max-w-5xl px-6 py-16 text-white">
            <span class="text-xs font-semibold uppercase tracking-wide text-white/70">
                {{ $formation->formationType->name }} @if($formation->domain) &middot; {{ $formation->domain->name }} @endif
            </span>
            <h1 class="mt-2 text-3xl sm:text-4xl font-semibold">{{ $formation->title }}</h1>
            <p class="mt-4 text-white/80 max-w-2xl">{{ $formation->short_description }}</p>

            <div class="mt-6 flex flex-wrap gap-6 text-sm">
                <div>
                    <p class="text-white/60">Durée</p>
                    <p class="font-semibold">{{ $formation->durationLabel() }}</p>
                </div>
                @if ($formation->level_label)
                    <div>
                        <p class="text-white/60">Niveau</p>
                        <p class="font-semibold">{{ $formation->level_label }}</p>
                    </div>
                @endif
                @if ($formation->tuition_fee)
                    <div>
                        <p class="text-white/60">Frais de scolarité</p>
                        <p class="font-semibold">{{ number_format((float) $formation->tuition_fee, 0, ',', ' ') }} {{ setting('finance.currency') }}</p>
                    </div>
                @endif
                @if ($formation->seats_available)
                    <div>
                        <p class="text-white/60">Places disponibles</p>
                        <p class="font-semibold">{{ $formation->seats_available }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-5xl px-6 py-16 grid lg:grid-cols-3 gap-12">
        <div class="lg:col-span-2 space-y-10">
            @if ($formation->admission_requirements)
                <div>
                    <h2 class="text-lg font-semibold text-ink">Conditions d'admission</h2>
                    <p class="mt-2 text-ink/70 whitespace-pre-line">{{ $formation->admission_requirements }}</p>
                </div>
            @endif

            @if ($formation->objectives)
                <div>
                    <h2 class="text-lg font-semibold text-ink">Objectifs</h2>
                    <p class="mt-2 text-ink/70 whitespace-pre-line">{{ $formation->objectives }}</p>
                </div>
            @endif

            @if ($formation->curriculum)
                <div>
                    <h2 class="text-lg font-semibold text-ink">Programme</h2>
                    <p class="mt-2 text-ink/70 whitespace-pre-line">{{ $formation->curriculum }}</p>
                </div>
            @endif

            @if ($formation->subjects->isNotEmpty())
                <div>
                    <h2 class="text-lg font-semibold text-ink">Matières</h2>
                    <ul class="mt-3 grid sm:grid-cols-2 gap-2">
                        @foreach ($formation->subjects as $subject)
                            <li class="rounded-lg bg-surface px-3 py-2 text-sm text-ink/80">{{ $subject->name }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($formation->skills)
                <div>
                    <h2 class="text-lg font-semibold text-ink">Compétences acquises</h2>
                    <p class="mt-2 text-ink/70 whitespace-pre-line">{{ $formation->skills }}</p>
                </div>
            @endif

            @if ($formation->career_opportunities)
                <div>
                    <h2 class="text-lg font-semibold text-ink">Débouchés professionnels</h2>
                    <p class="mt-2 text-ink/70 whitespace-pre-line">{{ $formation->career_opportunities }}</p>
                </div>
            @endif
        </div>

        <div>
            <div class="sticky top-24 rounded-2xl border border-black/5 bg-white p-6 shadow-sm text-center">
                <p class="font-semibold text-ink">Intéressé(e) par cette formation ?</p>
                <a href="{{ route('admissions.apply', ['formation' => $formation->slug]) }}"
                   class="mt-4 block rounded-full bg-accent px-6 py-3 text-sm font-semibold text-white hover:opacity-90 transition">
                    Candidater à cette formation
                </a>
            </div>
        </div>
    </div>
</div>
