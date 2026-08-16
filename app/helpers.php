<?php

use App\Services\SettingsResolver;

if (! function_exists('setting')) {
    /**
     * Read a value from the "Paramètres de l'établissement" (settings) store.
     * Never hard-code school identity/design/academic values — go through this helper.
     */
    function setting(?string $key = null, mixed $default = null): mixed
    {
        $resolver = app(SettingsResolver::class);

        return $key === null ? $resolver->all() : $resolver->get($key, $default);
    }
}
