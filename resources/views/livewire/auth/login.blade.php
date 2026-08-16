<div class="mx-auto flex min-h-[70vh] max-w-md flex-col justify-center px-6 py-16">
    <div class="text-center">
        <h1 class="text-2xl font-semibold text-ink">Connexion</h1>
        <p class="mt-2 text-sm text-ink/60">Accédez à votre espace étudiant, enseignant ou administration.</p>
    </div>

    <form wire:submit="login" class="mt-8 space-y-5 rounded-2xl border border-black/5 bg-white p-8 shadow-sm">
        <div>
            <label class="block text-sm font-medium text-ink/80">Email</label>
            <input type="email" wire:model="email"
                   class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
            @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-ink/80">Mot de passe</label>
            <input type="password" wire:model="password"
                   class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
            @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <label class="flex items-center gap-2 text-sm text-ink/70">
            <input type="checkbox" wire:model="remember" class="rounded border-black/20 text-primary focus:ring-primary">
            Se souvenir de moi
        </label>

        <button type="submit"
                class="w-full rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition"
                wire:loading.attr="disabled">
            Se connecter
        </button>
    </form>
</div>
