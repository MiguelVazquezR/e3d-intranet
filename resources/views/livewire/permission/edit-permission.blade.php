<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Editar permiso
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Nombre del permiso" class="mt-3" />
                <x-jet-input wire:model.defer="permission.name" type="text" class="w-full mt-2" />
                <x-jet-input-error for="permission.name" class="text-xs" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="update" wire:loading.attr="disabled" wire:target="update" class="disabled:opacity-25">
                Actualizar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>