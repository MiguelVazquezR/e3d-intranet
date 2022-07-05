@props(['options', 'label', 'model'])

<div class="flex border rounded-full overflow-hidden m-4 text-xs">
    <div class="py-2 my-auto px-5 bg-blue-500 text-white font-semibold mr-3">
        {{ $label }}
    </div>
    @foreach ($options as $i => $option)
        <label class="flex items-center radio p-2 cursor-pointer">
            <input wire:model="{{ $model }}" value="{{ $i }}" class="my-auto" type="radio"
                name="{{ $label }}" />
            <div class="px-2">{{$option}}</div>
        </label>
    @endforeach
</div>
