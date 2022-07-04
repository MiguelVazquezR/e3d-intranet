<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Crear nuevo método de pago
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Clave" class="mt-3" />
                <x-jet-input wire:model.defer="key" type="text" class="w-full mt-2" />
                <x-jet-input-error for="key" class="mt-3" />
            </div>
            <div>
                <x-jet-label value="Descripción" class="mt-3" />
                <x-jet-input wire:model.defer="description" type="text" class="w-full mt-2" />
                <x-jet-input-error for="description" class="mt-3" />
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