<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Crear nuevo movimiento
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Nombre" class="mt-3" />
                <x-jet-input wire:model.defer="name" type="text" class="w-full mt-2" />
                <x-jet-input-error for="name" class="mt-3" />
            </div>

            <x-jet-label value="Tipo" class="mt-3" />
            <x-select class="w-1/2 mt-2" wire:model.defer="movement">
                <option value="1">Entrada</option>
                <option value="0">Salida</option>
            </x-select>
            <x-jet-input-error for="movement" class="mt-3" />

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