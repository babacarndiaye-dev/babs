<?php

namespace Database\Seeders;

use App\Models\FeeType;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class FinanceSeeder extends Seeder
{
    /**
     * Payment methods are seeded as clean, inactive-by-default placeholders
     * for the online ones — the architecture calls for real integration
     * interfaces later, never a faked "successful payment".
     */
    public function run(): void
    {
        $methods = [
            ['name' => 'Espèces', 'code' => 'especes', 'is_online' => false],
            ['name' => 'Wave', 'code' => 'wave', 'is_online' => true],
            ['name' => 'Orange Money', 'code' => 'orange_money', 'is_online' => true],
            ['name' => 'Free Money', 'code' => 'free_money', 'is_online' => true],
            ['name' => 'Virement bancaire', 'code' => 'virement', 'is_online' => false],
            ['name' => 'Carte bancaire', 'code' => 'carte', 'is_online' => true],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(
                ['code' => $method['code']],
                $method + ['is_active' => true]
            );
        }

        $feeTypes = [
            ['name' => "Frais d'inscription", 'code' => 'inscription'],
            ['name' => 'Scolarité', 'code' => 'scolarite'],
            ['name' => "Frais d'examen", 'code' => 'examen'],
            ['name' => 'Frais de stage', 'code' => 'stage'],
        ];

        foreach ($feeTypes as $feeType) {
            FeeType::updateOrCreate(['code' => $feeType['code']], $feeType);
        }
    }
}
