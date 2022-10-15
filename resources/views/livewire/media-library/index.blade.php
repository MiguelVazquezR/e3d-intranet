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

    <div class="py-6 px-12">
        <div wire:loading wire:target="edit,show">
            <x-loading-indicator />
        </div>
        
        <div class="flex items-center">
            <i class="fas fa-folder mr-2 text-3xl text-blue-300"></i>
            <span>Nueva carpeta</span>
        </div>
    </div>

</div>
