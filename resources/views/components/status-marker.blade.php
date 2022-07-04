@props(['color' => 'green'])

<span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
    <span aria-hidden class="absolute inset-0 bg-{{ $color }}-200 opacity-50 rounded-full"></span>
    <span class="relative">
        {{$slot}} {{$color}} 
    </span>
</span>