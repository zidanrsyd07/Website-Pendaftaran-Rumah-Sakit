@php
/**
 * Renders an SVG icon based on name.
 * Usage: @include('components.icon', ['name' => 'stethoscope'])
 */
$icons = [
    'stethoscope' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3v2.25M14.25 3v2.25M9.75 5.25a3 3 0 00-3 3v3a6 6 0 0012 0v-3a3 3 0 00-3-3M9.75 5.25h4.5M18 14.25a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5zm0 0V12"/>',
    'tooth' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-2 0-3.5.5-4.5 1.5S6 6.5 6 8.5c0 1.5.5 2.5 1 4s1 3.5 1 5c0 1.5.5 2.5 1.5 3.5.5.5 1 .5 1.5 0 .5-1 1-2 1-3.5s.5-2 1-3c.5 1 1 1.5 1 3s.5 2.5 1 3.5c.5.5 1 .5 1.5 0C17.5 20 18 19 18 17.5c0-1.5.5-3.5 1-5s1-2.5 1-4c0-2-.5-3.5-1.5-4.5S14 3 12 3z"/>',
    'baby' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8a3 3 0 100-6 3 3 0 000 6zm-4.5 6.5a4.5 4.5 0 019 0V18a3 3 0 01-3 3h-3a3 3 0 01-3-3v-3.5z"/>',
    'pregnant' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6a2.5 2.5 0 100-5 2.5 2.5 0 000 5zm-2 2c-2 0-3.5 1.5-3.5 3.5 0 1.5.5 3 1 4.5.3.8.5 1.5.5 2.5 0 1.5.5 3 2 4h4c1.5-1 2-2.5 2-4 0-1-.2-1.7-.5-2.5-.5-1.5-1-3-1-4.5C14.5 9.5 13 8 11 8h-1z"/>',
    'heart-pulse' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 12h4l2-3 2 6 2-3h8"/>',
    'user' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>',
    'check' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>',
    'chevron-right' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>',
    'calendar' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>',
    'clipboard' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>',
    'queue' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/>',
    'search' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>',
    'refresh' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/>',
    'empty' => '<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>',
];

$path = $icons[$name] ?? $icons['user'];
$size = $size ?? 24;
@endphp
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="{{ $strokeWidth ?? 1.5 }}" stroke="currentColor" width="{{ $size }}" height="{{ $size }}" aria-hidden="true">
    {!! $path !!}
</svg>
