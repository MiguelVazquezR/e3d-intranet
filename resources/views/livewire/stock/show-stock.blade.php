<div>
    @if($open)
    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
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
                    <li class="cursor-pointer text-sm py-2 px-6 text-gray-500 border-b-2" :class="activeTab===index ? 'text-black border-black' : ''" @click="activeTab = index; changeAction(); $dispatch('change-tab-show', index);" x-text="tab"></li>
                </template>
            </ul>
            <!--tab component end-->
            
        </x-slot>

        <x-slot name="content">
            <div x-data="{activeTab: @entangle('active_tab'), action: @entangle('action')}" @change-tab-show.window="activeTab = $event.detail">
                <!-- Detalles -->
                <div x-show="activeTab==0">
                    <h2 class="mb-4 text-gray-500">
                        Producto de inventario <span class="text-black">{{ $stock_product->id }}</span>
                    </h2>

                    <div class="grid grid-cols-3 gap-4">
                        @if($stock_product->image)
                        <img class="w-48 h-48 rounded-2xl object-fill" src="{{ Storage::url($stock_product->image) }}">
                        @endif
                        @if($stock_product->product)
                        <div>
                            <x-jet-label value="Producto" class="mt-3" />
                            <x-product-quick-view :image="$stock_product->product->image" :name="$stock_product->product->name" />
                            <div>
                                <x-jet-label value="Cantidad actual" class="mt-3" />
                                <p>{{ $stock_product->quantity . ' ' . $stock_product->product->unit['name'] }}</p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <x-jet-label value="Ubicación" class="mt-3" />
                                <p>{{ $stock_product->location }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Entradas -->
                <div x-show="activeTab==1" class="text-gray-500">
                    <div class="flex justify-between pb-4 border-b-2">
                        @if($stock_product->product)
                        <h2>
                            Entradas de <span class="text-black font-extrabold">{{ $stock_product->product->name }}</span>
                        </h2>
                        @endif
                    </div>

                    <div x-show="action != 1" class="py-8 text-center">
                        Cargando...
                    </div>

                    @if($stock_movements->count())
                    <div x-show="action == 1" class="w-full mt-3 max-h-72 overflow-auto hover:overflow-y-scroll lg:overflow-hidden">
                        <table>
                            <thead>
                                <tr class="text-xs uppercase text-gray-700">
                                    <td class="w-1/5 p-2">Creado por</td>
                                    <td class="w-1/5 p-2">Movimiento</td>
                                    <td class="w-1/5 p-2">Cantidad</td>
                                    <td class="w-1/5 p-2">Notas</i></td>
                                    <td wire:click="$set('human_format', {{!$human_format}})" class="w-1/5 p-2 hover:cursor-pointer">Fecha <i class="fas fa-exchange-alt"></i></td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stock_movements as $movement)
                                <tr class="text-xs my-2 bg-green-100 border-b border-green-300">
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
                <div x-show="activeTab==2" class="text-gray-500">
                    <div class="flex justify-between pb-4 border-b-2">
                        @if($stock_product->product)
                        <h2>
                            Salidas de <span class="text-black font-extrabold">{{ $stock_product->product->name }}</span>
                        </h2>
                    </div>
                    @endif

                    <div x-show="action != 0" class="py-8 text-center">
                        Cargando...
                    </div>

                    @if($stock_movements->count())
                    <div x-show="action == 0" class="w-full mt-3 max-h-72 overflow-auto hover:overflow-y-scroll lg:overflow-hidden">
                        <table>
                            <thead>
                                <tr class="text-xs uppercase text-gray-700">
                                    <td class="w-1/5 p-2">Creado por</td>
                                    <td class="w-1/5 p-2">Movimiento</td>
                                    <td class="w-1/5 p-2">Cantidad</td>
                                    <td class="w-1/5 p-2">Notas</i></td>
                                    <td wire:click="$set('human_format', {{!$human_format}})" class="w-1/5 p-2 hover:cursor-pointer">Fecha <i class="fas fa-exchange-alt"></i></td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stock_movements as $movement)
                                <tr class="text-xs my-2 bg-red-100 border-b border-red-300">
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
        </x-slot>

    </x-jet-dialog-modal>
    @endif
</div>