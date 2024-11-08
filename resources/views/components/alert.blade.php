@props(['type'])

@php
    $alertClasses = [
        'success' => 'bg-green-100 text-green-700',
        'error' => 'bg-red-100 text-red-700',
        'warning' => 'bg-yellow-100 text-yellow-700',
        'info' => 'bg-blue-100 text-blue-700',
    ];
@endphp

<div {{ $attributes->merge(['class' => $alertClasses[$type] . ' p-4 rounded mb-4']) }}>
    {{ $slot }}
</div>
