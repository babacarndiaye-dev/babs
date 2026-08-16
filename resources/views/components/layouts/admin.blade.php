@props(['title' => 'Administration'])

<x-portal-shell :title="$title" :nav="[
    ['label' => 'Tableau de bord', 'route' => 'admin.dashboard'],
    ['label' => 'Candidatures', 'route' => 'admin.applications.index'],
    ['label' => 'Étudiants', 'route' => 'admin.students.index'],
    ['label' => 'Enseignants', 'route' => 'admin.teachers.index'],
    ['label' => 'Formations', 'route' => 'admin.formations.index'],
    ['label' => 'Classes', 'route' => 'admin.classes.index'],
    ['label' => 'Finance', 'route' => 'admin.finance.index'],
    ['label' => 'Actualités & Galerie', 'route' => 'admin.communication.index'],
    ['label' => 'Utilisateurs & Rôles', 'route' => 'admin.users.index'],
    ['label' => 'Paramètres', 'route' => 'admin.settings.index'],
]">
    {{ $slot }}
</x-portal-shell>
