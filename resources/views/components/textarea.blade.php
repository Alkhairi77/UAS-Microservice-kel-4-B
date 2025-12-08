@props(['name', 'rows' => 3])

<textarea 
    name="{{ $name }}" 
    id="{{ $name }}"
    rows="{{ $rows }}"
    {{ $attributes->merge([
        'class' => 'block w-full mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-green-400 focus:ring focus:ring-green-400 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-green-400 dark:focus:ring-green-400'
    ]) }}
>{{ $slot }}</textarea>
