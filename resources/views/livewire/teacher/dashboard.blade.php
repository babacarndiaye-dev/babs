<div>
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-ink">
                Tableau de bord
                <span class="rounded-lg bg-violet-100 px-2 py-0.5 text-violet-700">Enseignant</span>
            </h1>
            <p class="mt-1 text-sm text-ink/60">
                <span class="font-semibold text-orange-600">Bonjour {{ auth()->user()->name }}</span>
                &nbsp;! Voici un aperçu de votre journée et de vos classes.
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
        <x-stat-card color="blue" icon="users" label="Mes Élèves" :value="$studentsTotal"
                     :sub="$presentToday.' présents aujourd\'hui'" />
        <x-stat-card color="green" icon="user-check" label="Présences"
                     :value="$attendanceRate !== null ? $attendanceRate.'%' : '—'"
                     :sub="$absentToday.' absents'" />
        <x-stat-card color="purple" icon="chart-bar" label="Moyenne Générale"
                     :value="$averageGeneral !== null ? number_format($averageGeneral, 1).'/20' : '—'"
                     :sub="$gradesCount.' notes saisies'" />
        <x-stat-card color="orange" icon="calendar" label="Cours Aujourd'hui" :value="$todaysSchedules->count()"
                     :sub="$classesCount.' classes au total'" />
    </div>

    <div class="mt-4 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-alert-card color="red" icon="bell" label="Alertes" :value="$studentsToWatch" sub="Élèves à suivre" />
        <x-alert-card color="blue" icon="clipboard-list" label="À corriger" :value="$pendingGrading" sub="Évaluations" />
        <x-alert-card color="amber" icon="chart-bar" label="Difficultés" :value="$strugglingStudents" sub="Élèves < 10/20" />
        <x-alert-card color="green" icon="clipboard-check" label="Terminé" :value="$fullyGraded" sub="Évaluations notées" />
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
                                <p class="text-xs text-ink/50">{{ $schedule->schoolClass->name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if ($schedule->room)
                                <span class="rounded-full border border-black/10 px-2.5 py-1 text-xs font-medium uppercase text-ink/60">
                                    {{ $schedule->room->name }}
                                </span>
                            @endif
                            <a href="{{ route('teacher.attendances.index') }}"
                               class="rounded-full border border-black/10 px-3 py-1.5 text-xs font-medium text-ink/70 hover:bg-surface transition">
                                Marquer absence
                            </a>
                            <a href="{{ route('teacher.classes.index') }}"
                               class="rounded-full border border-black/10 px-3 py-1.5 text-xs font-medium text-ink/70 hover:bg-surface transition">
                                Voir
                            </a>
                        </div>
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
                    ['label' => 'Mes Élèves', 'route' => 'teacher.classes.index', 'icon' => 'users'],
                    ['label' => 'Saisir des Notes', 'route' => 'teacher.assessments.index', 'icon' => 'clipboard-list'],
                    ['label' => 'Gérer Absences', 'route' => 'teacher.attendances.index', 'icon' => 'user-check'],
                    ['label' => 'Emploi du Temps', 'route' => 'teacher.schedule', 'icon' => 'calendar'],
                    ['label' => 'Mes Classes', 'route' => 'teacher.classes.index', 'icon' => 'academic-cap'],
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
