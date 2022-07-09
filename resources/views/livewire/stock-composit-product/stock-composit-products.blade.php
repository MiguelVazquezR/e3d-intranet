<div>
    <div class="py-6">

        <div wire:loading wire:target="edit,show">
            <x-loading-indicator />
        </div>

        <!-- inputs -->
        <div class="w-11/12 lg:w-3/4 mx-auto">
            <x-jet-input class="w-full placeholder:text-xs" wire:model="search" type="text" name="search" placeholder="Escribe el nombre del producto" />
        </div>
        <div class="w-3/4 mx-auto flex justify-between pt-8">
            <div>
                <span class="mr-2 text-sm">Mostrar</span>
                <select class="input mt-2" wire:model="elements">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

        <!-- table -->
        <x-table :models="$stock_products" :sort="$sort" :direction="$direction" :columns="$table_columns">
            <x-slot name="body">
                @foreach( $stock_products as $item )
                <tr>
                    <td class="px-3 py-3 border-b border-gray-200 bg-white">
                        <p class="text-gray-900 whitespace-no-wrap">
                            {{$item->id}}
                        </p>
                    </td>
                    <td class="px-3 py-3 border-b border-gray-200 bg-white">
                        <p class="text-gray-900 whitespace-no-wrap">
                            {{ $item->compositProduct->alias }}
                        </p>
                    </td>
                    <td class="px-3 py-3 border-b border-gray-200 bg-white">
                        @if($item->compositProduct->min_stock >= $item->quantity)
                        <span class="relative inline-block px-3 py-1 font-semibold text-red-900 leading-tight">
                            <span aria-hidden class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                            <span class="relative">{{ $item->quantity . ' unidades' }}</span>
                        </span>
                        @else
                        <p class="text-gray-900 whitespace-no-wrap">
                            {{ $item->quantity . ' unidades' }}
                        </p>
                        @endif
                    </td>
                    <td class="px-3 py-3 border-b border-gray-200 bg-white">
                        <p class="text-gray-900 whitespace-no-wrap">{{$item->location}}</p>
                    </td>
                    <td class="w-28 px-px py-3 border-b border-gray-200 bg-white">
                        @can('ver_inventarios')
                        <i wire:click="show({{ $item }})" class="far fa-eye bg-sky-400 text-white p-2 rounded-lg hover:cursor-pointer"></i>
                        @endcan
                        @can('editar_inventarios')
                        <i wire:click="edit({{ $item }})" class="far fa-edit bg-blue-500 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                        @endcan
                        @can('eliminar_inventarios')
                        <i wire:click="$emit('confirm', { 0:'stock-composit-product.stock-composit-products', 1:'delete', 2:{{$item}}, 3:'Este proceso no se puede revertir' })" class="fas fa-trash bg-red-500 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                        @endcan
                    </td>
                    <td class="py-3 border-b border-gray-200 bg-white">
                    </td>
                </tr>
                @endforeach
            </x-slot>
        </x-table>

        <!-- show modal -->
        @livewire('stock-composit-product.show-stock-composit-product')

        <!-- edit modal -->
        @livewire('stock-composit-product.edit-stock-composit-product')

        <!-- aditional modals -->
        @livewire('stock-composit-product-movement.create-stock-composit-product-movement')

    </div>


</div>