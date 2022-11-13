<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Solicitar modificaciones
        </x-slot>

        <x-slot name="content">
            <div class="mt-3">
                <x-jet-label class="dark:text-gray-400" value="Modificaciones" />
                <textarea wire:model.defer="modifications" rows="5"
                    class="input !h-[6rem] w-full"></textarea>
                <x-jet-input-error for="modifications" class="text-xs" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="sendRequest" wire:loading.attr="disabled" wire:target="sendRequest"
                class="disabled:opacity-25">
                Enviar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>
