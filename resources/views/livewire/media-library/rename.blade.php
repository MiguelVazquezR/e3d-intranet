<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
           Renombrar
        </x-slot>

        <x-slot name="content">
            <div class="mt-3">
                <x-jet-label value="Nombre" />
                <x-jet-input wire:model.defer="name" type="text" class="w-full" />
            </div>
            <x-jet-input-error for="name" class="text-xs" />
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="rename" wire:loading.attr="disabled" wire:target="rename"
                class="disabled:opacity-25">
                Aceptar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>
