<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Orden de diseño {{ $design_order->id }}
            @if ($design_order->isLate())
                <span class="text-red-500 ml-2">(Pedido con retraso)</span>
            @endif
        </x-slot>

        <x-slot name="content">
            @if ($design_order->isLate())
                <div class="w-full flex justify-between mx-auto bg-red-100 rounded-lg p-4 my-6 text-sm font-medium text-red-700"
                    role="alert">
                    <div class="w-full flex">
                        <i class="fas fa-exclamation-circle w-5 h-5 inline mr-3"></i>
                        <div>
                            El pedido tiene retraso cuando se rebasa la fecha tentativa de entrega establecida o cuando
                            se excede
                            en un 10% el tiempo promedio por actividad (tiempos calculados con base a muchas ordenes de
                            diseño).
                        </div>
                    </div>
                </div>
            @endif
            @if ($design_order->id)
                <div wire:loading.remove wire:target="seeOrder">
                    <div class="flex justify-between text-lg">
                        @if ($design_order->original_id)
                            <div class="flex items-center text-blue-500 cursor-pointer"
                                wire:click="seeOrder({{ $design_order->original_id }})">
                                <i class="fas fa-long-arrow-alt-left mr-2"></i>
                                <span>Ver orden original</span>
                            </div>
                        @endif
                        @if ($design_order->modified_id)
                            <div class="flex items-center text-blue-500 cursor-pointer"
                                wire:click="seeOrder({{ $design_order->modified_id }})">
                                <span>Ver orden con modificaciones</span>
                                <i class="fas fa-long-arrow-alt-right ml-2"></i>
                            </div>
                        @endif
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <x-jet-label value="Solicitante" class="mt-3" />
                            <p>{{ $design_order->creator->name }}</p>
                        </div>
                        <div>
                            <x-jet-label value="solicitado el" class="mt-3" />
                            <p>{{ $design_order->created_at->isoFormat('DD MMMM YYYY') }}</p>
                        </div>
                        <div>
                            <x-jet-label value="Cliente" class="mt-3" />
                            @if ($design_order->customer)
                                <p>{{ $design_order->customer->name }}</p>
                            @else
                                <p>{{ $design_order->customer_name }}</p>
                            @endif
                        </div>
                        <div>
                            <x-jet-label value="Contacto" class="mt-3" />
                            @if ($design_order->contact)
                                <p>{{ $design_order->contact->name }}</p>
                            @else
                                <p>{{ $design_order->contact_name }}</p>
                            @endif
                        </div>
                        <div>
                            <x-jet-label value="Nombre de diseño" class="mt-3" />
                            <p>{{ $design_order->design_name }}</p>
                        </div>
                        <div>
                            <x-jet-label value="Clasificación" class="mt-3" />
                            <p>{{ $design_order->designType->name }}</p>
                        </div>
                        <div>
                            <x-jet-label value="Datos" class="mt-3" />
                            <p>{{ $design_order->design_data }}</p>
                        </div>
                        <div>
                            <x-jet-label value="Especificaciones" class="mt-3" />
                            <p>{{ $design_order->especifications }}</p>
                        </div>
                        <div>
                            <x-jet-label value="Dimensiones" class="mt-3" />
                            <p>{{ $design_order->dimentions . ' ' . $design_order->unit->name }}</p>
                        </div>
                        <div>
                            <x-jet-label value="Pantones" class="mt-3" />
                            <p>{{ $design_order->pantones }}</p>
                        </div>
                        <div>
                            <x-jet-label value="Diseñador" class="mt-3" />
                            <p>{{ $design_order->designer->name }}</p>
                        </div>
                        <div>
                            <x-jet-label value="Estimado de entrega" class="mt-3" />
                            <p>
                                @if ($design_order->tentative_end)
                                    {{ $design_order->tentative_end->isoFormat('DD MMMM hh:mm a') }}
                                @else
                                    --
                                @endif
                            </p>
                        </div>
                        <div>
                            <x-jet-label value="Imágenes" class="mt-3" />
                            @if ($design_order->plans_image)
                                <a href="{{ Storage::url($design_order->plans_image) }}" target="_blank"
                                    class="text-blue-500 hover:underline">Planos</a>
                            @endif
                            @if ($design_order->logo_image)
                                <a href="{{ Storage::url($design_order->logo_image) }}" target="_blank"
                                    class="ml-3 text-blue-500 hover:underline">Logo</a>
                            @endif
                        </div>
                        <div>
                            <x-jet-label value="Autorizado" class="mt-3" />
                            @if ($design_order->authorizedBy)
                                <p class="text-green-600">
                                    {{ $design_order->authorizedBy->name . ' el ' . $design_order->authorized_at->isoFormat('DD MMMM hh:mm a') }}
                                </p>
                            @else
                                <p class="text-red-600">Sin autorización</p>
                            @endif
                        </div>
                        <div>
                            <x-jet-label value="Status" class="mt-3" />
                            <p>{{ $design_order->status }}</p>
                        </div>
                    </div>
                    @if ($design_order->tentative_end)
                        <h2 class="text-center font-bold text-lg text-sky-600 mt-3 flex items-center justify-center">
                            Resultados
                            <i wire:click="addDesignResult"
                                class="fas fa-plus-circle text-green-600 hover:cursor-pointer ml-3"></i>
                        </h2>
                        <x-jet-input-error for="design_results_list" class="text-xs" />
                        @foreach ($design_results_list as $i => $design_result)
                            <x-item-list :index="$i" :active="true" :edit="false" :objectId="null">
                                <x-item-quick-view :image="in_array($design_result->file_extension, $image_extensions)
                                    ? Storage::url($design_result->image)
                                    : asset('images/file-extensions/' . $design_result->file_extension . '.png')" :src="Storage::url($design_result->image)">
                                    <span class="text-gray-500 w-2/3">{{ $design_result->notes }}</span>
                                    <strong class="text-gray-600 text-xs w-1/3 ml-2 border-l pl-2">subido el
                                        ({{ $design_result->created_at->isoFormat('DD MMM, YYYY - hh:mm a') }})
                                    </strong>
                                </x-item-quick-view>
                            </x-item-list>
                        @endforeach
                    @else
                        <div x-data="{ open: true }" x-show="open"
                            class="w-11/12 flex justify-between mx-auto dark:bg-blue-300 bg-blue-100 rounded-lg p-4 my-6 text-sm font-medium text-blue-700"
                            role="alert">
                            <div class="w-11/12 flex">
                                <i class="fas fa-exclamation-circle w-5 h-5 inline mr-3"></i>
                                <div>
                                    Al establecer fecha estimada de entrega, se entenderá que ya se comenzará a trabajar
                                    en el pedido.
                                </div>
                            </div>

                            <i @click="open = false" class="fal fa-times text-right hover:cursor-pointer"></i>
                        </div>
                        <div class="col-span-2">
                            <x-jet-label value="Entrega estimada" class="mt-3 dark:text-gray-400" />
                            <x-jet-input wire:model.defer="tentative_end" type="datetime-local" class="w-full mt-2 input" />
                            <x-jet-input-error for="tentative_end" class="text-xs" />
                        </div>
                        <label class="inline-flex items-center mt-3 text-xs dark:text-gray-400">
                            <input wire:model.defer="is_complex" type="checkbox" value="1" class="rounded">
                            <span class="ml-1 text-gray-700 dark:text-gray-500">Es un diseño complicado</span>
                        </label>
                        <div class="mt-1">
                            <x-jet-label class="dark:text-gray-400" value="Si tienes archivos que puedas reutilizar, ¿qué porcentaje tienes de la orden?" />
                            <div class="flex items-center space-x-2">
                                <input wire:model="reuse" class="outline-none" type="range" step="5"
                                    min="0" max="100">
                                <span wire:loading wire:target="reuse"
                                    class="text-xs text-blue-400">calculando...</span>
                                <span wire:loading.remove wire:target="reuse"
                                    class="text-xs text-blue-400 font-bold">{{ $reuse }}%</span>
                            </div>
                        </div>
                    @endif
                </div>
                <div wire:loading wire:target="seeOrder" class="text-gray-500 py-6">Cargando ...</div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cerrar
            </x-jet-secondary-button>
            @if ($design_order->id)
                @if (!$design_order->tentative_end)
                    <x-jet-button wire:click="storeTentativeEnd" wire:loading.attr="disabled"
                        wire:target="storeTentativeEnd" class="disabled:opacity-25">
                        Guardar fecha de entrega estimada
                    </x-jet-button>
                @endif
            @endif
        </x-slot>

    </x-jet-dialog-modal>
</div>
