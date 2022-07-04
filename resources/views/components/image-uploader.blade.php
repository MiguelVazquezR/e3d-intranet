<div {{$attributes}}>
    @if($show_alerts)
    <div x-data="{open: true}" x-show="open" class="flex justify-between bg-green-100 rounded-lg p-4 mb-4 text-sm font-medium text-green-700" role="alert">
        <div class="w-11/12 flex">
            <i class="fas fa-exclamation-circle w-5 h-5 inline mr-3"></i>
            <div>
                <span class="font-extrabold">Sin límite:</span>
                Ahora puedes subir cualquier imagen sin tener que reducir su tamaño antes.
                Esto permitirá también poder subir fotos directamente de una captura hecha con el móvil.
            </div>
        </div>

        <i @click="open = false" class="fal fa-times text-right hover:cursor-pointer"></i>
    </div>
    @endif

    <div wire:loading wire:target="{{ $model }}" class="w-full flex items-center bg-red-100 rounded-lg p-4 mb-4 text-sm text-red-700 font-medium" role="alert">
        <i class="fas fa-exclamation-circle w-5 h-5 inline mr-3"></i>
        <div>
            <span class="font-extrabold">Imagen cargando:</span> Espere un momento por favor, la imagen está procesandose.
        </div>
    </div>

    <div class="lg:grid lg:grid-cols-2 lg:gap-2">
        @if($image)
        @if(in_array($image->extension(), $image_extensions))
        <img class="rounded-2xl" src="{{ $image->temporaryUrl() }}">
        @else
        <div class="rounded-2xl bg-red-100 text-red-700 flex justify-center items-center p-4">
            <p class="text-sm">El archivo seleccionado no es una imagen</p>
        </div>
        @endif
        @elseif($registered_image)
        <img class="rounded-2xl" src="{{ Storage::url($registered_image) }}">
        @else
        @endif
        <div>
            <x-jet-label value="{{$label}}" class="mt-3" />
            <input wire:model.defer="{{ $model }}" type="file" class="text-sm mt-2" id="{{ $image_id }}">
            <x-jet-input-error for="{{ $model }}" class="mt-3" />
        </div>
    </div>
</div>