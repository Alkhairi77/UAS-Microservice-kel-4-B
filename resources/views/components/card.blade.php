@props(['padding' => true])

<div {{ $attributes->merge([
    'class' => 'bg-white rounded-lg shadow-xs dark:bg-gray-800' . ($padding ? ' p-4' : '')
]) }}>
    {{ $slot }}
</div>
