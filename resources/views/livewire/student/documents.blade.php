<div class="rounded-2xl border border-black/5 bg-white overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-surface text-left text-ink/60">
            <tr>
                <th class="px-5 py-3 font-medium">Document</th>
                <th class="px-5 py-3 font-medium">Référence</th>
                <th class="px-5 py-3 font-medium">Émis le</th>
                <th class="px-5 py-3 font-medium text-right">PDF</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-black/5">
            @forelse ($documents as $document)
                <tr wire:key="doc-{{ $document->id }}">
                    <td class="px-5 py-3.5 font-medium text-ink">{{ $document->template->name }}</td>
                    <td class="px-5 py-3.5 text-ink/70">{{ $document->reference }}</td>
                    <td class="px-5 py-3.5 text-ink/70">{{ $document->issued_at?->format('d/m/Y') }}</td>
                    <td class="px-5 py-3.5 text-right">
                        <a href="{{ route('documents.pdf', $document) }}" target="_blank" class="text-primary hover:underline font-medium">Télécharger</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-5 py-12 text-center text-ink/50">Aucun document émis pour le moment.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
