<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Crear nueva unidad de medida
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Nombre (en plural)" class="mt-3" />
                <x-jet-input wire:model.defer="name" type="text" class="w-full mt-2" />
                <x-jet-input-error for="name" class="text-xs" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="store" wire:loading.attr="disabled" wire:target="store" class="disabled:opacity-25">
                Crear unidad
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>