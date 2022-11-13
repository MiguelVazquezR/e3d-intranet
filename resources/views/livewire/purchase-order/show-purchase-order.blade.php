<div>
    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            <!--tab component start-->
            <ul class="flex justify-center items-center pb-4" x-data="{
                activeTab: 0,
                tabs: [
                    'Datos de orden',
                    'Productos',
                ],
                activeTab: @entangle('active_tab')
            }">
                <template x-for="(tab, index) in tabs" :key="index">
                    <li class="cursor-pointer text-sm py-2 px-6 text-gray-500 border-b-2"
                        :class="activeTab === index ? 'text-black border-black dark:text-blue-700 dark:border-blue-700' : ''"
                        @click="activeTab = index; $dispatch('change-tab', index);" x-text="tab"></li>
                </template>
            </ul>
            <!--tab component end-->
        </x-slot>

        <x-slot name="content">
            <div x-data="{ activeTab: @entangle('active_tab') }" @change-tab.window="activeTab = $event.detail">
                <!-- Details -->
                @if ($purchase_order->id)
                    <div x-show="activeTab == 0">
                        <h3 class="text-center text-lg text-sky-800 tracking-widest font-bold my-2">Logística</h3>
                        <div class="lg:grid lg:grid-cols-2 lg:gap-2 font-bold">
                            <p>Paquetería: <span class="text-gray-500">{{ $purchase_order->shipping_company }}</span>
                            </p>
                            <p>Guía: <span class="text-gray-500">{{ $purchase_order->tracking_guide }}</span></p>
                            <p>fecha de recibido:
                                @if ($purchase_order->received_at)
                                    <span class="text-gray-500">{{ $purchase_order->received_at }}
                                    </span>
                                @else
                                    <span class="text-red-500"> Sin recibir aún
                                    </span>
                                @endif
                            </p>
                        </div>
                        @if ($purchase_order->supplier)
                            <h3 class="text-center text-lg text-sky-800 tracking-widest font-bold my-2">Datos del
                                proveedor
                            </h3>
                            <div class="lg:grid lg:grid-cols-2 lg:gap-2 font-bold">
                                <p>Nombre: <span class="text-gray-500">{{ $purchase_order->supplier->name }}</span>
                                </p>
                                <p class="col-span-2">Dirección: <span
                                        class="text-gray-500">{{ $purchase_order->supplier->address }} -
                                        C.P.{{ $purchase_order->supplier->post_code }}</span></p>
                                <p>Contacto:</p>
                                <div
                                    class="col-span-2 flex flex-col lg:flex-row items-center text-sm mb-1 lg:justify-center">
                                    <div>
                                        <i class="fas fa-user-circle mr-1"></i><span
                                            class="mr-2">{{ $purchase_order->contact->name }}</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-envelope mr-1"></i><span
                                            class="mr-2">{{ $purchase_order->contact->email }}</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-phone-alt mr-1"></i><span
                                            class="mr-2">{{ $purchase_order->contact->phone }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <h3 class="text-center text-lg text-sky-800 tracking-widest font-bold my-2">Datos de la orden
                        </h3>
                        <div class="lg:grid lg:grid-cols-2 lg:gap-2 font-bold">
                            <p>Solicitada por: <span
                                    class="text-gray-500">{{ $purchase_order->creator->name }}</span>
                            </p>
                            <p>Solicitada el: <span
                                    class="text-gray-500">{{ $purchase_order->created_at->isoFormat('DD MMMM YYYY') }}</span>
                            </p>
                            <p>Fecha de entrega esperada: <span
                                    class="text-gray-500">{{ $purchase_order->expected_delivery_at->isoFormat('DD MMMM YYYY') }}</span>
                            </p>
                            <p>Status: <span class="text-gray-500">{{ $purchase_order->status }}</span></p>
                            <p>Notas: <span class="text-gray-500">{{ $purchase_order->notes }}</span></p>
                        </div>
                    </div>

                    <!-- Products -->
                    <div x-show="activeTab == 1">
                        <h2 class="text-lg text-gray-800"> {{ $purchase_order->purchaseOrderedProducts->count() }}
                            productos</h2>
                        <div class="grid-cols-1 md:grid md:grid-cols-2 md:gap-3 mt-2 text-sm">
                            @foreach ($purchase_order->purchaseOrderedProducts as $p_o_p)
                                <x-simple-product-card :simpleProduct="$p_o_p->product">
                                    <!-- OP details -->
                                    <p class="mt-1 text-gray-800 font-bold">Notas: <span
                                            class="text-gray-600 font-normal">{{ $p_o_p->notes }}
                                        </span>
                                    </p>
                                    <p class="mt-1 text-gray-500">Código: <span
                                            class="text-green-600">{{ $p_o_p->code ?? '-' }}</span>
                                    </p>
                                    <p class="mt-1 text-gray-500">Cantidad ordenada: <span
                                            class="text-green-600">{{ $p_o_p->quantity }}
                                            {{ $p_o_p->product->unit->name }}</span>
                                    </p>
                                </x-simple-product-card>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cerrar
            </x-jet-secondary-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
