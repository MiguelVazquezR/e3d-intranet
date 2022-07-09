<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Orden de diseño {{ $design_order->id }}
        </x-slot>

        <x-slot name="content">

            @if ($design_order->id)
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
                            <x-product-quick-view :image="$design_result->image">
                                <span class="text-gray-500">{{ $design_result->notes }}</span>
                            </x-product-quick-view>
                        </x-item-list>
                    @endforeach
                @else
                    <div class="col-span-2">
                        <x-jet-label value="Entrega estimada" class="mt-3" />
                        <x-jet-input wire:model.defer="tentative_end" type="datetime-local" class="w-full mt-2" />
                        <x-jet-input-error for="tentative_end" class="text-xs" />
                    </div>
                @endif
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
