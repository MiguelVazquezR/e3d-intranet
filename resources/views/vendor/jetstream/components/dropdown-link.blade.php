@props(['link' => true])

@if($link)
<a {{ $attributes->merge(['class' => 'block px-4 py-2 text-sm leading-5 dark:hover:bg-gray-600 dark:text-gray-400 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition']) }}>{{ $slot }}</a>
@else
<button {{ $attributes->merge(['class' => 'block px-4 py-2 text-sm leading-5 dark:hover:bg-gray-600 dark:text-gray-400 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition w-full']) }}>{{ $slot }}</button>
@endif
