<div>
    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            Conversión de unidades
        </x-slot>

        <x-slot name="content">
            <!-- banner -->
            <div x-data="{ open: true }" x-show="open"
                class="w-full flex justify-between mx-auto dark:bg-blue-300 dark:text-blue-900 bg-blue-100 rounded-lg p-4 my-6 text-sm font-medium text-blue-700"
                role="alert">
                <div class="flex">
                    <i class="fas fa-exclamation-circle w-5 h-5 inline mr-3"></i>
                    <div>
                        Esta conversión es necesaria para poder manejar diferentes unidades de medida con proveedores y 
                        para el seguimiento de inventario interno.
                    </div>
                </div>

                <i @click="open = false" class="fal fa-times text-right hover:cursor-pointer"></i>
            </div>

            @php
                $products = $purchase_order->purchaseOrderedProducts;
            @endphp
            @foreach ($products as $i => $pop)
                <div class="flex justify-between items-center text-xs mt-1">
                    <span>1 Unidad recibida de <u>{{ $pop->product->name }}</u> => </span>
                    <div class="flex items-center w-1/2">
                        <x-jet-input wire:model="convertions.{{ $i }}" type="number" class="w-1/3 mr-2" />
                        {{ $pop->product->unit->name }}
                    </div>
                </div>
            @endforeach
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cerrar
            </x-jet-secondary-button>
            <x-jet-button wire:click="receive" wire:loading.attr="disabled" wire:target="receive"
                class="disabled:opacity-25">
                Cargar a almacén
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
