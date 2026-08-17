@props(['title' => 'Espace candidat'])

<x-portal-shell :title="$title" :nav="[
    ['label' => 'Mes candidatures', 'route' => 'candidate.dashboard'],
    ['label' => 'Nouvelle candidature', 'route' => 'candidate.applications.create'],
]">
    {{ $slot }}
</x-portal-shell>
