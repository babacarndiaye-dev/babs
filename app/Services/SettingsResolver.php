<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsResolver
{
    private const CACHE_KEY = 'app.settings';

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function (): array {
            return Setting::query()->pluck('value', 'key')->map(
                fn ($value, $key) => $this->cast($value, $key)
            )->all();
        });
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->all()[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        Setting::query()->where('key', $key)->update([
            'value' => is_bool($value) ? ($value ? '1' : '0') : $value,
        ]);

        $this->forget();
    }

    public function group(string $group): array
    {
        return Setting::query()->where('group', $group)->get()
            ->mapWithKeys(fn (Setting $setting) => [
                $setting->key => $this->cast($setting->value, $setting->key, $setting->type),
            ])->all();
    }

    public function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    private function cast(mixed $value, string $key, ?string $type = null): mixed
    {
        $type ??= Setting::query()->where('key', $key)->value('type');

        return match ($type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'json' => json_decode((string) $value, true),
            default => $value,
        };
    }
}
