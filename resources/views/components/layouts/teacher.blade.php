@props(['title' => 'Espace enseignant'])

<x-portal-shell :title="$title" role-label="Enseignant" :nav="[
    ['label' => 'Tableau de bord', 'route' => 'teacher.dashboard', 'icon' => 'home'],
    ['label' => 'Mes classes', 'route' => 'teacher.classes.index', 'icon' => 'academic-cap'],
    ['label' => 'Emploi du temps', 'route' => 'teacher.schedule', 'icon' => 'calendar'],
    ['label' => 'Présences', 'route' => 'teacher.attendances.index', 'icon' => 'user-check'],
    ['label' => 'Évaluations & Notes', 'route' => 'teacher.assessments.index', 'icon' => 'clipboard-list'],
]">
    {{ $slot }}
</x-portal-shell>
