<div>
    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            Conversión de unidades
        </x-slot>

        <x-slot name="content">
            @php
                $products = $purchase_order->purchaseOrderedProducts;
            @endphp
            @foreach ($products as $i => $pop)
                <div class="flex justify-between items-center text-xs">
                    <span>1 Unidad recibida de <u>{{ $pop->product->name }}</u> => </span>
                    <div class="flex items-center w-1/2">
                        <x-jet-input wire:model="convertions.{{$i}}" type="number" class="w-1/3 mr-2" />
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
