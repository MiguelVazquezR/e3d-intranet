<div>
    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            Actualizar precio
        </x-slot>

        <x-slot name="content">
            @if ($product_for_sell)
                <p class="mt-1 text-gray-500">Precio actual: <span
                        class="text-green-600">{{ $product_for_sell->new_price . ' ' . $product_for_sell->new_price_currency }}</span>
                </p>
                <p class="mt-1 text-gray-500">Establecido: <span
                        class="text-sky-600">{{ $product_for_sell->new_date->diffForHumans() }}</span> </p>
            @endif

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <x-jet-label value="Precio nuevo" class="mt-3" />
                    <x-jet-input wire:model.defer="new_price" type="number" class="w-full mt-2" />
                    <x-jet-input-error for="new_price" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Moneda" class="mt-3" />
                    <x-select class="mt-2 w-full" wire:model.defer="new_price_currency" :options="$currencies"
                        id="name" />
                        <x-jet-input-error for="new_price_currency" class="text-xs" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer" class="mt-8">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="update" wire:loading.attr="disabled" wire:target="update"
                class="disabled:opacity-25">
                Actualizar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
