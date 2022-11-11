<div>
    @if($open)
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Edición Producto de inventario {{ $stock_product->id }}
            <!--tab component start-->
            <ul class="flex justify-center items-center pb-4" x-data="{
                activeTab: 0,
                        tabs: [
                            'Detalles',
                            'Entradas',
                            'Salidas',
                        ],
                        action: @entangle('action'),
                        activeTab: @entangle('active_tab'),
                        changeAction() {
                            if (this.activeTab == 1) {
                                this.action = 1;
                            } else if (this.activeTab == 2) {
                                this.action = 0;
                            }
                        }
            }">
                <template x-for="(tab, index) in tabs" :key="index">
                    <li class="cursor-pointer text-sm py-2 px-6 text-gray-500 border-b-2" :class="activeTab===index ? 'text-black border-black dark:text-gray-200 dark:border-gray-200' : ''" @click="activeTab = index; changeAction(); $dispatch('change-tab-edit', index);" x-text="tab"></li>
                </template>
            </ul>
            <!--tab component end-->
        </x-slot>

        <x-slot name="content">
            <div x-data="{activeTab: @entangle('active_tab'), action: @entangle('action')}" @change-tab-edit.window="activeTab = $event.detail">
                <!-- Detalles -->
                <div x-show="activeTab==0">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2">
                            @livewire('products.search-products')
                        </div>
                        @if( !empty($selected_product->id) )
                        <x-product-quick-view :image="$selected_product->image" :name="$selected_product->name" />
                        @endif
                    </div>
                    <x-jet-input-error for="selected_product" class="text-xs" />
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-jet-label value="Cantidad" class="mt-3 dark:text-gray-400" />
                            <x-jet-input wire:model.defer="stock_product.quantity" type="number" min="1" class="w-full mt-2 input" />
                            <x-jet-input-error for="stock_product.quantity" class="text-xs" />
                        </div>

                        <div class="mb-3">
                            <x-jet-label value="Ubicación" class="mt-3 dark:text-gray-400" />
                            <x-jet-input wire:model.defer="stock_product.location" type="text" class="w-full mt-2 input" />
                            <x-jet-input-error for="stock_product.location" class="text-xs" />
                        </div>
                    </div>

                    <x-image-uploader :image="$image" :imageExtensions="$image_extensions" :imageId="$image_id" :registeredImage="$stock_product->image" label="Imagen de ubicación" />

                </div>

                <!-- Entradas -->
                <div x-show="activeTab == 1" class="text-gray-500">
                    <div class="flex justify-between pb-4 border-b-2">
                        @if($stock_product->product)
                        <h2>
                            Entradas de <span class="text-black font-extrabold dark:text-gray-200">{{ $stock_product->product->name }}</span>
                        </h2>
                        @endif
                        <x-jet-secondary-button wire:click="$emitTo('stock-movement.create-stock-movement', 'openModal', ['entrada', {{ $stock_product->id }}])" class="ml-2">
                            Registrar entrada
                        </x-jet-secondary-button>
                    </div>

                    <div x-show="action != 1" class="py-8 text-center">
                        Cargando...
                    </div>

                    @if($stock_movements->count())
                    <div x-show="action == 1" class="w-full mt-3 max-h-72 overflow-auto hover:overflow-y-scroll lg:overflow-hidden">
                        <table>
                            <thead>
                                <tr class="text-xs uppercase text-gray-700 dark:text-gray-500">
                                    <td class="w-1/5 p-2">Creado por</td>
                                    <td class="w-1/5 p-2">Movimiento</td>
                                    <td class="w-1/5 p-2">Cantidad</td>
                                    <td class="w-1/5 p-2">Notas</i></td>
                                    <td wire:click="$set('human_format', {{!$human_format}})" class="w-1/5 p-2 hover:cursor-pointer">Fecha <i class="fas fa-exchange-alt"></i></td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stock_movements as $movement)
                                <tr class="text-xs my-2 bg-green-100 border-b border-green-300 dark:bg-green-300 dark:border-green-600">
                                    <td class="w-1/5 p-1"> {{ $movement->user->name }} </td>
                                    <td class="w-1/5 p-1"> {{ $movement->action->name }} </td>
                                    <td class="w-1/5 p-1"> {{ $movement->quantity . ' ' . $movement->stockProduct->product->unit->name}} </td>
                                    <td class="w-1/5">
                                        @if( !is_null($movement->notes) )
                                        {{ $movement->notes }}
                                        @else
                                        --
                                        @endif
                                    </td>
                                    @if( $human_format )
                                    <td class="w-1/5 p-1"> {{ $movement->created_at->diffForHumans() }} </td>
                                    @else
                                    <td class="w-1/5 p-1"> {{ $movement->created_at->isoFormat('DD MMMM YYYY h:mm a') }} </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <h2 x-show="action == 1" class="text-center py-10">No hay entradas registradas</h2>
                    @endif
                </div>

                <!-- Salidas -->
                <div x-show="activeTab == 2" class="text-gray-500">
                    <div class="flex justify-between pb-4 border-b-2">
                        @if($stock_product->product)
                        <h2>
                            Salidas de <span class="text-black font-extrabold dark:text-gray-200">{{ $stock_product->product->name }}</span>
                        </h2>
                        @endif
                        <x-jet-secondary-button class="ml-2" wire:click="$emitTo('stock-movement.create-stock-movement', 'openModal', ['salida', {{ $stock_product->id }}])">
                            Registrar salida
                        </x-jet-secondary-button>
                    </div>

                    <div x-show="action != 0" class="py-8 text-center">
                        Cargando...
                    </div>

                    @if($stock_movements->count())
                    <div x-show="action == 0" class="w-full mt-3 max-h-72 overflow-auto hover:overflow-y-scroll lg:overflow-hidden">
                        <table>
                            <thead>
                                <tr class="text-xs uppercase text-gray-700 dark:text-gray-500">
                                    <td class="w-1/5 p-2">Creado por</td>
                                    <td class="w-1/5 p-2">Movimiento</td>
                                    <td class="w-1/5 p-2">Cantidad</td>
                                    <td class="w-1/5 p-2">Notas</i></td>
                                    <td wire:click="$set('human_format', {{!$human_format}})" class="w-1/5 p-2 hover:cursor-pointer">Fecha <i class="fas fa-exchange-alt"></i></td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stock_movements as $movement)
                                <tr class="text-xs my-2 bg-red-100 border-b border-red-300 dark:bg-red-300 dark:border-red-600">
                                    <td class="w-1/5 p-1"> {{ $movement->user->name }} </td>
                                    <td class="w-1/5 p-1"> {{ $movement->action->name }} </td>
                                    <td class="w-1/5 p-1 flex justify-between"> {{ $movement->quantity . ' ' . $movement->stockProduct->product->unit->name}} </td>
                                    <td class="w-1/5">
                                        @if( $movement->notes )
                                        {{ $movement->notes }}
                                        @else
                                        --
                                        @endif
                                    </td>
                                    @if( $human_format )
                                    <td class="w-1/5 p-1"> {{ $movement->created_at->diffForHumans() }} </td>
                                    @else
                                    <td class="w-1/5 p-1"> {{ $movement->created_at->isoFormat('DD MMMM YYYY h:mm a') }} </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <h2 x-show="action == 0" class="text-center py-10">No hay salidas registradas</h2>
                    @endif
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cerrar
            </x-jet-secondary-button>

            @if($active_tab == 0)
            <x-jet-button wire:click="update" wire:loading.attr="disabled" wire:target="update,image" class="disabled:opacity-25">
                Actualizar
            </x-jet-button>
            @endif
        </x-slot>

    </x-jet-dialog-modal>
    @endif
</div>