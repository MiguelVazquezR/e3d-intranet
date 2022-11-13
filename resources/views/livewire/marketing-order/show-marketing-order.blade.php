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
                            <x-jet-label value="Nombre de orden" class="mt-3" />
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
                    @if (count($marketing_results_list))
                        <h2 class="text-center font-bold text-lg text-sky-600 mt-3 mb-2 flex items-center">Resultados
                        </h2>
                    @endif
                    @foreach ($marketing_results_list as $i => $marketing_result)
                        <div class="py-2 border-b">
                            <x-item-quick-view :image="$marketing_result->external_link
                                ? asset('images/file-extensions/link.png')
                                : asset('images/file-extensions/file.png')" :src="$marketing_result->external_link
                                ? $marketing_result->external_link
                                : $marketing_result->media->getUrl()">
                                <span class="text-gray-500 w-2/3">{{ $marketing_result->notes }}</span>
                                <strong class="text-gray-600 text-xs w-1/3 ml-2 border-l pl-2">subido por
                                    {{ $marketing_result->creator->name }}
                                    ({{ $marketing_result->created_at->isoFormat('DD MMM, YYYY - hh:mm a') }})</strong>
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
            @if (!$marketing_order->modified_id)
                <x-jet-button wire:click="requestModifications" wire:loading.attr="disabled"
                    wire:target="requestModifications, plans_image, logo_image" class="disabled:opacity-25">
                    Solicitar modificaciones
                </x-jet-button>
            @endif
        </x-slot>

    </x-jet-dialog-modal>
</div>
