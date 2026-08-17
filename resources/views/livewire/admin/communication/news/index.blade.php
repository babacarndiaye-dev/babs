<div>
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.communication.index') }}" class="text-sm text-ink/60 hover:text-primary">&larr; Retour à la communication</a>
        <a href="{{ route('admin.communication.news.create') }}" class="rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
            + Nouvelle actualité
        </a>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-lg bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('status') }}</div>
    @endif

    <div class="rounded-2xl border border-black/5 bg-white overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-surface text-left text-ink/60">
                <tr>
                    <th class="px-5 py-3 font-medium">Titre</th>
                    <th class="px-5 py-3 font-medium">Catégorie</th>
                    <th class="px-5 py-3 font-medium">Statut</th>
                    <th class="px-5 py-3 font-medium">Publiée le</th>
                    <th class="px-5 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @forelse ($newsItems as $news)
                    <tr wire:key="news-{{ $news->id }}">
                        <td class="px-5 py-3.5 font-medium text-ink">{{ $news->title }}</td>
                        <td class="px-5 py-3.5 text-ink/70">{{ $news->category ?? '—' }}</td>
                        <td class="px-5 py-3.5">
                            <span class="rounded-full px-3 py-1 text-xs font-semibold
                                {{ $news->status === 'publie' ? 'bg-primary/10 text-primary' : 'bg-black/5 text-ink/50' }}">
                                {{ ['brouillon' => 'Brouillon', 'publie' => 'Publié', 'archive' => 'Archivé'][$news->status] }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-ink/70">{{ $news->published_at?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-right space-x-3">
                            <a href="{{ route('admin.communication.news.edit', $news) }}" class="text-primary hover:underline font-medium">Modifier</a>
                            <button wire:click="delete({{ $news->id }})" wire:confirm="Supprimer cette actualité ?"
                                    class="text-red-600 hover:underline font-medium">Supprimer</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-ink/50">Aucune actualité.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $newsItems->links() }}</div>
</div>
