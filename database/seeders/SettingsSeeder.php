<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Default settings for the pilot school (EEHT). Everything here is
     * editable from Administration > Paramètres — none of it is hard-coded
     * anywhere else in the application.
     */
    public function run(): void
    {
        $settings = [
            // Identité
            ['key' => 'identity.name', 'value' => 'Elite École Hôtelière et Touristique', 'type' => 'string', 'group' => 'identity', 'label' => "Nom de l'établissement"],
            ['key' => 'identity.acronym', 'value' => 'EEHT', 'type' => 'string', 'group' => 'identity', 'label' => 'Sigle'],
            ['key' => 'identity.slogan', 'value' => 'Construisez votre avenir professionnel.', 'type' => 'string', 'group' => 'identity', 'label' => 'Slogan'],
            ['key' => 'identity.address', 'value' => 'Dakar, Sénégal', 'type' => 'string', 'group' => 'identity', 'label' => 'Adresse'],
            ['key' => 'identity.phone', 'value' => '+221 33 000 00 00', 'type' => 'string', 'group' => 'identity', 'label' => 'Téléphone'],
            ['key' => 'identity.whatsapp', 'value' => '+221 77 000 00 00', 'type' => 'string', 'group' => 'identity', 'label' => 'WhatsApp'],
            ['key' => 'identity.email', 'value' => 'contact@eeht.sn', 'type' => 'string', 'group' => 'identity', 'label' => 'Email'],
            ['key' => 'identity.website', 'value' => 'https://eeht.sn', 'type' => 'string', 'group' => 'identity', 'label' => 'Site web'],
            ['key' => 'identity.facebook', 'value' => '', 'type' => 'string', 'group' => 'identity', 'label' => 'Facebook'],
            ['key' => 'identity.instagram', 'value' => '', 'type' => 'string', 'group' => 'identity', 'label' => 'Instagram'],
            ['key' => 'identity.linkedin', 'value' => '', 'type' => 'string', 'group' => 'identity', 'label' => 'LinkedIn'],

            // Design
            ['key' => 'design.color_primary', 'value' => '#0F5132', 'type' => 'string', 'group' => 'design', 'label' => 'Couleur primaire (vert profond)'],
            ['key' => 'design.color_secondary', 'value' => '#0B3D26', 'type' => 'string', 'group' => 'design', 'label' => 'Couleur secondaire'],
            ['key' => 'design.color_accent', 'value' => '#E8772E', 'type' => 'string', 'group' => 'design', 'label' => 'Couleur accent (orange)'],
            ['key' => 'design.color_bg', 'value' => '#FFFFFF', 'type' => 'string', 'group' => 'design', 'label' => 'Fond'],
            ['key' => 'design.color_surface', 'value' => '#F7F7F5', 'type' => 'string', 'group' => 'design', 'label' => 'Surface (gris très clair)'],
            ['key' => 'design.color_text', 'value' => '#1F2421', 'type' => 'string', 'group' => 'design', 'label' => 'Texte (charbon)'],

            // Académique
            ['key' => 'academic.default_grading_system', 'value' => 'systeme-20', 'type' => 'string', 'group' => 'academic', 'label' => 'Barème par défaut'],
            ['key' => 'academic.success_rate_display', 'value' => '90', 'type' => 'integer', 'group' => 'academic', 'label' => 'Taux de réussite affiché (%) — sera calculé automatiquement en Phase 13'],

            // Finance
            ['key' => 'finance.currency', 'value' => 'FCFA', 'type' => 'string', 'group' => 'finance', 'label' => 'Devise'],

            // Notifications
            ['key' => 'notifications.email_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'notifications', 'label' => 'Notifications email actives'],
            ['key' => 'notifications.sms_enabled', 'value' => '0', 'type' => 'boolean', 'group' => 'notifications', 'label' => 'Notifications SMS actives'],
            ['key' => 'notifications.whatsapp_enabled', 'value' => '0', 'type' => 'boolean', 'group' => 'notifications', 'label' => 'Notifications WhatsApp actives'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
