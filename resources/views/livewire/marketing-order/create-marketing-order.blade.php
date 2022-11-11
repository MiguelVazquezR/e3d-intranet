<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    @can('crear_ordenes_mercadotecnia')
        <x-jet-button wire:click="openModal">
            + nuevo
        </x-jet-button>
    @endcan

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Crear nueva orden de mercadotecnia
        </x-slot>

        <x-slot name="content">
            <x-my-radio :options="['Registrado', 'Nuevo']" label="Cliente" model="new_customer" />
            @if (!$new_customer)
                <div>
                    @livewire('customer.search-customer')
                    @if ($customer)
                        <x-customer-card :customer="$customer" />
                    @endif
                    <x-jet-input-error for="customer" class="text-xs" />
                </div>
            @else
                <div class="lg:grid lg:grid-cols-2 lg:gap-3">
                    <div>
                        <x-jet-label value="Cliente" class="mt-3" />
                        <x-jet-input wire:model.defer="customer_name" type="text" class="w-full mt-2" />
                        <x-jet-input-error for="customer_name" class="text-xs" />
                    </div>
                </div>
            @endif
            <div class="lg:grid lg:grid-cols-2 lg:gap-3">
                <div class="mt-3">
                    <x-jet-label value="Nombre de la orden" />
                    <x-jet-input wire:model.defer="order_name" type="text" class="w-full mt-2" />
                    <x-jet-input-error for="order_name" class="text-xs" />
                </div>
                <div class="mt-3">
                    <x-jet-label value="Clasificación" />
                    <select class="mt-2 w-full input" wire:model.defer="order_type">
                        <option selected>-- Seleccione --</option>
                        <option>Foto</option>
                        <option>Video</option>
                        <option>Brochure</option>
                        <option>Archivo editable</option>
                    </select>
                    <x-jet-input-error for="order_type" class="text-xs" />
                </div>
                <div class="mt-3 col-span-full">
                    <x-jet-label value="Requerimientos/especificaciones" />
                    <textarea wire:model.defer="especifications" rows="5"
                        placeholder="ángulos de fotos, color de fondo, medidas, duración de video, páginas, etc."
                        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full placeholder:text-sm placeholder:text-gray-400"></textarea>
                    <x-jet-input-error for="especifications" class="text-xs" />
                </div>
            </div>


        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="store" wire:loading.attr="disabled" wire:target="store"
                class="disabled:opacity-25">
                Crear
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
