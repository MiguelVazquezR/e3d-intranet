<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
            <div class="flex items-center">
                <i class="fas fa-hand-holding-usd mr-2"></i>
                Órdenes de venta
            </div>
            @livewire('sell-order.create-sell-order')
        </h2>
    </x-slot>

    <div class="py-6">

        <div wire:loading wire:target="edit,show">
            <x-loading-indicator />
        </div>

        <!-- inputs -->
        <div class="w-11/12 lg:w-3/4 mx-auto">
            <x-jet-input class="w-full placeholder:text-xs" wire:model="search" type="text" name="search"
                placeholder="Escribe el ID, creador o cliente" />
        </div>
        <div class="w-3/4 mx-auto flex justify-between pt-8">
            <div>
                <span class="mr-2 text-sm">Mostrar</span>
                <x-select class="mt-2" wire:model="elements">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </x-select>
            </div>
        </div>

        <!-- table -->
        <x-table :models="$sell_orders" :sort="$sort" :direction="$direction" :columns="$table_columns">
            <x-slot name="body">
                @foreach ($sell_orders as $item)
                    <tr>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $item->id }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $item->creator->name }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $item->customer->name }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $item->priority }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $item->created_at->isoFormat('D MMMM YYYY') }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">
                                @foreach ($item->sellOrderedProducts as $s_o_p)
                                    @php
                                        if ($s_o_p->productForSell->model instanceof App\Models\Product) {
                                            $product = App\Models\Product::find($s_o_p->productForSell->model_id);
                                            $name = $product->name;
                                        } else {
                                            $product = App\Models\CompositProduct::find($s_o_p->productForSell->model_id);
                                            $name = $product->alias;
                                        }
                                    @endphp
                                    @forelse($s_o_p->activityDetails as $activity)
                                        <span
                                            class="relative inline-block px-1 py-1 ml-1 mb-1 font-semibold {{ $activity->finish ? 'text-green-900' : 'text-gray-800' }} leading-tight">
                                            <span aria-hidden
                                                class="absolute inset-0 {{ $activity->finish ? 'bg-green-200' : 'bg-gray-300' }} opacity-50 rounded-full"></span>
                                            <span class="relative"> {{ $name }}:
                                                {{ $activity->operator->name }}</span>
                                        </span>
                                    @empty
                                        <span
                                            class="relative inline-block px-1 py-1 ml-1 mb-1 font-semibold text-red-900 leading-tight">
                                            <span aria-hidden
                                                class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                                            <span class="relative"> {{ $name }}: No hay operadores
                                                asignados </span>
                                        </span>
                                    @endforelse
                                @endforeach
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $item->status }}
                            </p>
                        </td>
                        <td class="w-28 px-px py-3 border-b border-gray-200 bg-white">
                            @can('ver_ordenes_venta')
                                <i wire:click="show( {{ $item }} )"
                                    class="far fa-eye bg-sky-400 text-white p-2 rounded-lg hover:cursor-pointer"></i>
                            @endcan
                            @if (Auth::user()->can('autorizar_ordenes_venta'))
                                @can('editar_ordenes_venta')
                                    <i wire:click="edit( {{ $item }} )"
                                        class="far fa-edit bg-blue-500 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                                @endcan
                            @elseif(!$item->authorized_user_id && Auth::user()->id != $item->user_id)
                                <i title="Sólo el solicitante puede editar esta OV al no estar autorizada"
                                    class="far fa-edit bg-blue-500 text-white p-2 rounded-lg ml-1 opacity-50 hover:cursor-not-allowed"></i>
                            @else
                                @can('editar_ordenes_venta')
                                    <i wire:click="edit( {{ $item }} )"
                                        class="far fa-edit bg-blue-500 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                                @endcan
                            @endif
                            @can('eliminar_ordenes_venta')
                                <i wire:click="$emit('confirm', {0:'sell-order.sell-orders', 1:'delete' ,2:{{ $item->id }}, 3:'Este proceso no se puede revertir. Si la OV no se ha terminado, se tendrá que hacer ajuste en inventario manualmente.'})"
                                    class="fas fa-trash bg-red-500 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                            @endcan
                        </td>
                        <td class="py-3 border-b border-gray-200 bg-white text-center">
                            @can('más_acciones_ov')
                                <x-jet-dropdown align="right" width="48" class="inline">
                                    <x-slot name="trigger">
                                        <i class="fas fa-ellipsis-v text-white p-1 rounded-lg hover:cursor-pointer bg-gray-400 ml-px mt-1"></i>
                                    </x-slot>

                                    <x-slot name="content">
                                        <x-jet-dropdown-link href="{{ route('quality-certificate-format', $item) }}"
                                            target="_blank">
                                            Certificados de calidad
                                        </x-jet-dropdown-link>
                                        <x-jet-dropdown-link href="{{ route('sell-order-format', $item) }}"
                                            target="_blank">
                                            Formato orden de venta
                                        </x-jet-dropdown-link>
                                        <x-jet-dropdown-link wire:click="shippingPackages( {{ $item }} )" :link="false">
                                            Paquetes
                                        </x-jet-dropdown-link>
                                    </x-slot>
                                </x-jet-dropdown>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        </x-table>

        <!-- show modal -->
        @livewire('sell-order.show-sell-order')

        <!-- edit modal -->
        @livewire('sell-order.edit-sell-order')

        <!-- aditional modals -->
        @livewire('sell-ordered-product.create-sell-ordered-product')
        @livewire('sell-ordered-product.edit-sell-ordered-product')
        @livewire('user-has-sell-ordered-product.create-user-has-sell-ordered-product')
        @livewire('shipping-package.shipping-packages')
        @livewire('shipping-package.create-shipping-package')
        @livewire('shipping-package.edit-shipping-package')
        @livewire('shipping-package.partial-shipment')


    </div>

</div>
