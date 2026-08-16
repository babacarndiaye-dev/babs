@props(['title' => 'Espace étudiant'])

<x-portal-shell :title="$title" :nav="[
    ['label' => 'Tableau de bord', 'route' => 'student.dashboard'],
    ['label' => 'Mon profil', 'route' => 'student.profile'],
    ['label' => 'Ma formation', 'route' => 'student.formation'],
    ['label' => 'Emploi du temps', 'route' => 'student.schedule'],
    ['label' => 'Mes notes', 'route' => 'student.grades'],
    ['label' => 'Mes absences', 'route' => 'student.attendances'],
    ['label' => 'Mes paiements', 'route' => 'student.payments'],
    ['label' => 'Mes documents', 'route' => 'student.documents'],
]">
    {{ $slot }}
</x-portal-shell>
