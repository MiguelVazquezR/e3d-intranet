<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    @can('crear_productos')
    <x-jet-button wire:click="openModal">
        + nuevo
    </x-jet-button>
    @endcan

    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            Registrar producto
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Nombre" class="mt-1 dark:text-gray-400" />
                <x-jet-input wire:model.defer="alias" type="text" class="w-full mt-2 input" />
                <x-jet-input-error for="alias" class="text-xs" />
            </div>
                            
            <div class="mb-4">
                <x-jet-label value="Estado" class="mt-3 dark:text-gray-400" />
                <x-select class="mt-2 w-3/4 input" wire:model.defer="product_status_id" :options="$statuses" />
                <x-jet-secondary-button class="ml-2 rounded-full input" wire:click="$emitTo('product-status.create-product-status', 'openModal')">
                    <i class="fas fa-plus"></i>
                </x-jet-secondary-button>
                <x-jet-input-error for="product_status_id" class="text-xs" />
            </div>

            <div class="mb-4">
                <x-jet-label value="Familia" class="mt-3 dark:text-gray-400" />
                <x-select class="mt-2 w-3/4 input" wire:model="product_family_id" :options="$families" />
                <x-jet-secondary-button class="ml-2 rounded-full input" wire:click="$emitTo('product-family.create-product-family', 'openModal')">
                    <i class="fas fa-plus"></i>
                </x-jet-secondary-button>
                <x-jet-input-error for="product_family_id" class="text-xs" />
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
                        <x-jet-label value="Cantidad para armar producto" class="mt-3 dark:text-gray-400" />
                        <div class="flex items-center">
                            <x-jet-input wire:model.defer="quantity" type="number" min="1" class="w-1/2 mt-2 input" />
                            <span class="w-1/2 ml-2 text-gray-600 text-sm">{{ $selected_product->unit->name }}</span>
                        </div>
                        <x-jet-input-error for="quantity" class="text-xs" />
                    </div>
                    <div>
                        <x-jet-label value="Características adicionales o personalización" class="mt-3 dark:text-gray-400" />
                        <textarea wire:model.defer="notes" rows="2" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
                        <x-jet-input-error for="notes" class="text-xs" />
                    </div>
                </div>

            </div>
            @elseif(!count($products_list))
            <div class="h-40"></div>
            @endif

            @foreach($products_list as $i => $product)
            @php
            $current_product = App\Models\Product::find($product["product_id"]);
            @endphp
            <x-item-list :index="$i" active="true" :objectId="null">
                <x-product-quick-view :image="$current_product->image" :name="$current_product->name">
                    <span class="text-blue-500">{{ $product["quantity"] . ' ' . $current_product->unit->name }}</span>
                    (<span class="text-blue-500">{{ $product["notes"] }}</span>)
                </x-product-quick-view>
            </x-item-list>
            @endforeach
            <x-jet-input-error for="products_list" class="text-xs" />
        </x-slot>

        <x-slot name="footer" class="mt-8">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="store" wire:loading.attr="disabled" wire:target="store" class="disabled:opacity-25">
                Crear
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>