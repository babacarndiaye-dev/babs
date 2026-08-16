<?php

namespace App\Livewire\Admin\Settings;

use App\Models\Setting;
use App\Services\SettingsResolver;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    /**
     * Nested as [group][local_key] => value, e.g. values['identity']['name'].
     * A flat "group.key" array can't be used directly because wire:model
     * splits every dot into a nested path.
     *
     * @var array<string, array<string, mixed>>
     */
    public array $values = [];

    public string $activeGroup = 'identity';

    public array $groups = [
        'identity' => 'Identité',
        'design' => 'Design',
        'academic' => 'Académique',
        'finance' => 'Finance',
        'notifications' => 'Notifications',
    ];

    public function mount(): void
    {
        foreach (Setting::all() as $setting) {
            $this->values[$setting->group][$this->localKey($setting->key)] = $setting->value;
        }
    }

    public function save(SettingsResolver $settings): void
    {
        foreach ($this->settingsForGroup($this->activeGroup) as $setting) {
            $local = $this->localKey($setting->key);
            $settings->set($setting->key, $this->values[$this->activeGroup][$local] ?? $setting->value);
        }

        $settings->forget();

        session()->flash('status', "Paramètres « {$this->groups[$this->activeGroup]} » enregistrés.");
    }

    public function settingsForGroup(string $group)
    {
        return Setting::query()->where('group', $group)->orderBy('label')->get();
    }

    private function localKey(string $key): string
    {
        return Str::after($key, '.');
    }

    public function render()
    {
        return view('livewire.admin.settings.index', [
            'settings' => $this->settingsForGroup($this->activeGroup),
        ]);
    }
}
