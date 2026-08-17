<?php

namespace Database\Seeders;

use App\Models\DocumentTemplate;
use Illuminate\Database\Seeder;

class DocumentTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'code' => 'certificat_scolarite',
                'name' => 'Certificat de scolarité',
                'description' => "Atteste qu'un étudiant est régulièrement inscrit pour l'année académique en cours.",
                'numbering_format' => 'CERT-{year}-{seq}',
            ],
            [
                'code' => 'attestation_reussite',
                'name' => 'Attestation de réussite',
                'description' => "Atteste qu'un étudiant a validé sa formation avec succès.",
                'numbering_format' => 'ATT-{year}-{seq}',
            ],
            [
                'code' => 'certificat_fin_formation',
                'name' => 'Certificat de fin de formation',
                'description' => "Certifie qu'un étudiant a suivi et achevé l'intégralité d'une formation.",
                'numbering_format' => 'FIN-{year}-{seq}',
            ],
        ];

        foreach ($templates as $template) {
            DocumentTemplate::updateOrCreate(
                ['code' => $template['code']],
                $template + ['is_active' => true]
            );
        }
    }
}
