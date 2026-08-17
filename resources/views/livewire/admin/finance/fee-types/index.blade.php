<div>
    <div class="mb-6">
        <a href="{{ route('admin.finance.index') }}" class="text-sm text-ink/60 hover:text-primary">&larr; Retour à la finance</a>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-lg bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('status') }}</div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 rounded-2xl border border-black/5 bg-white overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-surface text-left text-ink/60">
                    <tr>
                        <th class="px-5 py-3 font-medium">Type de frais</th>
                        <th class="px-5 py-3 font-medium">Code</th>
                        <th class="px-5 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5">
                    @forelse ($feeTypes as $feeType)
                        <tr wire:key="fee-{{ $feeType->id }}">
                            <td class="px-5 py-3.5 font-medium text-ink">{{ $feeType->name }}</td>
                            <td class="px-5 py-3.5 text-ink/60">{{ $feeType->code }}</td>
                            <td class="px-5 py-3.5 text-right space-x-3">
                                <button wire:click="edit({{ $feeType->id }})" class="text-primary hover:underline font-medium">Modifier</button>
                                <button wire:click="delete({{ $feeType->id }})" wire:confirm="Supprimer ce type de frais ?"
                                        class="text-red-600 hover:underline font-medium">Supprimer</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-5 py-12 text-center text-ink/50">Aucun type de frais.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            <form wire:submit="save" class="rounded-2xl border border-black/5 bg-white p-6 space-y-4">
                <h3 class="font-semibold text-ink">{{ $editing ? 'Modifier' : 'Nouveau type de frais' }}</h3>
                <div>
                    <label class="block text-sm font-medium text-ink/80">Nom</label>
                    <input type="text" wire:model="name" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-ink/80">Code</label>
                    <input type="text" wire:model="code" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-ink/80">Description</label>
                    <textarea wire:model="description" rows="2" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none"></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                        Enregistrer
                    </button>
                    @if ($editing)
                        <button type="button" wire:click="cancel" class="rounded-lg border border-black/10 px-4 py-2.5 text-sm font-medium text-ink/70 hover:bg-surface transition">
                            Annuler
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
