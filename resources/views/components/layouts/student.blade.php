@props(['title' => 'Espace étudiant'])

<x-portal-shell :title="$title" role-label="Étudiant" :nav="[
    ['label' => 'Tableau de bord', 'route' => 'student.dashboard', 'icon' => 'home'],
    ['label' => 'Mon profil', 'route' => 'student.profile', 'icon' => 'user'],
    ['label' => 'Ma formation', 'route' => 'student.formation', 'icon' => 'academic-cap'],
    ['label' => 'Emploi du temps', 'route' => 'student.schedule', 'icon' => 'calendar'],
    ['label' => 'Mes notes', 'route' => 'student.grades', 'icon' => 'chart-bar'],
    ['label' => 'Mes absences', 'route' => 'student.attendances', 'icon' => 'user-check'],
    ['label' => 'Mes paiements', 'route' => 'student.payments', 'icon' => 'currency'],
    ['label' => 'Mes documents', 'route' => 'student.documents', 'icon' => 'folder'],
]">
    {{ $slot }}
</x-portal-shell>
