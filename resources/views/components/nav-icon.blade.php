@props(['name', 'class' => 'h-5 w-5'])

@php
    $paths = match ($name) {
        'home' => '<path d="M3 10.5 12 3l9 7.5" /><path d="M5 9.5V20a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1V9.5" />',
        'academic-cap' => '<path d="M12 4 22 9l-10 5L2 9l10-5Z" /><path d="M6 11.5V16c0 1.7 2.7 3 6 3s6-1.3 6-3v-4.5" /><path d="M22 9v6" />',
        'book-open' => '<path d="M4 6.2c2.8-1.4 5.5-1.4 8 0v13c-2.5-1.4-5.2-1.4-8 0V6.2Z" /><path d="M20 6.2c-2.8-1.4-5.5-1.4-8 0v13c2.5-1.4 5.2-1.4 8 0V6.2Z" />',
        'calendar' => '<rect x="3.5" y="5" width="17" height="15.5" rx="2" /><path d="M3.5 9.5h17" /><path d="M8 3v4M16 3v4" />',
        'clipboard-list' => '<rect x="5.5" y="4.5" width="13" height="16" rx="2" /><rect x="9" y="3" width="6" height="3" rx="1" /><path d="M8.5 11h7M8.5 14.5h7M8.5 18h4" />',
        'clipboard-check' => '<rect x="5.5" y="4.5" width="13" height="16" rx="2" /><rect x="9" y="3" width="6" height="3" rx="1" /><path d="M9 13.5l2 2 4-4.5" />',
        'user-check' => '<circle cx="10" cy="8.5" r="3.2" /><path d="M4.5 20c.6-3.4 3-5.3 5.5-5.3s4.9 1.9 5.5 5.3" /><path d="M16.5 12.5l1.5 1.5 3-3" />',
        'user' => '<circle cx="12" cy="8.2" r="3.3" /><path d="M5.5 20c.7-3.7 3.4-5.8 6.5-5.8s5.8 2.1 6.5 5.8" />',
        'users' => '<circle cx="9" cy="8.5" r="3" /><path d="M3.5 20c.5-3.2 2.7-5 5.5-5s5 1.8 5.5 5" /><circle cx="17" cy="9" r="2.4" /><path d="M15.8 12.2c2 .3 3.4 1.8 3.7 3.8" />',
        'bell' => '<path d="M6 17V11a6 6 0 0 1 12 0v6l1.5 2.2H4.5L6 17Z" /><path d="M10 20a2 2 0 0 0 4 0" />',
        'cog' => '<path d="M4 8h16M4 12h16M4 16h16" /><circle cx="8" cy="8" r="1.6" /><circle cx="15" cy="12" r="1.6" /><circle cx="10" cy="16" r="1.6" />',
        'currency' => '<circle cx="12" cy="12" r="8.5" /><path d="M12 7.5v9M9.5 9.7c0-1.2 1.1-2.2 2.5-2.2s2.5 1 2.5 2c0 2.5-5 1.7-5 4.2 0 1 1.1 2 2.5 2s2.5-1 2.5-2.2" />',
        'folder' => '<path d="M3.5 7a2 2 0 0 1 2-2h3.6l1.8 2H18a2 2 0 0 1 2 2v8.5a2 2 0 0 1-2 2H5.5a2 2 0 0 1-2-2V7Z" />',
        'newspaper' => '<rect x="3.5" y="5" width="17" height="14" rx="1.5" /><path d="M7 9h4M7 12h7M7 15h7" /><rect x="14.2" y="8.7" width="3" height="2.6" rx="0.5" />',
        'shield-check' => '<path d="M12 3.5 19 6.3v5.4c0 4.3-3 7.6-7 9-4-1.4-7-4.7-7-9V6.3L12 3.5Z" /><path d="M9 12l2 2 4-4.5" />',
        'chart-bar' => '<path d="M4 20V13M10 20V8M16 20v-6M20 20H4" />',
        'inbox' => '<path d="M4 12.5h4.2l1.4 2.5h4.8l1.4-2.5H20" /><path d="M4 12.5 5.6 5a1.5 1.5 0 0 1 1.5-1.2h9.8A1.5 1.5 0 0 1 18.4 5L20 12.5v5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 17.5v-5Z" />',
        'document-plus' => '<path d="M8 3.5h6l4 4V19a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 6 19V5A1.5 1.5 0 0 1 8 3.5Z" /><path d="M14 3.5V8h4" /><path d="M12 11.5v5M9.5 14h5" />',
        'rectangle-group' => '<rect x="3.5" y="3.5" width="8" height="8" rx="1.4" /><rect x="12.5" y="3.5" width="8" height="8" rx="1.4" /><rect x="3.5" y="12.5" width="8" height="8" rx="1.4" /><rect x="12.5" y="12.5" width="8" height="8" rx="1.4" />',
        'bookmark' => '<path d="M6.5 4h11a1 1 0 0 1 1 1v15l-6.5-4-6.5 4V5a1 1 0 0 1 1-1Z" />',
        default => '<circle cx="12" cy="12" r="8.5" />',
    };
@endphp

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
     stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="{{ $class }}">
    {!! $paths !!}
</svg>
