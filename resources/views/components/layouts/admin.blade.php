@props(['title' => 'Administration'])

@php
    $roleLabels = [
        'super-admin' => 'Super Admin',
        'directeur' => 'Directeur',
        'administrateur' => 'Administrateur',
        'responsable-academique' => 'Resp. Académique',
        'scolarite' => 'Scolarité',
        'comptable' => 'Comptable',
    ];
    $currentRoleLabel = collect($roleLabels)->first(fn ($label, $role) => auth()->user()?->hasRole($role)) ?? 'Administration';
@endphp

<x-portal-shell :title="$title" :role-label="$currentRoleLabel" :nav="[
    ['label' => 'Tableau de bord', 'route' => 'admin.dashboard', 'icon' => 'home'],
    ['label' => 'Candidatures', 'route' => 'admin.applications.index', 'icon' => 'inbox'],
    ['label' => 'Étudiants', 'route' => 'admin.students.index', 'icon' => 'academic-cap'],
    ['label' => 'Enseignants', 'route' => 'admin.teachers.index', 'icon' => 'users'],
    ['label' => 'Formations', 'route' => 'admin.formations.index', 'icon' => 'book-open'],
    ['label' => 'Matières', 'route' => 'admin.subjects.index', 'icon' => 'bookmark'],
    ['label' => 'Classes', 'route' => 'admin.classes.index', 'icon' => 'rectangle-group'],
    ['label' => 'Bulletins', 'route' => 'admin.report-cards.index', 'icon' => 'clipboard-check'],
    ['label' => 'Finance', 'route' => 'admin.finance.index', 'icon' => 'currency'],
    ['label' => 'Documents', 'route' => 'admin.documents.index', 'icon' => 'folder'],
    ['label' => 'Actualités & Galerie', 'route' => 'admin.communication.index', 'icon' => 'newspaper'],
    ['label' => 'Utilisateurs & Rôles', 'route' => 'admin.users.index', 'icon' => 'shield-check'],
    ['label' => 'Paramètres', 'route' => 'admin.settings.index', 'icon' => 'cog'],
]">
    {{ $slot }}
</x-portal-shell>
