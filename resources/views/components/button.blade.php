@props([
    'variant' => 'primary', 
    'size' => 'md', 
    'type' => 'button', 
    'href' => null,
    'target' => null,
    'disabled' => false
])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-colors duration-150 focus:outline-none focus:shadow-outline';

$variants = [
    'primary' => 'text-white bg-green-600 border border-transparent hover:bg-green-700 focus:shadow-outline-green active:bg-green-600 disabled:opacity-50 disabled:cursor-not-allowed',
    'secondary' => 'text-gray-700 bg-gray-200 border border-gray-300 hover:bg-gray-300 focus:shadow-outline-gray active:bg-gray-200 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed',
    'danger' => 'text-white bg-red-600 border border-transparent hover:bg-red-700 focus:shadow-outline-red active:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed',
    'warning' => 'text-white bg-yellow-600 border border-transparent hover:bg-yellow-700 focus:shadow-outline-yellow active:bg-yellow-600 disabled:opacity-50 disabled:cursor-not-allowed',
    'info' => 'text-white bg-blue-600 border border-transparent hover:bg-blue-700 focus:shadow-outline-blue active:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed',
    'success' => 'text-white bg-green-600 border border-transparent hover:bg-green-700 focus:shadow-outline-green active:bg-green-600 disabled:opacity-50 disabled:cursor-not-allowed',
    'outline' => 'text-gray-700 bg-transparent border border-gray-300 hover:bg-gray-50 focus:shadow-outline-gray active:bg-gray-100 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed',
];

$sizes = [
    'xs' => 'px-2 py-1 text-xs',
    'sm' => 'px-3 py-2 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
    'xl' => 'px-8 py-4 text-lg',
];

$variantClass = $variants[$variant] ?? $variants['primary'];
$sizeClass = $sizes[$size] ?? $sizes['md'];

$classes = "{$baseClasses} {$variantClass} {$sizeClass}";

// Remove href from attributes to avoid duplication
$attributes = $attributes->except(['href', 'variant', 'size', 'type', 'target', 'disabled']);
@endphp

@if($href)
    <a href="{{ $href }}" 
       @if($target) target="{{ $target }}" @endif
       {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" 
            @if($disabled) disabled @endif
            {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
