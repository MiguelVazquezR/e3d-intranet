<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    @can('crear_ordenes_compra')
        <x-jet-button wire:click="openModal">
            + nuevo
        </x-jet-button>
    @endcan

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Crear nueva orden de compra
        </x-slot>

        <x-slot name="content">
            <!-- Details -->
            <div>
                @livewire('supplier.search-supplier')
                @if ($supplier)
                    <div class="grid grid-cols-2 gap-2 text-xs mt-2 font-bold">
                        <p>Nombre: <span class="font-normal">{{ $supplier->name }}</span></p>
                        <p class="col-span-2">Dirección: <span class="font-normal">{{ $supplier->address }} -
                                C.P.{{ $supplier->post_code }}</span></p>
                        <div class="col-span-2 flex flex-col">
                            <x-jet-label value="Contacto" class="mt-3" />
                            @foreach ($supplier->contacts as $contact)
                                <label class="flex items-center radio cursor-pointer">
                                    <input wire:model="contact_id" value="{{ $contact->id }}" type="radio"
                                        name="for" />
                                    <div class="px-2">
                                        <div
                                            class="col-span-2 flex flex-col lg:flex-row items-center text-sm mb-1 py-2 mx-6 border-b-2 lg:justify-center">
                                            <div>
                                                <i class="fas fa-user-circle mr-1"></i><span
                                                    class="mr-2">{{ $contact->name }}</span>
                                            </div>
                                            <div>
                                                <i class="fas fa-envelope mr-1"></i><span
                                                    class="mr-2">{{ $contact->email }}</span>
                                            </div>
                                            <div>
                                                <i class="fas fa-phone-alt mr-1"></i><span
                                                    class="mr-2">{{ $contact->phone }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                            <x-jet-input-error for="contact_id" class="text-xs" />
                        </div>
                    </div>
                @endif
                <x-jet-input-error for="supplier" class="text-xs" />
            </div>
            <h2 class="text-center font-bold text-lg text-sky-600 mt-2">Logística</h2>
            <div class="lg:grid lg:grid-cols-2 lg:gap-2">
                <div>
                    <x-jet-label value="Paquetería" class="mt-3" />
                    <x-jet-input wire:model.defer="shipping_company" type="text" class="w-full mt-2" />
                    <x-jet-input-error for="shipping_company" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Guía" class="mt-3" />
                    <x-jet-input wire:model.defer="tracking_guide" type="text" class="w-full mt-2" />
                    <x-jet-input-error for="tracking_guide" class="text-xs" />
                </div>
            </div>

            <!-- order data -->
            <h2 class="text-center font-bold text-lg text-sky-600 mt-3">Datos de la orden</h2>
            <div>
                <x-jet-label value="Fecha esperada de entrega" class="mt-3" />
                <x-jet-input wire:model.defer="expected_delivery_at" type="date" class="w-full mt-2" />
                <x-jet-input-error for="expected_delivery_at" class="text-xs" />
            </div>

            <!-- products -->
            <h2 class="text-center font-bold text-lg text-sky-600 mt-3 flex items-center justify-center">
                Productos
                <i wire:click="addPurchaseOrderedProducts"
                    class="fas fa-plus-circle text-green-600 hover:cursor-pointer ml-3"></i>
            </h2>
            <x-jet-input-error for="purchase_ordered_products_list" class="mt-2" />
            @foreach ($purchase_ordered_products_list as $i => $purchase_ordered_product)
                @php
                    $p_o_p = new App\Models\PurchaseOrderedProduct($purchase_ordered_product);
                @endphp
                <x-item-list :index="$i" active="true" :objectId="null">
                    <x-product-quick-view :image="$p_o_p->product->image" :name="$p_o_p->product->name">
                        <span class="text-blue-500">{{ $p_o_p->quantity }} unidades
                        </span>
                    </x-product-quick-view>
                </x-item-list>
            @endforeach
            <div class="mt-2">
                <x-jet-label value="Notas" />
                <textarea wire:model.defer="notes" rows="3"
                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
                <x-jet-input-error for="notes" class="text-xs" />
            </div>
            {{-- <label class="inline-flex items-center mt-3 text-xs">
                <input wire:model="iva_included" type="checkbox" value="1" class="rounded">
                <span class="ml-2 text-gray-700">IVA incluido en los precios</span>
            </label> --}}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="store" wire:loading.attr="disabled" wire:target="store"
                class="disabled:opacity-25">
                Crear
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>
