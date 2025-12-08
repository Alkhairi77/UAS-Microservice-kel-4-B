@props([
    'type' => 'text',
    'name' => null,
    'id' => null,
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'multiple' => false,
    'step' => null,
    'min' => null,
    'max' => null,
    'rows' => null
])

@php
    // Generate ID jika tidak ada
    $inputId = $id ?? $name ?? 'input_' . uniqid();
    
    // Base classes untuk semua input
    $baseClasses = 'block w-full text-sm shadow-sm focus:ring focus:ring-opacity-50 dark:text-gray-300';
    
    // Classes berdasarkan tipe
    if ($type === 'file') {
        $classes = $baseClasses . ' text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 dark:file:bg-green-900 dark:file:text-green-100';
    } else {
        $classes = $baseClasses . ' border-gray-300 rounded-md focus:border-green-400 focus:ring-green-400 dark:border-gray-600 dark:bg-gray-700 dark:focus:border-green-400 dark:focus:ring-green-400';
    }
    
    // Merge dengan classes dari attributes
    $classes = $attributes->get('class') ? $classes . ' ' . $attributes->get('class') : $classes;
@endphp

<input 
    type="{{ $type }}"
    @if($name) name="{{ $name }}{{ ($type === 'file' && $multiple) ? '[]' : '' }}" @endif
    @if($inputId) id="{{ $inputId }}" @endif
    @if($value !== null) value="{{ $value }}" @endif
    @if($placeholder) placeholder="{{ $placeholder }}" @endif
    @if($step) step="{{ $step }}" @endif
    @if($min !== null) min="{{ $min }}" @endif
    @if($max !== null) max="{{ $max }}" @endif
    @if($required) required @endif
    @if($disabled) disabled @endif
    @if($readonly) readonly @endif
    @if($multiple && $type === 'file') multiple @endif
    {{ $attributes->except(['class'])->merge(['class' => $classes]) }}
/>
