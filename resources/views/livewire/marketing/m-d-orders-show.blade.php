<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Orden de mercadotecnia {{ $marketing_order->id }}
        </x-slot>

        <x-slot name="content">
            @if ($marketing_order->id)
                <div wire:loading.remove wire:target="seeOrder">
                    <div class="flex justify-between text-lg">
                        @if ($marketing_order->original_id)
                            <div class="flex items-center text-blue-500 cursor-pointer"
                                wire:click="seeOrder({{ $marketing_order->original_id }})">
                                <i class="fas fa-long-arrow-alt-left mr-2"></i>
                                <span>Ver orden original</span>
                            </div>
                        @endif
                        @if ($marketing_order->modified_id)
                            <div class="flex items-center text-blue-500 cursor-pointer"
                                wire:click="seeOrder({{ $marketing_order->modified_id }})">
                                <span>Ver orden con modificaciones</span>
                                <i class="fas fa-long-arrow-alt-right ml-2"></i>
                            </div>
                        @endif
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <x-jet-label value="Solicitante" class="mt-3" />
                            <p>{{ $marketing_order->creator->name }}</p>
                        </div>
                        <div>
                            <x-jet-label value="solicitado el" class="mt-3" />
                            <p>{{ $marketing_order->created_at->isoFormat('DD MMMM YYYY') }}</p>
                        </div>
                        <div>
                            <x-jet-label value="Cliente" class="mt-3" />
                            @if ($marketing_order->customer)
                                <p>{{ $marketing_order->customer->name }}</p>
                            @else
                                <p>{{ $marketing_order->customer_name }}</p>
                            @endif
                        </div>
                        <div>
                            <x-jet-label value="Nombre de diseño" class="mt-3" />
                            <p>{{ $marketing_order->order_name }}</p>
                        </div>
                        <div>
                            <x-jet-label value="Clasificación" class="mt-3" />
                            <p>{{ $marketing_order->order_type }}</p>
                        </div>
                        <div>
                            <x-jet-label value="Especificaciones" class="mt-3" />
                            <p>{{ $marketing_order->especifications }}</p>
                        </div>
                        <div>
                            <x-jet-label value="Estimado de entrega" class="mt-3" />
                            <p>
                                @if ($marketing_order->tentative_end)
                                    {{ $marketing_order->tentative_end->isoFormat('DD MMMM hh:mm a') }}
                                @else
                                    --
                                @endif
                            </p>
                        </div>
                        <div>
                            <x-jet-label value="Autorizado" class="mt-3" />
                            @if ($marketing_order->authorizedBy)
                                <p class="text-green-600">
                                    {{ $marketing_order->authorizedBy->name . ' el ' . $marketing_order->authorized_at->isoFormat('DD MMMM hh:mm a') }}
                                </p>
                            @else
                                <p class="text-red-600">Sin autorización</p>
                            @endif
                        </div>
                        <div>
                            <x-jet-label value="Status" class="mt-3" />
                            <p>{{ $marketing_order->status }}</p>
                        </div>
                    </div>
                    @if ($marketing_order->tentative_end)
                        <h2 class="text-center font-bold text-lg text-sky-600 mt-3 flex items-center justify-center">
                            Resultados
                            <i wire:click="addMarketingResult"
                                class="fas fa-plus-circle text-green-600 hover:cursor-pointer ml-3"></i>
                        </h2>
                        <x-jet-input-error for="marketing_results_list" class="text-xs" />
                        @foreach ($marketing_results_list as $i => $marketing_result)
                            <x-item-list :index="$i" :active="true" :edit="false" :objectId="null">
                                <x-item-quick-view :image="in_array($marketing_result->file_extension, $image_extensions)
                                    ? Storage::url($marketing_result->image)
                                    : asset('images/file-extensions/' . $marketing_result->file_extension . '.png')" :src="Storage::url($marketing_result->image)">
                                    <span class="text-gray-500">{{ $marketing_result->notes }}</span>
                                </x-item-quick-view>
                            </x-item-list>
                        @endforeach
                    @else
                        <div x-data="{ open: true }" x-show="open"
                            class="w-11/12 flex justify-between mx-auto bg-blue-100 rounded-lg p-4 my-6 text-sm font-medium text-blue-700"
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
                            <x-jet-label value="Entrega estimada" class="mt-3" />
                            <x-jet-input wire:model.defer="tentative_end" type="datetime-local" class="w-full mt-2" />
                            <x-jet-input-error for="tentative_end" class="text-xs" />
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
            @if ($marketing_order->id)
                @if (!$marketing_order->tentative_end)
                    <x-jet-button wire:click="storeTentativeEnd" wire:loading.attr="disabled"
                        wire:target="storeTentativeEnd" class="disabled:opacity-25">
                        Guardar fecha de entrega estimada
                    </x-jet-button>
                @endif
            @endif
        </x-slot>

    </x-jet-dialog-modal>
</div>
