<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
            <div class="flex items-center">
                <i class="fas fa-shopping-cart mr-2"></i>
                Órdenes de compra
            </div>
            @livewire('purchase-order.create-purchase-order')
        </h2>
    </x-slot>

    <div class="py-6">

        <div wire:loading wire:target="edit,show,receiveOrder,emitOrder,cancelOrder">
            <x-loading-indicator />
        </div>

        <!-- inputs -->
        <div class="w-11/12 lg:w-3/4 mx-auto">
            <x-jet-input class="w-full placeholder:text-xs" wire:model="search" type="text" name="search"
                placeholder="Escribe el ID, solicitante o proveedor" />
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
        <x-table :models="$purchase_orders" :sort="$sort" :direction="$direction" :columns="$table_columns">
            <x-slot name="body">
                @foreach ($purchase_orders as $item)
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
                                {{ $item->supplier->name }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $item->created_at->isoFormat('D MMMM YYYY') }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $item->status }}
                            </p>
                        </td>
                        <td class="w-28 px-px py-3 border-b border-gray-200 bg-white">
                            @can('ver_ordenes_compra')
                                <i wire:click="show( {{ $item }} )"
                                    class="far fa-eye bg-sky-400 text-white p-2 rounded-lg hover:cursor-pointer"></i>
                            @endcan
                            @if (Auth::user()->can('autorizar_ordenes_compra'))
                                @can('editar_ordenes_compra')
                                    <i wire:click="edit( {{ $item }} )"
                                        class="far fa-edit bg-blue-500 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                                @endcan
                            @elseif(!$item->authorized_user_id && Auth::user()->id != $item->user_id)
                                <i title="Sólo el solicitante puede editar esta OV al no estar autorizada"
                                    class="far fa-edit bg-blue-500 text-white p-2 rounded-lg ml-1 opacity-50 hover:cursor-not-allowed"></i>
                            @else
                                @can('editar_ordenes_compra')
                                    <i wire:click="edit( {{ $item }} )"
                                        class="far fa-edit bg-blue-500 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                                @endcan
                            @endif
                            @can('eliminar_ordenes_compra')
                                <i wire:click="$emit('confirm', {0:'purchase-order.purchase-orders', 1:'delete' ,2:{{ $item->id }}, 3:'Este proceso no se puede revertir. Si la OC ya se emitió al proveedor y no se ha recibido el pedido, favor de avisar Que la orden se canceló.'})"
                                    class="fas fa-trash bg-red-500 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                            @endcan
                        </td>
                        <td class="py-3 border-b border-gray-200 bg-white text-center">
                            @can('más_acciones_oc')
                                <x-jet-dropdown align="right" width="48" class="inline">
                                    <x-slot name="trigger">
                                        <i
                                            class="fas fa-ellipsis-v text-white p-1 rounded-lg hover:cursor-pointer bg-gray-400 ml-px mt-1"></i>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-jet-dropdown-link href="{{ route('purchase-order-format', $item) }}" target="_blank">
                                            Formato orden de compra
                                        </x-jet-dropdown-link>
                                        @if (is_null($item->emitted_at) && $item->authorized_user_id)
                                        <x-jet-dropdown-link wire:click="emitOrder( {{ $item }} )"
                                            :link="false">
                                            Marcar como orden emitida a proveedor
                                        </x-jet-dropdown-link>
                                        @endif
                                        @if (is_null($item->received_at) && !is_null($item->emitted_at))
                                        <x-jet-dropdown-link wire:click="receiveOrder( {{ $item }} )"
                                            :link="false">
                                            Marcar como orden recibida
                                        </x-jet-dropdown-link>   
                                        @endif
                                        @if (is_null($item->received_at))
                                        <x-jet-dropdown-link class="text-red-500" wire:click="cancelOrder( {{ $item }} )"
                                            :link="false">
                                            Cancelar orden
                                        </x-jet-dropdown-link>
                                        @endif
                                    </x-slot>
                                </x-jet-dropdown>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        </x-table>

        <!-- show modal -->
        @livewire('purchase-order.show-purchase-order')

        <!-- edit modal -->
        @livewire('purchase-order.edit-purchase-order')

        <!-- aditional modals -->
        @livewire('purchase-ordered-product.create-purchase-ordered-product')
        @livewire('purchase-ordered-product.edit-purchase-ordered-product')

    </div>

</div>
