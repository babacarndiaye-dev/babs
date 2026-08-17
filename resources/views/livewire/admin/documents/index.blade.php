<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        @if (session('status'))
            <div class="mb-6 rounded-lg bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('status') }}</div>
        @endif

        <div class="rounded-2xl border border-black/5 bg-white overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-surface text-left text-ink/60">
                    <tr>
                        <th class="px-5 py-3 font-medium">Référence</th>
                        <th class="px-5 py-3 font-medium">Type</th>
                        <th class="px-5 py-3 font-medium">Étudiant</th>
                        <th class="px-5 py-3 font-medium">Émis le</th>
                        <th class="px-5 py-3 font-medium text-right">PDF</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5">
                    @forelse ($documents as $document)
                        <tr wire:key="doc-{{ $document->id }}">
                            <td class="px-5 py-3.5 font-medium text-ink">{{ $document->reference }}</td>
                            <td class="px-5 py-3.5 text-ink/70">{{ $document->template->name }}</td>
                            <td class="px-5 py-3.5 text-ink/70">{{ $document->documentable?->fullName() }}</td>
                            <td class="px-5 py-3.5 text-ink/70">{{ $document->issued_at?->format('d/m/Y') }}</td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('documents.pdf', $document) }}" target="_blank" class="text-primary hover:underline font-medium">Voir</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-12 text-center text-ink/50">Aucun document émis.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $documents->links() }}</div>
    </div>

    <div>
        <form wire:submit="issue" class="rounded-2xl border border-black/5 bg-white p-6 space-y-4">
            <h3 class="font-semibold text-ink">Émettre un document</h3>

            <div>
                <label class="block text-sm font-medium text-ink/80">Étudiant</label>
                <select wire:model="student_id" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    <option value="">—</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}">{{ $student->fullName() }} ({{ $student->matricule }})</option>
                    @endforeach
                </select>
                @error('student_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-ink/80">Type de document</label>
                <select wire:model="document_template_id" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    <option value="">—</option>
                    @foreach ($templates as $template)
                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                    @endforeach
                </select>
                @error('document_template_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                Émettre le document
            </button>
        </form>
    </div>
</div>
