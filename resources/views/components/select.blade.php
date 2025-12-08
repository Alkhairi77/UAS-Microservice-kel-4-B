@props(['name', 'options' => [], 'value' => '', 'placeholder' => null, 'multiple' => false, 'disabled' => false])

<select 
    name="{{ $name }}{{ $multiple ? '[]' : '' }}" 
    id="{{ $name }}"
    {{ $multiple ? 'multiple' : '' }}
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge([
        'class' => 'block w-full mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-green-400 focus:ring focus:ring-green-400 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-green-400 dark:focus:ring-green-400'
    ]) }}
>
    @if($placeholder && !$multiple)
        <option value="">{{ $placeholder }}</option>
    @endif
    
    <!-- TAMBAH: Support untuk slot content -->
    @if($slot->isNotEmpty())
        {{ $slot }}
    @else
        @foreach($options as $key => $label)
            <option value="{{ $key }}" 
                    @if($multiple)
                        {{ in_array($key, (array)$value) ? 'selected' : '' }}
                    @else
                        {{ $key == $value ? 'selected' : '' }}
                    @endif>
                {{ $label }}
            </option>
        @endforeach
    @endif
</select>