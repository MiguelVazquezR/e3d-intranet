<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
            <div class="flex items-center">
                <i class="fas fa-photo-video mr-2"></i>
                Biblioteca de medios
            </div>
        </h2>
    </x-slot>


    <div class="flex justify-between px-5 py-2">
        <div>
            @if ($current_path !== 'ML-index')
                <button wire:click="back" class="text-blue-500 ">
                    <i class="fas fa-long-arrow-alt-left text-lg mr-2"></i>
                    atrás
                </button>
            @endif
            <span class="ml-6 font-semibold"><i class="fas fa-home" title="Inicio"></i> {{ $this->currentPath }}</span>
        </div>
        <div>
            <x-jet-button wire:click="openUploadModal">
                + Subir
            </x-jet-button>
        </div>
    </div>
    <div class="py-4 px-12">
        <div wire:loading wire:target="edit,show,openUploadModal">
            <x-loading-indicator />
        </div>
        <div wire:loading.remove wire:target="back,openFolder" class="lg:grid grid-cols-4 gap-3">
            @foreach ($resources as $resource)
                @php
                    $next_folder = $resource->nextFolder($current_path);
                    $all_media = $next_folder ? $resource->getFirstMedia($current_path . '/' . $next_folder) : $resource->getMedia($current_path);
                @endphp
                @if ($next_folder)
                    {{-- <x-media-file :next-folder="$next_folder" :media="$all_media" /> --}}
                    <div class="flex items-center">
                        <i wire:click="openFolder('{{ $next_folder }}')"
                            class="fas fa-folder mr-2 text-3xl text-blue-300 cursor-pointer"></i>
                        <span wire:click="openFolder('{{ $next_folder }}')"
                            class="text-gray-700 text-xs cursor-pointer">{{ $next_folder }}</span>
                        <i wire:click="$emit('confirm', {0:'media-library.index', 1:'deleteFolder' ,2:'{{ $next_folder }}', 3:'Se eliminará todo el contenido de la carpeta. Este proceso no se puede revertir'})"
                            class="fas fa-trash-alt ml-1 text-gray-400 hover:text-red-400 text-[11px] cursor-pointer"></i>
                    </div>
                @else
                    @foreach ($all_media as $media)
                        {{-- <x-media-file :next-folder="$next_folder" :media="$media" /> --}}
                        <div>
                            <a href="{{ $media->getUrl() }}" target="_blank" class="cursor-pointer">
                                <i class="fas fa-file text-gray-400 text-2xl mr-2"></i>
                                <span class="text-gray-700 text-xs">{{ $media->name }}</span>
                            </a>
                            <i wire:click="$emit('confirm', {0:'media-library.index', 1:'deleteFile' ,2:[{{ $resource }},{{ $media->id }}], 3:'Este proceso no se puede revertir'})"
                                class="fas fa-trash-alt ml-1 text-gray-400 hover:text-red-400 text-[11px] cursor-pointer"></i>
                        </div>
                    @endforeach
                @endif
            @endforeach
            @if (!$resources->count())
              <div class="text-sm text-gray-500 text-center p-8 col-span-full">No hay recursos para mostrar</div>
            @endif
          </div>
        <div wire:loading wire:target="back,openFolder" class="p-8 flex justify-center items-center"><i
                class="fas fa-circle-notch animate-spin text-4xl text-gray-500"></i></div>
    </div>

    {{-- components --}}
    @livewire('media-library.upload')
</div>
