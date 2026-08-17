<div>
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.applications.index') }}" class="text-sm text-ink/60 hover:text-primary">&larr; Retour aux candidatures</a>
        <x-application-status-badge :status="$application->status" />
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-lg bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('status') }}</div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-black/5 bg-white p-6">
                <h2 class="font-semibold text-ink">{{ $application->candidate->fullName() }}</h2>
                <p class="text-sm text-ink/50">N° {{ $application->application_number }} &middot; {{ $application->formation->title }}</p>

                <div class="mt-5 grid sm:grid-cols-2 gap-4 text-sm">
                    <div><span class="text-ink/50">Email</span><p class="text-ink">{{ $application->candidate->email }}</p></div>
                    <div><span class="text-ink/50">Téléphone</span><p class="text-ink">{{ $application->candidate->phone }}</p></div>
                    <div><span class="text-ink/50">Établissement précédent</span><p class="text-ink">{{ $application->candidate->previous_school ?? '—' }}</p></div>
                    <div><span class="text-ink/50">Dernier diplôme</span><p class="text-ink">{{ $application->candidate->last_diploma ?? '—' }}</p></div>
                    <div><span class="text-ink/50">Déposée le</span><p class="text-ink">{{ $application->submitted_at->format('d/m/Y à H:i') }}</p></div>
                </div>
            </div>

            <div class="rounded-2xl border border-black/5 bg-white p-6">
                <h3 class="font-semibold text-ink mb-4">Pièces jointes</h3>
                <div class="space-y-3">
                    @forelse ($application->documents as $document)
                        <div class="flex items-center justify-between rounded-lg border border-black/5 px-4 py-3">
                            <div>
                                <a href="{{ route('application-documents.show', $document) }}" target="_blank"
                                   class="text-sm font-medium text-primary hover:underline">{{ $document->document_type }}</a>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-medium
                                    {{ $document->status === 'valide' ? 'text-primary' : ($document->status === 'rejete' ? 'text-red-600' : 'text-ink/50') }}">
                                    {{ ['valide' => 'Validé', 'rejete' => 'Rejeté', 'en_attente' => 'En attente'][$document->status] }}
                                </span>
                                <button wire:click="reviewDocument({{ $document->id }}, 'valide')" class="text-xs text-primary hover:underline">Valider</button>
                                <button wire:click="reviewDocument({{ $document->id }}, 'rejete')" class="text-xs text-red-600 hover:underline">Rejeter</button>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-ink/50">Aucune pièce jointe.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-black/5 bg-white p-6">
                <h3 class="font-semibold text-ink mb-4">Historique</h3>
                <ul class="space-y-3">
                    @foreach ($application->statusHistory as $entry)
                        <li class="text-sm border-l-2 border-primary/30 pl-3">
                            <span class="text-ink/40">{{ $entry->created_at->format('d/m/Y H:i') }}</span> —
                            <span class="text-ink/70">{{ $entry->comment }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div>
            <div class="rounded-2xl border border-black/5 bg-white p-6 sticky top-24">
                <h3 class="font-semibold text-ink mb-4">Changer le statut</h3>
                <form wire:submit="updateStatus" class="space-y-4">
                    <select wire:model="newStatus" class="w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        @foreach ($statuses as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <textarea wire:model="comment" rows="3" placeholder="Commentaire (optionnel)"
                              class="w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none"></textarea>
                    <button type="submit" class="w-full rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                        Mettre à jour
                    </button>
                    @if ($newStatus === 'inscrit')
                        <p class="text-xs text-ink/50">Passer à « Inscrit » crée automatiquement le compte étudiant du candidat.</p>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
