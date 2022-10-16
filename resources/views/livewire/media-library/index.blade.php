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
            <span class="ml-6 font-semibold">{{ $this->currentPath }}</span>
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

        <div class="lg:grid grid-cols-4 gap-3">
            @foreach ($resources as $resource)
                @php
                    $next_folder = $resource->nextFolder($current_path);
                    $all_media = $next_folder ? $resource->getFirstMedia($current_path . '/' . $next_folder) : $resource->getMedia($current_path);
                @endphp
                @if ($next_folder)
                    <x-media-file :next-folder="$next_folder" :media="$all_media" />
                @else
                    @foreach ($all_media as $media)
                        <x-media-file :next-folder="$next_folder" :media="$media" />
                    @endforeach
                @endif
            @endforeach
        </div>
    </div>

    {{-- components --}}
    @livewire('media-library.upload')
</div>
