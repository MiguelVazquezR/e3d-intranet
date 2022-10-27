<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
            <div class="flex items-center">
                <i class="fas fa-file-invoice-dollar mr-2"></i>
                Cotizaciones
            </div>
            @livewire('quote.create-quote')
        </h2>
    </x-slot>

    <div class="py-6">

        <div wire:loading wire:target="edit">
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
                <select class="mt-2 input" wire:model="elements">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

        <!-- table -->
        <x-table :models="$quotes" :sort="$sort" :direction="$direction" :columns="$table_columns">
            <x-slot name="body">
                @foreach ($quotes as $item)
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
                                @if ($item->customer)
                                    {{ $item->customer->name }}
                                @else
                                    {{ $item->customer_name }}
                                @endif
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">
                                @if ($item->authorized_by)
                                    <span
                                        class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                        <span aria-hidden
                                            class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                        <span class="relative">
                                            {{ $item->authorized_by->name }}
                                        </span>
                                    </span>
                                @else
                                    <span
                                        class="relative inline-block px-3 py-1 font-semibold text-red-900 leading-tight">
                                        <span aria-hidden
                                            class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                                        <span class="relative">
                                            Esperando autorización
                                        </span>
                                    </span>
                                @endif
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            {{ $item->created_at->diffForHumans() }}
                        </td>
                        <td class="w-28 px-px py-3 border-b border-gray-200 bg-white">
                            @can('ver_cotizaciones')
                                @php
                                   $hash = ($item->id + 990) * 480 + 880; 
                                @endphp
                                <a href="{{ route('quote-pdf', ['item' => $hash]) }}" target="_blank"><i
                                        class="far fa-eye bg-sky-400 text-white p-2 rounded-lg hover:cursor-pointer"></i></a>
                            @endcan
                            @can('editar_cotizaciones')
                                <i wire:click="edit({{ $item }})"
                                    class="far fa-edit bg-blue-500 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                            @endcan
                            @can('eliminar_cotizaciones')
                                <i wire:click="$emit('confirm', {0:'quote.quotes', 1:'delete' ,2:{{ $item->id }}, 3:'Este proceso no se puede revertir'})"
                                    class="fas fa-trash bg-red-500 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                            @endcan
                        </td>
                        <td class="py-3 border-b border-gray-200 bg-white">
                            @can('más_acciones_cotizaciones')
                                <x-jet-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <i
                                            class="fas fa-ellipsis-v text-white p-1 rounded-lg hover:cursor-pointer bg-gray-400 ml-px mt-1"></i>
                                    </x-slot>

                                    <x-slot name="content">
                                        @if (is_null($item->sellOrder))
                                            <x-jet-dropdown-link wire:click="turnIntoSellOrder( {{ $item }} )"
                                                :link="false">
                                                Convertir en orden de venta
                                            </x-jet-dropdown-link>
                                        @else
                                            <x-jet-dropdown-link href="{{ route('sell-orders') }}" class="text-blue-400">
                                                Cotización relacionada con OV: {{ $item->sell_order_id }}
                                            </x-jet-dropdown-link>
                                        @endif
                                        <x-jet-dropdown-link wire:click="clone( {{ $item }} )"
                                            :link="false">
                                            Clonar
                                        </x-jet-dropdown-link>
                                    </x-slot>
                                </x-jet-dropdown>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        </x-table>

        <!-- edit -->
        @livewire('quote.edit-quote')

        <!-- aditional modals -->
        @livewire('currency.create-currency')
        @livewire('quote.turn-into-sell-order')

    </div>

</div>
