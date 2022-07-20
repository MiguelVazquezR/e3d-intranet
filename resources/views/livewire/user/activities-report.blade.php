<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Reporte de actividades
        </x-slot>

        <x-slot name="content">
            <div class="mt-3">
                <x-jet-label value="Filtro" />
                <select wire:model="filter" class="input">
                    <option value="1">Semana en curso</option>
                    <option value="2">Semana pasada</option>
                    <option value="3">Todo</option>
                </select>
            </div>
            <div class="mt-3">
                @if ($activities)
                    <div class="container p-2 mx-auto sm:p-4 text-gray-500">
                        <h2 class="mb-2 text-2xl font-semibold leading-tight">Actividades</h2>
                        <div
                            class="overflow-auto max-h-72 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100">
                            <table class="min-w-full text-xs">
                                <thead class="bg-white border">
                                    <tr class="text-left">
                                        <th class="p-3">Producto</th>
                                        <th class="p-3">Indicaciones</th>
                                        <th class="p-3 text-right">Cantidad</th>
                                        <th class="p-3">Tiempo estimado</th>
                                        <th class="p-3">Asignado el</th>
                                        <th class="p-3">Iniciado el</th>
                                        <th class="p-3">Terminado el</th>
                                        <th class="p-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($activities as $activity)
                                        <tr class="border border-opacity-20 border-gray-700 bg-white">
                                            <td class="p-3">
                                                @php
                                                    $product = $activity->sellOrderedProduct->productForSell->model_name::find($activity->sellOrderedProduct->productForSell->model_id);
                                                    if(get_class($product) == 'App\\Models\\Product'){
                                                        $product_name = $product->name;
                                                    } else{
                                                        $product_name = $product->alias;
                                                    }
                                                @endphp
                                                <p>{{ $product_name }}</p>
                                            </td>
                                            <td class="p-3">
                                                <p>{{ $activity->indications }}</p>
                                            </td>
                                            <td class="p-3 text-right">
                                                <p>{{ $activity->sellOrderedProduct->quantity }}</p>
                                            </td>
                                            <td class="p-3">
                                                <p>{{ $activity->estimated_time }} min.</p>
                                            </td>
                                            <td class="p-3">
                                                <p>{{ $activity->created_at->isoFormat('DD MMM YYYY hh:mm a') }}</p>
                                                <p class="text-gray-400">
                                                    {{ $activity->created_at->isoFormat('dddd') }}</p>
                                            </td>
                                            <td class="p-3">
                                                @if ($activity->start)
                                                    <p>{{ $activity->start->isoFormat('DD MMM YYYY hh:mm a') }}</p>
                                                    <p class="text-gray-400">
                                                        {{ $activity->start->isoFormat('dddd') }}</p>
                                                @else
                                                    <p>No iniciado</p>
                                                @endif
                                            </td>
                                            <td class="p-3">
                                                @if ($activity->finish)
                                                    <p>{{ $activity->finish->isoFormat('DD MMM YYYY hh:mm a') }}</p>
                                                    <p class="text-gray-400">
                                                        {{ $activity->finish->isoFormat('dddd') }}</p>
                                                @else
                                                    <p>No terminado</p>
                                                @endif
                                            </td>
                                            <td class="p-3 text-right">
                                                <span
                                                    class="px-3 py-1 font-semibold rounded-md bg-violet-300 text-gray-900">
                                                    <span>{{ $activity->status() }}</span>
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <p class="text-xs my-5 text-gray-500 text-center border rounded p-4">No hay actividades para mostrar
                    </p>
                @endif
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>
