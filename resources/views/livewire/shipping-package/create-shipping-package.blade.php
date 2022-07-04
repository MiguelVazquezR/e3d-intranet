<div>

    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            @if ($sell_ordered_product)
                Crear paquetes para pedido
                OV-{{ str_pad($sell_ordered_product->sellOrder->id, 4, '0', STR_PAD_LEFT) }}
            @endif
        </x-slot>

        <x-slot name="content">
            @if ($sell_ordered_product)
                @if ($sell_ordered_product->productForSell->model_name == 'App\\Models\\' . Product::class)
                    @php
                        $product = App\Models\Product::find($sell_ordered_product->productForSell->model_id);
                    @endphp
                    <x-simple-product-card :simpleProduct="$product" :vertical="false">
                        {{-- operators --}}
                        <div class="mt-1 text-gray-700 border-t-2 pb-2">Operadores asignados:
                            <ul>
                                @forelse($sell_ordered_product->activityDetails as $activity_details)
                                    <li class="text-gray-500 flex justify-between items-center">
                                        - {{ $activity_details->operator->name }}
                                        <div x-data="{ open_tooltip: false }" class="flex items-center">
                                            <span @click="open_tooltip = !open_tooltip"
                                                class="fas fa-tasks hover:cursor-pointer relative">
                                                <div @click.away="open_tooltip=false" x-show="open_tooltip" x-transition
                                                    class="absolute w-60 z-10 right-full top-0 m-1 cursor-default">
                                                    <div class="bg-black text-white text-xs rounded py-1 px-2 font-sans">
                                                        <span class="text-yellow-400">Indicaciones:</span>
                                                        {{ mb_strtolower($activity_details->indications) }} <br><br>
                                                        <span class="text-yellow-400">tiempo estimado:</span>
                                                        {{ $activity_details->estimated_time }}
                                                        minutos <br><br>
                                                        <span class="text-yellow-400">Status:</span>
                                                        {{ $activity_details->status() }}
                                                    </div>
                                                </div>
                                            </span>
                                        </div>
                                    </li>
                                @empty
                                    <span class="text-red-600">No hay operadores asignados</span>
                                @endforelse
                            </ul>
                        </div>
                        {{-- quantity --}}
                        <div class="mt-1 text-gray-700">
                            {{ $sell_ordered_product->quantity }} unidades ordenadas
                        </div>
                    </x-simple-product-card>
                @else
                    @php
                        $product = App\Models\CompositProduct::find($sell_ordered_product->productForSell->model_id);
                    @endphp
                    <x-composit-product-card :compositProduct="$product" :vertical="false">
                        {{-- operators --}}
                        <div class="mt-1 text-gray-700 border-t-2 pb-2">Operadores asignados:
                            <ul>
                                @forelse($sell_ordered_product->activityDetails as $activity_details)
                                    <li class="text-gray-500 flex justify-between items-center">
                                        - {{ $activity_details->operator->name }}
                                        <div x-data="{ open_tooltip: false }" class="flex items-center">
                                            <span @click="open_tooltip = !open_tooltip"
                                                class="fas fa-tasks hover:cursor-pointer relative">
                                                <div @click.away="open_tooltip=false" x-show="open_tooltip" x-transition
                                                    class="absolute w-48 z-10 right-full top-0 m-1 cursor-default">
                                                    <div
                                                        class="bg-black text-white text-xs rounded py-1 px-2 font-sans">
                                                        <span>Indicaciones:</span>
                                                        {{ $activity_details->indications }} <br><br>
                                                        <span>tiempo estimado:</span>
                                                        {{ $activity_details->estimated_time }}
                                                        minutos <br><br>
                                                        <span>Status:</span>
                                                        {{ $activity_details->status() }}
                                                    </div>
                                                </div>
                                            </span>
                                        </div>
                                    </li>
                                @empty
                                    <span class="text-red-600">No hay operadores asignados</span>
                                @endforelse
                            </ul>
                        </div>
                        {{-- quantity --}}
                        <div class="mt-1 text-gray-700">
                            {{ $sell_ordered_product->quantity }} unidades ordenadas
                        </div>
                    </x-composit-product-card>
                @endif
                <div class="grid grid-cols-3 gap-x-2">
                    <div>
                        <x-jet-label value="Largo" class="mt-3" />
                        <x-jet-input wire:model.defer="large" type="text" placeholder="cm" class="w-full mt-2" />
                        <x-jet-input-error for="large" class="text-xs" />
                    </div>
                    <div>
                        <x-jet-label value="Ancho" class="mt-3" />
                        <x-jet-input wire:model.defer="width" type="text" placeholder="cm" class="w-full mt-2" />
                        <x-jet-input-error for="width" class="text-xs" />
                    </div>
                    <div>
                        <x-jet-label value="Alto" class="mt-3" />
                        <x-jet-input wire:model.defer="height" type="text" placeholder="cm" class="w-full mt-2" />
                        <x-jet-input-error for="height" class="text-xs" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <x-jet-label value="Cantidad" class="mt-3" />
                        <x-jet-input wire:model.defer="quantity" type="text" placeholder="unidades"
                            class="w-full mt-2" />
                        <x-jet-input-error for="quantity" class="text-xs" />
                    </div>
                    <div>
                        <x-jet-label value="Peso" class="mt-3" />
                        <x-jet-input wire:model.defer="weight" type="text" placeholder="kg" class="w-full mt-2" />
                        <x-jet-input-error for="weight" class="text-xs" />
                    </div>
                    <x-image-uploader :image="$inside_image" :imageExtensions="$image_extensions" :imageId="$inside_image_id"
                        label="Imagen del contenido del paquete" model="inside_image" class="col-span-full" />
                    <x-image-uploader :image="$outside_image" :imageExtensions="$image_extensions" :imageId="$outside_image_id" :showAlerts="false"
                        label="Imagen del exterior del paquete" model="outside_image" class="col-span-full" />
                    <div wire:click="addItemToList" class="hover:cursor-pointer items-center text-green-600 mt-3">
                        <i class="fas fa-plus-circle"></i>
                        <span class="ml-1 text-xs">Agregar a la lista</span>
                    </div>
                </div>

                <x-jet-label value="Paquetes" class="mt-3" />

                @foreach ($packages_list as $i => $package)
                    <x-item-list :index="$i" active="true" :edit="false" :objectId="null">
                        <x-product-quick-view name="{{ $i + 1 }}:">
                            <i class="fas fa-box mr-1"></i><span
                                class="mr-2 text-xs">{{ $package['large'] }}x{{ $package['width'] }}x{{ $package['height'] }}cm
                                - {{ $package['weight'] }}kg</span> ({{ $package['quantity'] }} unidades)
                            <div x-data="{ open_tooltip: false }" class="flex items-center ml-3">
                                <span @click="open_tooltip = !open_tooltip"
                                    class="fas fa-images hover:cursor-pointer relative">
                                    <div @click.away="open_tooltip=false" x-show="open_tooltip" x-transition
                                        class="absolute w-60 z-10 right-full top-0 m-1 cursor-default">
                                        <div
                                            class="flex justify-between bg-black text-white text-xs rounded py-1 px-2 font-sans">
                                            <img class="rounded-2xl w-24"
                                                src="{{ array_key_exists('id', $package) ? Storage::url($package['inside_image']) : $images_list[$i]['inside_image']->temporaryUrl() }}">
                                            <img class="rounded-2xl w-24"
                                                src="{{ array_key_exists('id', $package) ? Storage::url($package['outside_image']) : $images_list[$i]['outside_image']->temporaryUrl() }}">
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </x-product-quick-view>
                    </x-item-list>
                @endforeach
                <x-jet-input-error for="packages_list" class="text-xs" />
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>
            <x-jet-button wire:click="store" wire:loading.attr="disabled" wire:target="store,inside_image,outside_image"
                class="disabled:opacity-25">
                Crear
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>

</div>
