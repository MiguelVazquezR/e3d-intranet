<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
            <div class="flex items-center">
                <i class="fas fa-hard-hat mr-2"></i>
                Departamento de producción
            </div>
        </h2>
    </x-slot>

    <div class="py-6">

        <div wire:loading wire:target="edit,show">
            <x-loading-indicator />
        </div>

        <!-- inputs -->
        <div class="w-11/12 lg:w-3/4 mx-auto">
            <x-jet-input class="w-full placeholder:text-xs" wire:model="search" type="text" name="search" placeholder="Escribe el ID, creador o cliente" />
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
                @foreach( $sell_orders as $item )
                <tr>
                    <td class="px-3 py-3 border-b border-gray-200 bg-white">
                        <p class="text-gray-900 whitespace-no-wrap">
                            {{$item->id}}
                        </p>
                    </td>
                    <td class="px-3 py-3 border-b border-gray-200 bg-white">
                        <p class="text-gray-900 whitespace-no-wrap">
                            {{$item->creator->name}}
                        </p>
                    </td>
                    <td class="px-3 py-3 border-b border-gray-200 bg-white">
                        <p class="text-gray-900 whitespace-no-wrap">
                            {{$item->customer->name}}
                        </p>
                    </td>
                    <td class="px-3 py-3 border-b border-gray-200 bg-white">
                        <p class="text-gray-900 whitespace-no-wrap">
                            {{$item->priority}}
                        </p>
                    </td>
                    <td class="px-3 py-3 border-b border-gray-200 bg-white">
                        <p class="text-gray-900 whitespace-no-wrap">
                            {{$item->created_at->isoFormat('D MMMM YYYY')}}
                        </p>
                    </td>
                    <td class="px-3 py-3 border-b border-gray-200 bg-white">
                    <p class="text-gray-900 whitespace-no-wrap">
                            @foreach($item->sellOrderedProducts as $s_o_p)
                            @php
                            if($s_o_p->productForSell->model instanceof App\Models\Product) {
                                $product = App\Models\Product::find($s_o_p->productForSell->model_id);
                                $name = $product->name;
                            } else {
                                $product = App\Models\CompositProduct::find($s_o_p->productForSell->model_id);
                                $name = $product->alias;
                            }
                            @endphp
                            @forelse($s_o_p->activityDetails as $activity)
                            <span class="relative inline-block px-1 py-1 ml-1 mb-1 font-semibold {{ $activity->finish ? 'text-green-900' : 'text-gray-800' }} leading-tight">
                                <span aria-hidden class="absolute inset-0 {{ $activity->finish ? 'bg-green-200' : 'bg-gray-300' }} opacity-50 rounded-full"></span>
                                <span class="relative"> {{ $name }}: {{ $activity->operator->name }}</span>
                            </span>
                            @empty
                            <span class="relative inline-block px-1 py-1 ml-1 mb-1 font-semibold text-red-900 leading-tight">
                                <span aria-hidden class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                                <span class="relative"> {{ $name }}: No hay operadores asignados </span>
                            </span>
                            @endforelse
                            @endforeach
                        </p>
                    </td>
                    <td class="px-3 py-3 border-b border-gray-200 bg-white">
                        <p class="text-gray-900 whitespace-no-wrap">
                            {{$item->status}}
                        </p>
                    </td>
                    <td class="px-px py-3 border-b border-gray-200 bg-white">
                        @can('ver_departamento_producción')
                        <i wire:click="show( {{$item}} )" class="far fa-eye bg-sky-400 text-white p-2 rounded-lg hover:cursor-pointer"></i>
                        @endcan
                    </td>
                    <td class="py-3 border-b border-gray-200 bg-white">
                    </td>
                </tr>
                @endforeach
            </x-slot>
        </x-table>

        <!-- show modal -->
        @livewire('production-department.show-production-department')

        <!-- aditional modales -->

    </div>

</div>