<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    @can('crear_permisos')
    <x-jet-button wire:click="openModal">
        + nuevo
    </x-jet-button>
    @endcan

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Crear permiso
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Nombre del permiso" class="mt-3" />
                <x-jet-input wire:model.defer="name" type="text" class="w-full mt-2" />
                <x-jet-input-error for="name" class="mt-3" />
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