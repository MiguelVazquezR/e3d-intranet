<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Editar nueva orden de diseño
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
                        <x-jet-input wire:model.defer="design_order.customer_name" type="text" class="w-full mt-2" />
                        <x-jet-input-error for="design_order.customer_name" class="text-xs" />
                    </div>
                    <div>
                        <x-jet-label value="Contacto" class="mt-3" />
                        <x-jet-input wire:model.defer="design_order.contact_name" type="text" class="w-full mt-2" />
                        <x-jet-input-error for="design_order.contact_name" class="text-xs" />
                    </div>
                </div>
            @endif
            <div>
                <x-jet-label value="Diseñador(a)" class="mt-3" />
                <x-select class="mt-2 w-full" wire:model.defer="design_order.designer_id" :options="$designers" />
                <x-jet-input-error for="design_order.designer_id" class="text-xs" />
            </div>
            <div class="lg:grid lg:grid-cols-2 lg:gap-3">
                <div>
                    <x-jet-label value="Nombre del diseño" class="mt-3" />
                    <x-jet-input wire:model.defer="design_order.design_name" type="text" class="w-full mt-2" />
                    <x-jet-input-error for="design_order.design_name" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Clasificación" class="mt-3" />
                    <x-select class="mt-2 w-full" wire:model.defer="design_order.design_type_id" :options="$design_types" />
                    <x-jet-input-error for="design_order.design_type_id" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Medidas" class="mt-3" />
                    <x-jet-input wire:model.defer="design_order.dimentions" placeholder="ancho X largo X alto"
                        type="text" class="w-full mt-2 placeholder:text-xs" />
                    <x-jet-input-error for="design_order.dimentions" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Unidad" class="mt-3" />
                    <x-select class="mt-2 w-full" wire:model.defer="design_order.measurement_unit_id" :options="$units" />
                    <x-jet-input-error for="design_order.measurement_unit_id" class="text-xs" />
                </div>
                <div class="col-span-2">
                    <x-jet-label value="Pantones" class="mt-3" />
                    <x-jet-input wire:model.defer="design_order.pantones" type="text" class="w-full mt-2" />
                    <x-jet-input-error for="design_order.pantones" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Datos" class="mt-3" />
                    <textarea wire:model.defer="design_order.design_data" rows="5"
                        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
                    <x-jet-input-error for="design_order.design_data" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Requerimientos/especificaciones" class="mt-3" />
                    <textarea wire:model.defer="design_order.especifications" rows="5"
                        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
                    <x-jet-input-error for="design_order.especifications" class="text-xs" />
                </div>
                <x-image-uploader :image="$plans_image" :imageExtensions="$image_extensions" :imageId="$plans_image_id" label="Imagen de plano"
                    model="plans_image" :registeredImage="$design_order->plans_image ?? null" class="col-span-2" />
                <x-image-uploader :image="$logo_image" :imageExtensions="$image_extensions" :imageId="$logo_image_id" :showAlerts="false"
                    label="Imagen de logo" model="logo_image" :registeredImage="$design_order->logo_image ?? null" class="col-span-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>
            @if (Auth::user()->can('autorizar_ordenes_diseño') && !$design_order->authorized_user_id)
                <x-jet-button wire:click="authorize" wire:loading.attr="disabled" wire:target="authorize"
                    class="disabled:opacity-25 mr-2">
                    Autorizar
                </x-jet-button>
            @endif
            <x-jet-button wire:click="update" wire:loading.attr="disabled"
                wire:target="update, plans_image, logo_image" class="disabled:opacity-25">
                Actualizar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>
