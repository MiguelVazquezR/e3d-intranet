<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
            <div class="flex items-center dark:text-gray-400">
                <i class="fas fa-puzzle-piece mr-2"></i>
                Productos compuestos
            </div>
            @livewire('composit-product.create-composit-product')
        </h2>
    </x-slot>

    <div class="py-6">

        <div wire:loading wire:target="edit,show">
            <x-loading-indicator />
        </div>

        <!-- inputs -->
        <div class="w-11/12 lg:w-3/4 mx-auto">
            <x-jet-input class="w-full placeholder:text-xs input" wire:model="search" type="text" name="search" placeholder="Escribe el nombre del producto compuesto" />
        </div>
        <div class="w-3/4 mx-auto flex justify-between pt-8">
            <div>
                <span class="mr-2 text-sm dark:text-gray-400">Mostrar</span>
                <select class="mt-2 input" wire:model="elements">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

        <!-- banner -->
        <div x-data="{open: true}" x-show="open" class="w-11/12 flex justify-between mx-auto bg-orange-100 dark:bg-orange-300 rounded-lg p-4 my-6 text-sm font-medium text-orange-700" role="alert">
            <div class="w-11/12 flex">
                <i class="fas fa-exclamation-circle w-5 h-5 inline mr-3"></i>
                <div>
                    <span class="font-extrabold">¡Solo productos compuestos!</span>
                    En esta sección se registran productos compuestos por 2 o más productos simples, etc.
                    Si quiere registrar un producto simple vaya a <a class="hover:cursor-pointer underline" href="{{ route('products') }}">productos simples</a>
                </div>
            </div>

            <i @click="open = false" class="fal fa-times text-right hover:cursor-pointer"></i>
        </div>

        <!-- table -->
        <x-table :models="$composit_products" :sort="$sort" :direction="$direction" :columns="$table_columns">
            <x-slot name="body">
                @foreach( $composit_products as $item )
                <tr>
                    <td class="px-3 py-3 border-b dark:bg-slate-700 dark:text-gray-400 border-gray-200 bg-white">
                        <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">
                            {{$item->id}}
                        </p>
                    </td>
                    <td class="px-3 py-3 border-b dark:bg-slate-700 dark:text-gray-400 border-gray-200 bg-white">
                        <x-product-quick-view :image="$item->image" :name="$item->alias" :nameBolded="false" />
                    </td>
                    <td class="px-3 py-3 border-b dark:bg-slate-700 dark:text-gray-400 border-gray-200 bg-white">
                        <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">
                            {{$item->compositProductDetails->count()}} productos simples
                        </p>
                    </td>
                    <td class="px-3 py-3 border-b dark:bg-slate-700 dark:text-gray-400 border-gray-200 bg-white">
                        @if($item->product_status_id == 1)
                        <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                            <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                            <span class="relative">{{$item->status->name}}</span>
                        </span>
                        @elseif($item->product_status_id == 2)
                        <span class="relative inline-block px-3 py-1 font-semibold text-red-900 leading-tight">
                            <span aria-hidden class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                            <span class="relative">{{$item->status->name}}</span>
                        </span>
                        @else
                        <span class="relative inline-block px-3 py-1 font-semibold text-amber-800 leading-tight">
                            <span aria-hidden class="absolute inset-0 bg-amber-200 opacity-50 rounded-full"></span>
                            <span class="relative">{{$item->status->name}}</span>
                        </span>
                        @endif
                    </td>
                    <td class="w-28 px-px py-3 border-b dark:bg-slate-700 dark:text-gray-400 border-gray-200 bg-white">
                        @can('ver_productos')
                        <i wire:click="show({{ $item }})" class="far fa-eye bg-sky-400 text-white p-2 rounded-lg hover:cursor-pointer"></i>
                        @endcan
                        @can('editar_productos')
                        <i wire:click="edit({{ $item }})" class="far fa-edit bg-blue-500 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                        @endcan
                        @can('eliminar_productos')
                        <i wire:click="$emit('confirm', { 0:'composit-product.composit-products', 1:'delete' ,2:{{$item->id}}, 3:'Este proceso no se puede revertir' })" class="fas fa-trash bg-red-500 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                        @endcan
                    </td>
                    <td class="py-3 border-b dark:bg-slate-700 dark:text-gray-400 border-gray-200 bg-white">
                    </td>
                </tr>
                @endforeach
            </x-slot>
        </x-table>

        <!-- show modal -->
        @livewire('composit-product.show-composit-product')

        <!-- edit modal -->
        @livewire('composit-product.edit-composit-product')

        {{-- aditional modals --}}
        @livewire('product-family.create-product-family')
        @livewire('product-status.create-product-status')

    </div>

</div>