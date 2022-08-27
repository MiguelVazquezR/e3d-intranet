@props(['image'=>null, 'name'=>'', 'new_item'=>false, 'name_bolded'=>true, 'src' ])

<div {{$attributes}}>
    <div class="flex items-center text-sm">
        @if($image)
        <a href="{{$src}}" target="_blank" class="mr-2 hover:cursor-pointer">
            <img class="h-8 w-8 md:h-10 md:w-10 rounded-full" src="{{$image}}">
        </a>
        @endif
        <span class="{{$name_bolded ? 'font-bold' : ''}} mr-2">{{ $name }}</span>
        {{ $slot }}
        @if($new_item)
        <span class="bg-blue-500 p-1 ml-2 text-white rounded-lg text-xs">Nuevo</span>
        @endif
    </div>
</div>