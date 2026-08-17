@props(['title' => 'Espace candidat'])

<x-portal-shell :title="$title" role-label="Candidat" :nav="[
    ['label' => 'Mes candidatures', 'route' => 'candidate.dashboard', 'icon' => 'inbox'],
    ['label' => 'Nouvelle candidature', 'route' => 'candidate.applications.create', 'icon' => 'document-plus'],
]">
    {{ $slot }}
</x-portal-shell>
