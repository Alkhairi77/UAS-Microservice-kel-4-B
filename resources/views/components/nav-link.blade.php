@props(['active'])
<a class="nav-link {{ $active ? 'active' : '' }}" {{ $attributes }}>{{ $slot }}</a>