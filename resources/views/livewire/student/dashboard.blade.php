<div>
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-ink">
                Tableau de bord
                <span class="rounded-lg bg-violet-100 px-2 py-0.5 text-violet-700">Étudiant</span>
            </h1>
            <p class="mt-1 text-sm text-ink/60">
                <span class="font-semibold text-orange-600">Bonjour {{ auth()->user()->name }}</span>
                &nbsp;! Voici un aperçu de votre scolarité.
            </p>
        </div>

        <button wire:click="$refresh" type="button"
                class="flex items-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                <path d="M4 4v5h5" /><path d="M20 20v-5h-5" /><path d="M5.5 9A7.5 7.5 0 0 1 19 8M18.5 15a7.5 7.5 0 0 1-13.5 1" />
            </svg>
            Actualiser
        </button>
    </div>

    <div class="mt-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-stat-card color="blue" icon="chart-bar" label="Moyenne Générale"
                     :value="$generalAverage !== null ? number_format($generalAverage, 1).'/20' : '—'"
                     :sub="$gradesCount.' notes'" />
        <x-stat-card color="green" icon="user-check" label="Présences"
                     :value="$presenceRate !== null ? $presenceRate.'%' : '—'"
                     :sub="$unexcusedAbsences.' absences'" />
        <x-stat-card color="purple" icon="currency" label="Solde à Payer"
                     :value="number_format($balance, 0, ',', ' ').' '.setting('finance.currency')"
                     :sub="$balance > 0 ? $overdueInvoices.' facture(s) en retard' : 'À jour'" />
        <x-stat-card color="orange" icon="calendar" label="Cours Aujourd'hui" :value="$todaysSchedules->count()"
                     :sub="$student?->currentClass?->name ?? '—'" />
    </div>

    <div class="mt-4 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-alert-card color="red" icon="bell" label="Absences" :value="$unexcusedAbsences" sub="Non justifiées" />
        <x-alert-card color="blue" icon="folder" label="Documents" :value="$documentsCount" sub="Disponibles" />
        <x-alert-card color="amber" icon="currency" label="En retard" :value="$overdueInvoices" sub="Factures" />
        <x-alert-card color="green" icon="clipboard-check" label="Bulletins" :value="$reportCardsPublished" sub="Publiés" />
    </div>

    <div class="mt-6 grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 rounded-2xl border border-black/5 bg-white p-6">
            <h2 class="flex items-center gap-2 text-lg font-bold text-ink">
                <x-nav-icon name="calendar" class="h-5 w-5 text-ink/60" />
                Emploi du temps d'aujourd'hui
            </h2>

            <div class="mt-4 space-y-3">
                @forelse ($todaysSchedules as $schedule)
                    <div wire:key="schedule-{{ $schedule->id }}"
                         class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-black/5 px-4 py-3">
                        <div class="flex items-center gap-4">
                            <div class="text-sm font-medium text-ink/70 tabular-nums">
                                {{ substr($schedule->start_time, 0, 5) }}<br>{{ substr($schedule->end_time, 0, 5) }}
                            </div>
                            <div>
                                <p class="font-semibold text-ink">{{ $schedule->subject->name }}</p>
                                <p class="text-xs text-ink/50">{{ $schedule->teacher->fullName() }}</p>
                            </div>
                        </div>
                        @if ($schedule->room)
                            <span class="rounded-full border border-black/10 px-2.5 py-1 text-xs font-medium uppercase text-ink/60">
                                {{ $schedule->room->name }}
                            </span>
                        @endif
                    </div>
                @empty
                    <p class="py-10 text-center text-sm text-ink/50">Aucun cours prévu aujourd'hui.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-black/5 bg-white p-6">
            <h2 class="flex items-center gap-2 text-lg font-bold text-ink">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-orange-500">
                    <path d="M13 2 4 14h6l-1 8 9-12h-6l1-8Z" />
                </svg>
                Actions Rapides
            </h2>

            <div class="mt-4 space-y-2">
                @foreach ([
                    ['label' => 'Mes Notes', 'route' => 'student.grades', 'icon' => 'chart-bar'],
                    ['label' => 'Mes Absences', 'route' => 'student.attendances', 'icon' => 'user-check'],
                    ['label' => 'Mes Paiements', 'route' => 'student.payments', 'icon' => 'currency'],
                    ['label' => 'Mes Documents', 'route' => 'student.documents', 'icon' => 'folder'],
                    ['label' => 'Ma Formation', 'route' => 'student.formation', 'icon' => 'academic-cap'],
                ] as $action)
                    <a href="{{ route($action['route']) }}"
                       class="flex items-center justify-between rounded-xl border border-black/5 px-4 py-3 text-sm font-medium text-ink hover:bg-surface transition">
                        <span class="flex items-center gap-3">
                            <x-nav-icon :name="$action['icon']" class="h-5 w-5 text-ink/50" />
                            {{ $action['label'] }}
                        </span>
                        <span class="text-ink/30">&rarr;</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
