<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    @can('crear_actualizaciones')
    <x-jet-button wire:click="openModal">
       + nuevo
    </x-jet-button>
    @endcan

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Agregar actualización
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="título" class="mt-3" />
                <x-jet-input wire:model.defer="title" type="text" class="w-full mt-2" />
                <x-jet-input-error for="title" class="mt-1" />
            </div>
            <div>
                <x-jet-label value="Descripción" class="mt-3" />
                <textarea wire:model.defer="description" rows="2" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
                <x-jet-input-error for="description" class="mt-1" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="store" wire:loading.attr="disabled" wire:target="store" class="disabled:opacity-25">
                Crear
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>