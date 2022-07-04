<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Editar orden de compra
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
                                @if ($contact->model_name == 'App\\Models\\' . Supplier::class)
                                    <label class="flex items-center radio cursor-pointer">
                                        <input wire:model="purchase_order.contact_id" value="{{ $contact->id }}" type="radio"
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
                                @endif
                            @endforeach
                            <x-jet-input-error for="purchase_order.contact_id" class="mt-1" />
                        </div>
                    </div>
                @endif
                <x-jet-input-error for="supplier" class="mt-1" />
            </div>
            <h2 class="text-center font-bold text-lg text-sky-600 mt-2">Logística</h2>
            <div class="lg:grid lg:grid-cols-2 lg:gap-2">
                <div>
                    <x-jet-label value="Paquetería" class="mt-3" />
                    <x-jet-input wire:model.defer="purchase_order.shipping_company" type="text" class="w-full mt-2" />
                    <x-jet-input-error for="purchase_order.shipping_company" class="mt-1" />
                </div>
                <div>
                    <x-jet-label value="Guía" class="mt-3" />
                    <x-jet-input wire:model.defer="purchase_order.tracking_guide" type="text" class="w-full mt-2" />
                    <x-jet-input-error for="purchase_order.tracking_guide" class="mt-1" />
                </div>
            </div>

            <!-- order data -->
            <h2 class="text-center font-bold text-lg text-sky-600 mt-3">Datos de la orden</h2>
            <div>
                <x-jet-label value="Fecha esperada de entrega" class="mt-3" />
                <x-jet-input wire:model.defer="expected_delivery_at" type="date" class="w-full mt-2" />
                <x-jet-input-error for="expected_delivery_at" class="mt-1" />
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
                <x-item-list 
                :index="$i"
                :active="array_key_exists('id', $purchase_ordered_product) ? !in_array($purchase_ordered_product['id'],$temporary_deleted_list) : true"
                :objectId="array_key_exists('id', $purchase_ordered_product) ? $purchase_ordered_product['id'] : null"
                :inactiveMessage="$deleted_message"
                :canUndo="true"
                >
                    <x-product-quick-view :image="$p_o_p->product->image" :name="$p_o_p->product->name">
                        <span class="text-blue-500">{{ $p_o_p->quantity }} {{ $p_o_p->product->unit->name }}
                        </span>
                    </x-product-quick-view>
                </x-item-list>
            @endforeach
            <div class="mt-2">
                <x-jet-label value="Notas" />
                <textarea wire:model.defer="purchase_order.notes" rows="3"
                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
                <x-jet-input-error for="purchase_order.notes" class="mt-1" />
            </div>
            {{-- <label class="inline-flex items-center mt-3 text-xs">
                <input wire:model="purchase_order.iva_included" type="checkbox" value="1" class="rounded">
                <span class="ml-2 text-gray-700">IVA incluido en los precios</span>
            </label> --}}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>
            @if(Auth::user()->can('autorizar_ordenes_compra') && !$purchase_order->authorized_user_id)
            <x-jet-button wire:click="authorize" wire:loading.attr="disabled" wire:target="authorize" class="disabled:opacity-25 mr-2">
                Autorizar
            </x-jet-button>
            @endif
            <x-jet-button wire:click="update" wire:loading.attr="disabled" wire:target="update"
                class="disabled:opacity-25">
                Actualizar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>
