<div>

    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            Paquetes para pedido OV-{{ str_pad($sell_order->id, 4, '0', STR_PAD_LEFT) }}
        </x-slot>

        <x-slot name="content">
            <x-jet-label value="Paquetes hechos" class="mt-3" />
            @forelse ($packaged_products as $s_o_p)
                @php
                    if ($s_o_p->productForSell->model_name == 'App\\Models\\' . Product::class) {
                        $product_name = App\Models\Product::find($s_o_p->productForSell->model_id)->name;
                    } else {
                        $product_name = App\Models\CompositProduct::find($s_o_p->productForSell->model_id)->alias;
                    }
                @endphp
                <div wire:click="editPackages( {{ $s_o_p }} )"
                    class="my-1 ml-2 text-xs text-blue-400 hover:cursor-pointer">{{ $product_name }}:
                    {{ $s_o_p->quantity }}
                    unidades ordenadas</div>
            @empty
                <h2 class="text-sm ml-2">No hay paquetes hechos para esta orden</h2>
            @endforelse

            <x-jet-label value="Paquetes para hacer (producto con tareas terminadas)" class="mt-3" />
            @forelse ($products_for_package as $s_o_p)
                @php
                    if ($s_o_p->productForSell->model_name == 'App\\Models\\' . Product::class) {
                        $product_name = App\Models\Product::find($s_o_p->productForSell->model_id)->name;
                    } else {
                        $product_name = App\Models\CompositProduct::find($s_o_p->productForSell->model_id)->alias;
                    }
                @endphp
                <div wire:click="createPackages( {{ $s_o_p }} )"
                    class="my-1 ml-2 text-xs text-blue-400 hover:cursor-pointer">{{ $product_name }}:
                    {{ $s_o_p->quantity }}
                    unidades ordenadas</div>
            @empty
                <h2 class="text-sm ml-2">No hay productos para hacer paquetes</h2>
            @endforelse
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>
            @if ($this->sell_order->status != 'Totalmente enviado' && $sell_order->id)
                @if ($this->sell_order->totallyPacked() && !$this->sell_order->parciallyShipped() )
                    @if ($loading)
                        <span class="text-gray-500 mt-2 text-xs">Generando envio...</span>
                    @else
                        <x-jet-button
                            wire:click="alert"
                            wire:loading.attr="disabled" wire:target="alert" class="disabled:opacity-25">
                            Envío completo
                        </x-jet-button>
                    @endif
                @elseif($this->sell_order->anyPacked())
                    <x-jet-button
                        wire:click="$emit('confirm', { 0:'shipping-package.shipping-packages', 1:'partialShipment', 2:'', 3:'A continuación deberás seleccionar los paquetes que se enviarán y se notificará por correo a {{ $sell_order->contact->email }} con los paquetes que se envían.' })"
                        wire:loading.attr="disabled" wire:target="partialShipment" class="disabled:opacity-25">
                        Envío parcial
                    </x-jet-button>
                @endif
            @endif
        </x-slot>
    </x-jet-dialog-modal>

</div>
