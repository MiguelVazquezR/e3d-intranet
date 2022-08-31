<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Orden de diseño {{ $design_order->id }}
        </x-slot>

        <x-slot name="content">

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
                    @if (count($design_results_list))
                        <h2 class="text-center font-bold text-lg text-sky-600 mt-3 mb-2 flex items-center">Resultados
                        </h2>
                    @endif
                    @foreach ($design_results_list as $i => $design_result)
                        <div class="py-2 border-b">
                            <x-item-quick-view :image="in_array($design_result->file_extension, $image_extensions)
                                ? Storage::url($design_result->image)
                                : asset('images/file-extensions/' . $design_result->file_extension . '.png')" :src="Storage::url($design_result->image)">
                                <span class="text-gray-500">{{ $design_result->notes }}</span>
                            </x-item-quick-view>
                        </div>
                    @endforeach
                </div>
                <div wire:loading wire:target="seeOrder" class="text-gray-500 py-6">Cargando ...</div>
            @endif

        </x-slot>

        <x-slot name="footer">

            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cerrar
            </x-jet-secondary-button>
            @if (!$design_order->modified_id)
                <x-jet-button wire:click="requestModifications" wire:loading.attr="disabled"
                    wire:target="requestModifications, plans_image, logo_image" class="disabled:opacity-25">
                    Solicitar modificaciones
                </x-jet-button>
            @endif
        </x-slot>

    </x-jet-dialog-modal>
</div>
