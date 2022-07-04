<div>
    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            Actualizar precio de <span class="font-bold text-sky-500">{{$composit_product->alias}}</span>
        </x-slot>

        <x-slot name="content">
            @if( $composit_product->id )
            <p class="mt-1 text-gray-500">Precio actual: <span class="text-green-600">{{ $composit_product->new_price . ' ' . $composit_product->new_price_currency }}</span> </p>
            <p class="mt-1 text-gray-500">Establecido: <span class="text-sky-600">{{ $composit_product->new_date->diffForHumans() }}</span> </p>
            @endif

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <x-jet-label value="Precio nuevo" class="mt-3" />
                    <x-jet-input wire:model.defer="new_price" type="number" class="w-full mt-2" />
                    <x-jet-input-error for="new_price" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Moneda" class="mt-3" />
                    <x-select class="mt-2 w-full" wire:model.defer="new_price_currency">
                        @forelse($currencies as $currency)
                        <option value="{{ $currency->name }}">{{ $currency->name }}</option>
                        @empty
                        <option value="">No hay ninguna moneda registrada</option>
                        @endforelse
                    </x-select>
                    <x-jet-input-error for="new_price_currency" class="mt-3" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer" class="mt-8">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="update" wire:loading.attr="disabled" wire:target="update" class="disabled:opacity-25">
                Actualizar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>