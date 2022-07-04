<div>

    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            @if ($sell_order)
                Envío parcial para
                OV-{{ str_pad($sell_order->id, 4, '0', STR_PAD_LEFT) }}
            @endif
        </x-slot>

        <x-slot name="content">
            @if ($sell_order)
                <x-jet-label value="Paquetes" class="mt-3" />
                @foreach ($packages_list as $i => $package)
                    <x-item-list :index="$i" :active="true" :objectId="$package['id']" :edit="false"
                        :delete="false">
                        <x-product-quick-view name="{{ $i + 1 }}:">
                            <input wire:model="selected_packages" type="checkbox" value="{{ $package['id'] }}"
                                class="rounded mr-2">
                            <i class="fas fa-box mr-1"></i><span
                                class="mr-2 text-xs">{{ $package['large'] }}x{{ $package['width'] }}x{{ $package['height'] }}cm
                                - {{ $package['weight'] }}kg</span> ({{ $package['quantity'] }} unidades)
                            <div x-data="{ open_tooltip: false }" class="flex items-center ml-3">
                                <span @click="open_tooltip = !open_tooltip"
                                    class="fas fa-images hover:cursor-pointer relative">
                                    <div @click.away="open_tooltip=false" x-show="open_tooltip" x-transition
                                        class="absolute w-60 z-10 right-full top-0 m-1 cursor-default">
                                        <div
                                            class="flex justify-between bg-black text-white text-xs rounded py-1 px-2 font-sans">
                                            <img class="rounded-2xl w-24"
                                                src="{{ Storage::url($package['inside_image']) }}">
                                            <img class="rounded-2xl w-24"
                                                src="{{ Storage::url($package['outside_image']) }}">
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </x-product-quick-view>
                    </x-item-list>
                @endforeach
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>
            @if ($selected_packages)
                <x-jet-button wire:click="shipment" wire:loading.attr="disabled" wire:target="shipment"
                    class="disabled:opacity-25">
                    Enviar
                </x-jet-button>
            @endif
        </x-slot>
    </x-jet-dialog-modal>

</div>
