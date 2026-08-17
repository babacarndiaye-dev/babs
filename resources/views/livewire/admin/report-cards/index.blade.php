<div>
    @if (session('status'))
        <div class="mb-6 rounded-lg bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('status') }}</div>
    @endif

    <form wire:submit="generate" class="mb-6 flex flex-wrap items-end gap-3 rounded-2xl border border-black/5 bg-white p-5">
        <div>
            <label class="block text-sm font-medium text-ink/80">Classe</label>
            <select wire:model="classId" class="mt-1.5 rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                @foreach ($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-ink/80">Période</label>
            <input type="text" wire:model="period" placeholder="Trimestre 1"
                   class="mt-1.5 rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
        </div>
        <button type="submit" class="rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
            Générer / régénérer les bulletins
        </button>
        @if ($reportCards->isNotEmpty())
            <button type="button" wire:click="publish" wire:confirm="Publier ces bulletins ? Ils seront visibles par les étudiants."
                    class="rounded-lg bg-accent px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                Publier
            </button>
        @endif
    </form>

    @if ($reportCards->isNotEmpty())
        <form wire:submit="saveEdits" class="rounded-2xl border border-black/5 bg-white overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-surface text-left text-ink/60">
                    <tr>
                        <th class="px-5 py-3 font-medium">Rang</th>
                        <th class="px-5 py-3 font-medium">Étudiant</th>
                        <th class="px-5 py-3 font-medium">Moyenne</th>
                        <th class="px-5 py-3 font-medium">Décision</th>
                        <th class="px-5 py-3 font-medium">Commentaire</th>
                        <th class="px-5 py-3 font-medium">Statut</th>
                        <th class="px-5 py-3 font-medium text-right">PDF</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5">
                    @foreach ($reportCards as $card)
                        <tr wire:key="rc-{{ $card->id }}">
                            <td class="px-5 py-3 text-ink/70">{{ $card->rank }}/{{ $card->class_size }}</td>
                            <td class="px-5 py-3 font-medium text-ink">{{ $card->student->fullName() }}</td>
                            <td class="px-5 py-3 text-ink">{{ $card->general_average ?? '—' }}{{ $card->general_average !== null ? '/20' : '' }}</td>
                            <td class="px-5 py-3">
                                <input type="text" wire:model="edits.{{ $card->id }}.decision"
                                       class="w-32 rounded-lg border border-black/10 px-2.5 py-1.5 text-xs focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                            </td>
                            <td class="px-5 py-3">
                                <input type="text" wire:model="edits.{{ $card->id }}.comment"
                                       class="w-48 rounded-lg border border-black/10 px-2.5 py-1.5 text-xs focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                            </td>
                            <td class="px-5 py-3">
                                @if ($card->published_at)
                                    <span class="rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary">Publié</span>
                                @else
                                    <span class="rounded-full bg-black/5 px-2.5 py-1 text-xs font-semibold text-ink/50">Brouillon</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('report-cards.pdf', $card) }}" target="_blank" class="text-primary hover:underline font-medium">Voir</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-5 border-t border-black/5">
                <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                    Enregistrer les commentaires
                </button>
            </div>
        </form>
    @else
        <div class="rounded-2xl border border-dashed border-black/10 bg-white p-12 text-center text-ink/50">
            Aucun bulletin généré pour cette classe et cette période.
        </div>
    @endif
</div>
