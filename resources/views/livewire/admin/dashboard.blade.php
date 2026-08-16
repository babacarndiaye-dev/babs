<div class="space-y-8">
    <div>
        <h2 class="text-sm font-semibold text-ink/50 uppercase tracking-wide">Étudiants</h2>
        <div class="mt-3 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-kpi-card label="Total étudiants" :value="$kpis['students_total']" />
            <x-kpi-card label="Étudiants actifs" :value="$kpis['students_active']" />
            <x-kpi-card label="Enseignants" :value="$kpis['teachers_total']" />
            <x-kpi-card label="Formations publiées" :value="$kpis['formations_published']" />
        </div>
    </div>

    <div>
        <h2 class="text-sm font-semibold text-ink/50 uppercase tracking-wide">Candidatures</h2>
        <div class="mt-3 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-kpi-card label="Nouvelles candidatures" :value="$kpis['applications_new']" />
            <x-kpi-card label="En étude" :value="$kpis['applications_review']" />
        </div>
    </div>

    <div>
        <h2 class="text-sm font-semibold text-ink/50 uppercase tracking-wide">Finance</h2>
        <div class="mt-3 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-kpi-card label="Total facturé" :value="number_format((float) $kpis['invoices_billed'], 0, ',', ' ').' '.setting('finance.currency')" />
            <x-kpi-card label="Factures en attente" :value="$kpis['invoices_outstanding']" />
        </div>
    </div>
</div>
