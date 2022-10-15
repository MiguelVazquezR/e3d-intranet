<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-button wire:click="openModal">
        + Subir
    </x-jet-button>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Subir medios
        </x-slot>

        <x-slot name="content">
            <div class="mt-3">
                <x-jet-label value="Archivo(s)" />
                <input wire:model.defer="files" type="file" class="w-1/2" multiple />
                <x-jet-input-error for="files" class="text-xs" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="store" wire:loading.attr="disabled" wire:target="store"
                class="disabled:opacity-25">
                Subir
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>
