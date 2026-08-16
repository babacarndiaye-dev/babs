@props(['title' => 'Espace enseignant'])

<x-portal-shell :title="$title" :nav="[
    ['label' => 'Tableau de bord', 'route' => 'teacher.dashboard'],
    ['label' => 'Mes classes', 'route' => 'teacher.classes.index'],
    ['label' => 'Emploi du temps', 'route' => 'teacher.schedule'],
    ['label' => 'Présences', 'route' => 'teacher.attendances.index'],
    ['label' => 'Évaluations & Notes', 'route' => 'teacher.assessments.index'],
]">
    {{ $slot }}
</x-portal-shell>
