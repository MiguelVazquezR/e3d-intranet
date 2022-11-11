<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Editar nueva orden de mercadotecnia
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
                        <x-jet-input wire:model.defer="marketing_order.customer_name" type="text" class="w-full mt-2" />
                        <x-jet-input-error for="marketing_order.customer_name" class="text-xs" />
                    </div>
                </div>
            @endif
            <div class="lg:grid lg:grid-cols-2 lg:gap-3">
                <div>
                    <x-jet-label value="Nombre de la orden" class="mt-3" />
                    <x-jet-input wire:model.defer="marketing_order.order_name" type="text" class="w-full mt-2" />
                    <x-jet-input-error for="marketing_order.order_name" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Clasificación" class="mt-3" />
                    <select class="mt-2 w-full input" wire:model.defer="marketing_order.order_type">
                        <option selected>-- Seleccione --</option>
                        <option>Foto</option>
                        <option>Video</option>
                        <option>Brochure</option>
                        <option>Archivo editable</option>
                    </select>
                    <x-jet-input-error for="marketing_order.order_type" class="text-xs" />
                </div>
                <div class="col-span-full">
                    <x-jet-label value="Requerimientos/especificaciones" class="mt-3" />
                    <textarea wire:model.defer="marketing_order.especifications" rows="5"
                        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
                    <x-jet-input-error for="marketing_order.especifications" class="text-xs" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>
            @if (auth()->user()->can('autorizar_ordenes_mercadotecnia') && !$marketing_order->authorized_user_id)
                <x-jet-button wire:click="authorize" wire:loading.attr="disabled" wire:target="authorize"
                    class="disabled:opacity-25 mr-2">
                    Autorizar
                </x-jet-button>
            @endif
            <x-jet-button wire:click="update" wire:loading.attr="disabled"
                wire:target="update" class="disabled:opacity-25">
                Actualizar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>
