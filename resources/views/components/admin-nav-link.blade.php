@props([
    'active' => false,
])

@php
    $classes = $active
        ? 'flex items-center gap-3 rounded-lg bg-blue-50 px-3 py-2.5 text-sm font-semibold text-blue-700'
        : 'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-white transition hover:bg-blue-600 hover:text-white hover:shadow-md';
@endphp

<a
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $slot }}
</a>