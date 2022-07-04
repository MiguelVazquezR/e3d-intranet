<div>
    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            Registrar producto
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Nombre" class="mt-1" />
                <x-jet-input wire:model.defer="composit_product.alias" type="text" class="w-full mt-2" />
                <x-jet-input-error for="composit_product.alias" class="text-xs" />
            </div>

            <div class="mb-4">
                <x-jet-label value="Estado" class="mt-3" />
                <x-select class="mt-2 w-3/4" wire:model.defer="composit_product.product_status_id">
                    <option value="" selected>--- Seleccione ---</option>
                    @forelse($statuses as $status)
                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @empty
                    <option value="">No hay ningun estado registrado</option>
                    @endforelse
                </x-select>
                <x-jet-secondary-button class="ml-2 rounded-full" wire:click="$emitTo('product-status.create-product-status', 'openModal')">
                    <i class="fas fa-plus"></i>
                </x-jet-secondary-button>
                <x-jet-input-error for="composit_product.product_status_id" class="text-xs" />
            </div>

            <div class="mb-4">
                <x-jet-label value="Familia" class="mt-3" />
                <x-select class="mt-2 w-full" wire:model.defer="composit_product.product_family_id">
                    <option value="" selected>--- Seleccione ---</option>
                    @forelse($families as $family)
                    <option value="{{ $family->id }}">{{ $family->name }}</option>
                    @empty
                    <option value="">No hay ninguna familia registrada</option>
                    @endforelse
                </x-select>
                <x-jet-input-error for="composit_product.product_family_id" class="text-xs" />
            </div>

            <x-image-uploader :image="$image" :imageExtensions="$image_extensions" :imageId="$image_id" />

            @livewire('products.search-products')

            @if($selected_product)
            <div class="p-3 bg-sky-100 grid grid-cols-3 gap-x-4 my-2 rounded-2xl">
                <div>
                    <img class="rounded-2xl" src="{{ Storage::url($selected_product->image) }}">
                </div>
                <div class="col-span-2">
                    <div class="flex justify-between">
                        <div class="text-xs">
                            <p>
                                <b>Nombre: </b>{{ $selected_product->name }}
                            </p>
                            <p>
                                <b>Familia: </b>{{ $selected_product->family->name }}
                            </p>
                            <p>
                                <b>Material: </b>{{ $selected_product->material->name }}
                            </p>
                        </div>
                        <div>
                            @if( is_null($edit_index) )
                            <i wire:click="addProductToList" class="fas fa-plus-circle text-green-600 hover:cursor-pointer"></i>
                            @else
                            <i wire:click="updateProductFromList" class="fas fa-check-circle text-green-600 hover:cursor-pointer"></i>
                            @endif
                            <i wire:click="resetProduct" class="fas fa-times text-gray-700 hover:cursor-pointer"></i>
                        </div>
                    </div>
                    <div>
                        <x-jet-label value="Cantidad para armar producto" class="mt-3" />
                        <div class="flex items-center">
                            <x-jet-input wire:model.defer="quantity" type="number" min="1" class="w-1/2 mt-2" />
                            <span class="w-1/2 ml-2 text-gray-600 text-sm">{{ $selected_product->unit->name }}</span>
                        </div>
                        <x-jet-input-error for="quantity" class="text-xs" />
                    </div>
                    <div>
                        <x-jet-label value="Características adicionales o personalización" class="mt-3" />
                        <textarea wire:model.defer="notes" rows="2" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
                        <x-jet-input-error for="notes" class="text-xs" />
                    </div>
                </div>

            </div>
            @elseif(!count($products_list))
            <div class="h-40"></div>
            @endif

            @foreach($products_list as $i => $c_p_d)
            @php
            $current_product = App\Models\Product::find($c_p_d["product_id"]);
            @endphp
            <x-item-list
             :index="$i"
             :active="array_key_exists('id', $c_p_d) ? !in_array($c_p_d['id'],$temporary_deleted_list) : true"
             :objectId="array_key_exists('id', $c_p_d) ? $c_p_d['id'] : null"
             inactiveMessage="Componente eliminado del producto final"
             >
                <x-product-quick-view
                 :image="$current_product->image"
                 :name="$current_product->name"
                 :newItem="!array_key_exists('id', $c_p_d)"
                 >
                    <span class="text-blue-500">{{ $c_p_d["quantity"] . ' ' . $current_product->unit->name }}</span>&nbsp;
                    (<span class="text-blue-500">{{ $c_p_d["notes"] }}</span>)
                </x-product-quick-view>
            </x-item-list>
            @endforeach
            <x-jet-input-error for="products_list" class="text-xs" />
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