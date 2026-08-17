<div>
    <div class="mb-6 rounded-2xl border border-black/5 bg-white p-6">
        <p class="text-sm text-ink/50">Taux de présence</p>
        <p class="mt-1 text-3xl font-semibold text-primary">{{ $presenceRate }}%</p>
    </div>

    <div class="rounded-2xl border border-black/5 bg-white overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-surface text-left text-ink/60">
                <tr>
                    <th class="px-5 py-3 font-medium">Date</th>
                    <th class="px-5 py-3 font-medium">Matière</th>
                    <th class="px-5 py-3 font-medium">Statut</th>
                    <th class="px-5 py-3 font-medium">Remarque</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @forelse ($attendances as $attendance)
                    <tr wire:key="att-{{ $attendance->id }}">
                        <td class="px-5 py-3 text-ink">{{ \Illuminate\Support\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                        <td class="px-5 py-3 text-ink/70">{{ $attendance->subject?->name ?? '—' }}</td>
                        <td class="px-5 py-3">
                            <span class="rounded-full px-3 py-1 text-xs font-semibold
                                {{ match($attendance->status) {
                                    'present' => 'bg-primary/10 text-primary',
                                    'retard' => 'bg-amber-100 text-amber-700',
                                    'excuse' => 'bg-blue-100 text-blue-700',
                                    default => 'bg-red-100 text-red-700',
                                } }}">
                                {{ ['present' => 'Présent', 'absent' => 'Absent', 'retard' => 'Retard', 'excuse' => 'Excusé'][$attendance->status] }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-ink/50">{{ $attendance->remark ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-12 text-center text-ink/50">Aucun enregistrement de présence.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
