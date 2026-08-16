<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Domain;
use App\Models\Formation;
use App\Models\FormationType;
use App\Models\GradingScale;
use App\Models\GradingSystem;
use App\Models\Level;
use App\Models\Room;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoSchoolSeeder extends Seeder
{
    /**
     * Generic demonstration data for the pilot school (EEHT): a full
     * reference dataset (years, levels, grading, formations) that is
     * meant to be replaced/extended from the administration, never
     * hard-coded into the application code.
     */
    public function run(): void
    {
        $year = AcademicYear::updateOrCreate(
            ['name' => '2026-2027'],
            ['start_date' => '2026-10-01', 'end_date' => '2027-07-31', 'is_current' => true]
        );

        $levels = collect(['1ère année', '2ème année', '3ème année'])
            ->map(fn (string $name, int $i) => Level::updateOrCreate(['name' => $name], ['order' => $i + 1]));

        $gradingSystem = GradingSystem::updateOrCreate(
            ['name' => 'Système /20'],
            ['scale_max' => 20, 'type' => 'numeric', 'passing_threshold' => 10, 'is_default' => true]
        );

        foreach ([
            ['label' => 'Excellent', 'min' => 16, 'max' => 20],
            ['label' => 'Bien', 'min' => 14, 'max' => 15.99],
            ['label' => 'Passable', 'min' => 10, 'max' => 13.99],
            ['label' => 'Insuffisant', 'min' => 0, 'max' => 9.99],
        ] as $scale) {
            GradingScale::updateOrCreate(
                ['grading_system_id' => $gradingSystem->id, 'label' => $scale['label']],
                ['min_score' => $scale['min'], 'max_score' => $scale['max']]
            );
        }

        $formationTypes = collect([
            ['name' => 'CAP', 'code' => 'CAP', 'description' => "Certificat d'Aptitude Professionnelle"],
            ['name' => 'BEP', 'code' => 'BEP', 'description' => "Brevet d'Études Professionnelles"],
            ['name' => 'BT', 'code' => 'BT', 'description' => 'Brevet de Technicien'],
            ['name' => 'BTS', 'code' => 'BTS', 'description' => 'Brevet de Technicien Supérieur'],
            ['name' => 'Formation modulaire', 'code' => 'MODULAIRE', 'description' => 'Formation courte et qualifiante'],
        ])->mapWithKeys(fn ($t) => [$t['code'] => FormationType::updateOrCreate(['code' => $t['code']], $t)]);

        $domains = collect([
            'Hôtellerie & Restauration',
            'Informatique & Numérique',
            'Commerce & Marketing',
        ])->mapWithKeys(fn (string $name) => [$name => Domain::updateOrCreate(
            ['slug' => Str::slug($name)],
            ['name' => $name]
        )]);

        collect([
            'Français', 'Mathématiques', 'Anglais', 'Informatique appliquée',
            'Techniques professionnelles', 'Gestion', 'Communication', 'Stage pratique',
        ])->each(fn (string $name) => Subject::updateOrCreate(
            ['code' => Str::upper(Str::slug($name, '_'))],
            ['name' => $name]
        ));

        $rooms = collect([
            ['name' => 'Atelier Cuisine 1', 'type' => 'atelier'],
            ['name' => 'Atelier Restaurant', 'type' => 'atelier'],
            ['name' => 'Salle Informatique A', 'type' => 'labo_info'],
            ['name' => 'Salle 101', 'type' => 'salle'],
        ])->map(fn (array $r) => Room::updateOrCreate(['name' => $r['name']], $r));

        $formations = [
            [
                'formation_type_id' => $formationTypes['BTS']->id,
                'domain_id' => $domains['Informatique & Numérique']->id,
                'title' => 'BTS Informatique',
                'duration_value' => 2, 'duration_unit' => 'ans',
                'short_description' => 'Développement, réseaux et systèmes pour les métiers du numérique.',
                'tuition_fee' => 650000, 'seats_available' => 30,
            ],
            [
                'formation_type_id' => $formationTypes['CAP']->id,
                'domain_id' => $domains['Hôtellerie & Restauration']->id,
                'title' => 'CAP Restauration',
                'duration_value' => 2, 'duration_unit' => 'ans',
                'short_description' => 'Techniques culinaires et service en restauration.',
                'tuition_fee' => 450000, 'seats_available' => 25,
            ],
            [
                'formation_type_id' => $formationTypes['MODULAIRE']->id,
                'domain_id' => $domains['Informatique & Numérique']->id,
                'title' => 'Formation en Infographie',
                'duration_value' => 3, 'duration_unit' => 'mois',
                'short_description' => 'Conception graphique et outils de création visuelle.',
                'tuition_fee' => 180000, 'seats_available' => 20,
            ],
            [
                'formation_type_id' => $formationTypes['MODULAIRE']->id,
                'domain_id' => $domains['Commerce & Marketing']->id,
                'title' => 'Marketing Digital',
                'duration_value' => 6, 'duration_unit' => 'mois',
                'short_description' => 'Stratégies digitales, réseaux sociaux et publicité en ligne.',
                'tuition_fee' => 250000, 'seats_available' => 20,
            ],
        ];

        $classes = collect();

        foreach ($formations as $data) {
            $formation = Formation::updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                $data + [
                    'slug' => Str::slug($data['title']),
                    'level_label' => $data['formation_type_id'] === $formationTypes['BTS']->id ? 'Bac +2' : null,
                    'admission_requirements' => "Être titulaire d'un diplôme de niveau requis pour la formation, dossier de candidature complet.",
                    'objectives' => "Acquérir les compétences professionnelles nécessaires à l'exercice du métier visé.",
                    'is_published' => true,
                ]
            );

            $classes->push(SchoolClass::updateOrCreate(
                ['name' => $formation->title.' - '.$levels->first()->name, 'academic_year_id' => $year->id],
                [
                    'formation_id' => $formation->id,
                    'level_id' => $levels->first()->id,
                    'room_id' => $rooms->first()->id,
                    'capacity' => $data['seats_available'],
                ]
            ));
        }

        User::updateOrCreate(
            ['email' => 'admin@eeht.sn'],
            ['name' => 'Administrateur EEHT', 'password' => bcrypt('password')]
        )->assignRole('super-admin');

        $teacherUser = User::updateOrCreate(
            ['email' => 'enseignant@eeht.sn'],
            ['name' => 'Aïssatou Ndiaye', 'password' => bcrypt('password')]
        );
        $teacherUser->assignRole('enseignant');
        Teacher::updateOrCreate(
            ['user_id' => $teacherUser->id],
            ['matricule' => 'ENS-0001', 'first_name' => 'Aïssatou', 'last_name' => 'Ndiaye', 'specialty' => 'Informatique', 'is_active' => true]
        );

        collect([
            ['first' => 'Moussa', 'last' => 'Diop', 'matricule' => 'ETU-0001'],
            ['first' => 'Fatou', 'last' => 'Sarr', 'matricule' => 'ETU-0002'],
            ['first' => 'Ibrahima', 'last' => 'Fall', 'matricule' => 'ETU-0003'],
            ['first' => 'Aminata', 'last' => 'Gueye', 'matricule' => 'ETU-0004'],
            ['first' => 'Cheikh', 'last' => 'Ba', 'matricule' => 'ETU-0005'],
        ])->each(function (array $s, int $i) use ($classes, $year) {
            $studentUser = User::updateOrCreate(
                ['email' => Str::lower($s['first']).'.'.Str::lower($s['last']).'@eeht.sn'],
                ['name' => "{$s['first']} {$s['last']}", 'password' => bcrypt('password')]
            );
            $studentUser->assignRole('etudiant');

            $class = $classes[$i % $classes->count()];

            $student = Student::updateOrCreate(
                ['matricule' => $s['matricule']],
                [
                    'user_id' => $studentUser->id,
                    'first_name' => $s['first'],
                    'last_name' => $s['last'],
                    'current_class_id' => $class->id,
                    'status' => 'actif',
                    'enrolled_at' => $year->start_date,
                ]
            );

            $student->classes()->syncWithoutDetaching([
                $class->id => ['academic_year_id' => $year->id, 'status' => 'inscrit', 'enrolled_at' => $year->start_date],
            ]);
        });
    }
}
