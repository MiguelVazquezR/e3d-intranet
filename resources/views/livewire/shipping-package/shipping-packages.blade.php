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

            
            <!-- banner -->
            <div x-data="{ open: true }" x-show="open"
                class="flex justify-between mx-auto bg-orange-100 rounded-lg p-4 mt-6 mb-2 text-sm font-medium text-orange-700"
                role="alert">
                <div class="flex">
                    <i class="fas fa-exclamation-circle w-5 h-5 inline mr-3"></i>
                    <div>
                        Se notificará mediante correo electrónico a gerencia y opcionalmente puedes activar que se notifique al 
                        contacto relacionado con la OV. Asegurate de que el correo del contacto este correcto.
                    </div>
                </div>

                <i @click="open = false" class="fal fa-times text-right hover:cursor-pointer"></i>
            </div>  

            @if ($sell_order->contact)
                <div>
                    <div class="mt-4 text-sm" x-data="{ toggle: @entangle('notify_contact') }">
                        <x-jet-label
                            value="Notificar por correo al contacto relacionado: {{ $sell_order->contact->email }}"
                            class="my-2" />
                        <div @click="toggle == 0 ? toggle = 1 : toggle = 0"
                            class="relative rounded-full w-10 h-5 transition duration-300 ease-linear"
                            :class="[toggle == 1 ? 'bg-green-400' : 'bg-gray-300']">
                            <label for="toggle"
                                class="absolute left-0 bg-white border-2 mb-2 w-5 h-5 rounded-full transition transform duration-200 ease-linear cursor-pointer"
                                :class="[toggle == 1 ? 'translate-x-full border-green-400' : 'translate-x-0 border-gray-300']"></label>
                        </div>
                    </div>
                </div>
            @endif

        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>
            @if ($this->sell_order->status != 'Totalmente enviado' && $sell_order->id)
                @if ($this->sell_order->totallyPacked() && !$this->sell_order->parciallyShipped())
                    @if ($loading)
                        <span class="text-gray-500 mt-2 text-xs">Generando envio...</span>
                    @else
                        <x-jet-button wire:click="alert" wire:loading.attr="disabled" wire:target="alert"
                            class="disabled:opacity-25">
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
