@props(['active'])

@php
$classes = ($active ?? false)
? 'inline-flex items-center px-1 pt-1 font-medium leading-5 text-secondary focus:outline-none transition duration-150 ease-in-out cursor-pointer'
: 'inline-flex items-center px-1 pt-1 font-medium leading-5 dark:text-gray-50 text-gray-800 hover:text-secondary focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out cursor-pointer';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>