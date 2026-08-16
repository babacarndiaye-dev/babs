<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Permission nodes grouped by module. Each is a "module.action" node,
     * assignable to any role from the administration UI without redeploying.
     */
    private array $permissions = [
        'settings' => ['view', 'manage'],
        'users' => ['view', 'manage'],
        'roles' => ['view', 'manage'],
        'formations' => ['view', 'manage'],
        'applications' => ['view', 'manage', 'decide'],
        'students' => ['view', 'manage'],
        'teachers' => ['view', 'manage'],
        'classes' => ['view', 'manage'],
        'schedules' => ['view', 'manage'],
        'attendances' => ['view', 'manage'],
        'assessments' => ['view', 'manage'],
        'grades' => ['view', 'manage'],
        'report_cards' => ['view', 'manage', 'publish'],
        'finance' => ['view', 'manage'],
        'internships' => ['view', 'manage'],
        'documents' => ['view', 'manage', 'issue'],
        'communication' => ['view', 'manage'],
        'audit_logs' => ['view'],
    ];

    /**
     * Roles and the permission modules they get in full ("*") or a
     * restricted subset. These are presets, not hard constraints — every
     * permission stays individually assignable from the admin UI.
     */
    private array $rolePresets = [
        'super-admin' => '*',
        'directeur' => '*',
        'administrateur' => '*',
        'responsable-academique' => [
            'formations' => ['view', 'manage'],
            'classes' => ['view', 'manage'],
            'schedules' => ['view', 'manage'],
            'assessments' => ['view', 'manage'],
            'grades' => ['view', 'manage'],
            'report_cards' => ['view', 'manage', 'publish'],
            'teachers' => ['view'],
            'students' => ['view'],
        ],
        'scolarite' => [
            'applications' => ['view', 'manage', 'decide'],
            'students' => ['view', 'manage'],
            'classes' => ['view'],
            'documents' => ['view', 'manage', 'issue'],
        ],
        'comptable' => [
            'finance' => ['view', 'manage'],
            'students' => ['view'],
            'documents' => ['view', 'issue'],
        ],
        'enseignant' => [
            'classes' => ['view'],
            'schedules' => ['view'],
            'attendances' => ['view', 'manage'],
            'assessments' => ['view', 'manage'],
            'grades' => ['view', 'manage'],
            'students' => ['view'],
        ],
        'etudiant' => [],
        'candidat' => [],
    ];

    public function run(): void
    {
        $all = [];

        foreach ($this->permissions as $module => $actions) {
            foreach ($actions as $action) {
                $all[] = Permission::firstOrCreate(['name' => "{$module}.{$action}"]);
            }
        }

        foreach ($this->rolePresets as $roleName => $preset) {
            $role = Role::firstOrCreate(['name' => $roleName]);

            if ($preset === '*') {
                $role->syncPermissions($all);

                continue;
            }

            $names = [];
            foreach ($preset as $module => $actions) {
                foreach ($actions as $action) {
                    $names[] = "{$module}.{$action}";
                }
            }

            $role->syncPermissions($names);
        }
    }
}
