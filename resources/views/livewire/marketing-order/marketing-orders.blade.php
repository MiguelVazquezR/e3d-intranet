<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
            <div class="flex items-center">
                <i class="fas fa-thumbtack mr-2"></i>
                Órdenes de mercadotecnia
            </div>
            @livewire('marketing-order.create-marketing-order')
        </h2>
    </x-slot>

    <div class="py-6">

        <div wire:loading wire:target="edit,show">
            <x-loading-indicator />
        </div>

        <!-- inputs -->
        <div class="w-11/12 lg:w-3/4 mx-auto">
            <x-jet-input class="w-full placeholder:text-xs" wire:model="search" type="text" name="search"
                placeholder="Escribe el ID, solicitante, nombre de órden" />
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
        <x-table :models="$marketing_orders" :sort="$sort" :direction="$direction" :columns="$table_columns">
            <x-slot name="body">
                @foreach ($marketing_orders as $item)
                    <tr>
                        <td class="bg-white px-3 py-3 border-b border-gray-200">
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
                                {{ $item->order_name }}
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
                            @can('ver_ordenes_mercadotecnia')
                                <i wire:click="show( {{ $item }} )"
                                    class="far fa-eye bg-sky-400 text-white p-2 rounded-lg hover:cursor-pointer"></i>
                            @endcan
                            @can('editar_ordenes_mercadotecnia')
                                <i wire:click="edit( {{ $item }} )"
                                    class="far fa-edit bg-blue-500 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                            @endcan
                            @can('eliminar_ordenes_mercadotecnia')
                                <i wire:click="$emit('confirm', {0:'marketing-order.marketing-orders', 1:'delete' ,2:{{ $item->id }}, 3:'Este proceso no se puede revertir'})"
                                    class="fas fa-trash bg-red-500 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                            @endcan
                        </td>
                        <td class="py-3 border-b border-gray-200 bg-white">
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        </x-table>

        <!-- show modal -->
        @livewire('marketing-order.show-marketing-order')

        <!-- edit modal -->
        @livewire('marketing-order.edit-marketing-order')

         <!-- aditional modals -->
         @livewire('marketing-order.request-modifications')

    </div>

</div>
