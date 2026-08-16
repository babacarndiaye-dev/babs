<!DOCTYPE html>
<html lang="fr" style="
    --brand-primary: {{ setting('design.color_primary', '#0F5132') }};
    --brand-secondary: {{ setting('design.color_secondary', '#0B3D26') }};
    --brand-accent: {{ setting('design.color_accent', '#E8772E') }};
    --brand-surface: {{ setting('design.color_surface', '#F7F7F5') }};
    --brand-text: {{ setting('design.color_text', '#1F2421') }};
">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? setting('identity.name', 'École') }}</title>
    <meta name="description" content="{{ setting('identity.slogan', '') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-surface text-ink antialiased">
    {{ $slot }}

    @livewireScripts
</body>
</html>
