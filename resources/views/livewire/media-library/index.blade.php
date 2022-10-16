<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
            <div class="flex items-center">
                <i class="fas fa-photo-video mr-2"></i>
                Biblioteca de medios
            </div>
            @livewire('media-library.upload')
        </h2>
    </x-slot>

    <div class="py-4 px-12">
        <div wire:loading wire:target="edit,show">
            <x-loading-indicator />
        </div>

        <div class="mb-5">
            @if ($current_path !== 'ML-index')
                <button wire:click="back" class="text-blue-500 flex items-center">
                    <i class="fas fa-long-arrow-alt-left text-lg mr-3"></i>
                    atrás
                </button>                
            @endif
        </div>

        <div class="lg:grid grid-cols-4 gap-3">
            @foreach ($resources as $resource)
                @php
                    $next_folder = $resource->nextFolder($current_path);
                @endphp
                @foreach ($resource->getMedia($current_path) as $media)
                    <x-media-file :next-folder="$next_folder" :media="$media" />
                @endforeach
            @endforeach
        </div>
    </div>

</div>

