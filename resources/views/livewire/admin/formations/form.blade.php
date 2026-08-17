<div>
    <div class="mb-6">
        <a href="{{ route('admin.formations.index') }}" class="text-sm text-ink/60 hover:text-primary">&larr; Retour aux formations</a>
    </div>

    <form wire:submit="save" class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-black/5 bg-white p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-ink/80">Titre de la formation</label>
                    <input type="text" wire:model="title"
                           class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-ink/80">Type de formation</label>
                        <select wire:model="formation_type_id"
                                class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                            <option value="">—</option>
                            @foreach ($formationTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('formation_type_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink/80">Domaine</label>
                        <select wire:model="domain_id"
                                class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                            <option value="">—</option>
                            @foreach ($domains as $domain)
                                <option value="{{ $domain->id }}">{{ $domain->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink/80">Description courte</label>
                    <textarea wire:model="short_description" rows="2"
                              class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink/80">Description complète</label>
                    <textarea wire:model="description" rows="4"
                              class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none"></textarea>
                </div>
            </div>

            <div class="rounded-2xl border border-black/5 bg-white p-6 space-y-4">
                <h3 class="font-semibold text-ink">Programme</h3>

                @foreach ([
                    'admission_requirements' => "Conditions d'admission",
                    'objectives' => 'Objectifs',
                    'curriculum' => 'Programme / Curriculum',
                    'skills' => 'Compétences acquises',
                    'career_opportunities' => 'Débouchés professionnels',
                ] as $field => $label)
                    <div>
                        <label class="block text-sm font-medium text-ink/80">{{ $label }}</label>
                        <textarea wire:model="{{ $field }}" rows="3"
                                  class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none"></textarea>
                    </div>
                @endforeach

                <div>
                    <label class="block text-sm font-medium text-ink/80">Matières</label>
                    <div class="mt-2 grid sm:grid-cols-2 gap-2">
                        @foreach ($subjects as $subject)
                            <label class="flex items-center gap-2 text-sm text-ink/70">
                                <input type="checkbox" wire:model="subject_ids" value="{{ $subject->id }}"
                                       class="rounded border-black/20 text-primary focus:ring-primary">
                                {{ $subject->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-black/5 bg-white p-6 space-y-4">
                <h3 class="font-semibold text-ink">Détails</h3>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-ink/80">Durée</label>
                        <input type="number" wire:model="duration_value" min="1"
                               class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink/80">Unité</label>
                        <select wire:model="duration_unit"
                                class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                            <option value="mois">mois</option>
                            <option value="ans">ans</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink/80">Niveau (ex: Bac +2)</label>
                    <input type="text" wire:model="level_label"
                           class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink/80">Frais de scolarité ({{ setting('finance.currency') }})</label>
                    <input type="number" wire:model="tuition_fee" min="0" step="1000"
                           class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink/80">Places disponibles</label>
                    <input type="number" wire:model="seats_available" min="0"
                           class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink/80">Date de rentrée</label>
                    <input type="date" wire:model="start_date"
                           class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink/80">Image de couverture</label>
                    <input type="file" wire:model="cover"
                           class="mt-1.5 w-full text-sm">
                    @error('cover') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    @if ($formation?->getFirstMediaUrl('cover'))
                        <img src="{{ $formation->getFirstMediaUrl('cover') }}" class="mt-2 rounded-lg h-24 w-full object-cover">
                    @endif
                </div>

                <label class="flex items-center gap-2 text-sm text-ink/70">
                    <input type="checkbox" wire:model="is_published" class="rounded border-black/20 text-primary focus:ring-primary">
                    Publiée sur le site
                </label>

                <button type="submit"
                        class="w-full rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                    Enregistrer
                </button>
            </div>
        </div>
    </form>
</div>
