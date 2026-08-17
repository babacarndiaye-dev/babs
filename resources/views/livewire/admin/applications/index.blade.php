<div>
    <div class="flex flex-wrap items-center gap-3 mb-6">
        <input type="text" wire:model.live.debounce.400ms="search" placeholder="Rechercher (nom, n° dossier)..."
               class="w-full max-w-xs rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">

        <select wire:model.live="status" class="rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
            <option value="">Tous les statuts</option>
            @foreach ($statuses as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="rounded-2xl border border-black/5 bg-white overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-surface text-left text-ink/60">
                <tr>
                    <th class="px-5 py-3 font-medium">N° Dossier</th>
                    <th class="px-5 py-3 font-medium">Candidat</th>
                    <th class="px-5 py-3 font-medium">Formation</th>
                    <th class="px-5 py-3 font-medium">Déposée le</th>
                    <th class="px-5 py-3 font-medium">Statut</th>
                    <th class="px-5 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @forelse ($applications as $application)
                    <tr wire:key="app-{{ $application->id }}">
                        <td class="px-5 py-3.5 font-medium text-ink">{{ $application->application_number }}</td>
                        <td class="px-5 py-3.5 text-ink/70">{{ $application->candidate->fullName() }}</td>
                        <td class="px-5 py-3.5 text-ink/70">{{ $application->formation->title }}</td>
                        <td class="px-5 py-3.5 text-ink/70">{{ $application->submitted_at->format('d/m/Y') }}</td>
                        <td class="px-5 py-3.5"><x-application-status-badge :status="$application->status" /></td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('admin.applications.show', $application) }}" class="text-primary hover:underline font-medium">Voir le dossier</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-ink/50">Aucune candidature.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $applications->links() }}</div>
</div>
