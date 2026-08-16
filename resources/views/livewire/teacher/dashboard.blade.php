<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <x-kpi-card label="Mes classes" :value="$classesCount" />
    <x-kpi-card label="Bienvenue" :value="auth()->user()->name" />
</div>
