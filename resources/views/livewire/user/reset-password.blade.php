<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Resetear contraseña
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Nueva contraseña" class="mt-3" />
                <x-jet-input wire:model="password" type="text" class="w-full mt-2" />
                <x-jet-input-error for="password" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>
            <x-jet-button wire:click="resetPassword" wire:loading.attr="disable" wire:target="resetPassword" class="disabled:opacity-25">
                Resetear
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>