<div>
    @if (session('status'))
        <div class="mb-6 rounded-lg bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('status') }}</div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <h2 class="font-semibold text-ink">Mes candidatures</h2>
        <a href="{{ route('candidate.applications.create') }}" class="rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
            + Nouvelle candidature
        </a>
    </div>

    <div class="grid gap-4">
        @forelse ($applications as $application)
            <div class="rounded-2xl border border-black/5 bg-white p-5 flex items-center justify-between">
                <div>
                    <p class="font-semibold text-ink">{{ $application->formation->title }}</p>
                    <p class="text-sm text-ink/50">N° {{ $application->application_number }} &middot; déposée le {{ $application->submitted_at->format('d/m/Y') }}</p>
                </div>
                <x-application-status-badge :status="$application->status" />
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-black/10 bg-white p-12 text-center text-ink/50">
                Vous n'avez pas encore de candidature soumise.
            </div>
        @endforelse
    </div>
</div>
