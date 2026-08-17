<div>
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-ink">
                Tableau de bord
                <span class="rounded-lg bg-violet-100 px-2 py-0.5 text-violet-700">Administration</span>
            </h1>
            <p class="mt-1 text-sm text-ink/60">
                <span class="font-semibold text-orange-600">Bonjour {{ auth()->user()->name }}</span>
                &nbsp;! Voici un aperçu de l'établissement.
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
        <x-stat-card color="blue" icon="academic-cap" label="Étudiants Actifs" :value="$studentsActive"
                     :sub="$studentsTotal.' au total'" />
        <x-stat-card color="green" icon="users" label="Enseignants" :value="$teachersTotal"
                     :sub="$teachersActive.' actifs'" />
        <x-stat-card color="purple" icon="book-open" label="Formations Publiées" :value="$formationsPublished"
                     :sub="$formationsTotal.' au total'" />
        <x-stat-card color="orange" icon="currency" label="Total Facturé"
                     :value="number_format((float) $invoicesBilled, 0, ',', ' ').' '.setting('finance.currency')"
                     :sub="$invoicesOutstanding.' facture(s) en attente'" />
    </div>

    <div class="mt-4 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-alert-card color="red" icon="bell" label="À traiter" :value="$applicationsPending" sub="Candidatures" />
        <x-alert-card color="blue" icon="inbox" label="Nouvelles" :value="$applicationsNew" sub="Candidatures" />
        <x-alert-card color="amber" icon="currency" label="En retard" :value="$invoicesOverdue" sub="Factures" />
        <x-alert-card color="green" icon="user-check" label="À suivre" :value="$studentsWatch" sub="Étudiants" />
    </div>

    <div class="mt-6 grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 rounded-2xl border border-black/5 bg-white p-6">
            <div class="flex items-center justify-between">
                <h2 class="flex items-center gap-2 text-lg font-bold text-ink">
                    <x-nav-icon name="inbox" class="h-5 w-5 text-ink/60" />
                    Candidatures récentes
                </h2>
                <a href="{{ route('admin.applications.index') }}" class="text-xs font-medium text-primary hover:underline">Voir tout</a>
            </div>

            <div class="mt-4 space-y-3">
                @forelse ($recentApplications as $application)
                    <a href="{{ route('admin.applications.show', $application) }}" wire:key="app-{{ $application->id }}"
                       class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-black/5 px-4 py-3 hover:bg-surface transition">
                        <div>
                            <p class="font-semibold text-ink">{{ $application->candidate->fullName() }}</p>
                            <p class="text-xs text-ink/50">{{ $application->formation->title }} &middot; {{ $application->application_number }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-ink/40">{{ $application->submitted_at?->format('d/m/Y') }}</span>
                            <x-application-status-badge :status="$application->status" />
                        </div>
                    </a>
                @empty
                    <p class="py-10 text-center text-sm text-ink/50">Aucune candidature pour le moment.</p>
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
                    ['label' => 'Candidatures', 'route' => 'admin.applications.index', 'icon' => 'inbox'],
                    ['label' => 'Étudiants', 'route' => 'admin.students.index', 'icon' => 'academic-cap'],
                    ['label' => 'Nouvelle Formation', 'route' => 'admin.formations.create', 'icon' => 'book-open'],
                    ['label' => 'Finance', 'route' => 'admin.finance.index', 'icon' => 'currency'],
                    ['label' => 'Paramètres', 'route' => 'admin.settings.index', 'icon' => 'cog'],
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
