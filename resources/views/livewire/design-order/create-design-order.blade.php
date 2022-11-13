<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    @can('crear_ordenes_diseño')
        <x-jet-button wire:click="openModal">
            + nuevo
        </x-jet-button>
    @endcan

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Crear nueva orden de diseño
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
                        <x-jet-label value="Cliente" class="mt-3 dark:text-gray-400" />
                        <x-jet-input wire:model.defer="customer_name" type="text" class="w-full mt-2 input" />
                        <x-jet-input-error for="customer_name" class="text-xs" />
                    </div>
                    <div>
                        <x-jet-label value="Contacto" class="mt-3 dark:text-gray-400" />
                        <x-jet-input wire:model.defer="contact_name" type="text" class="w-full mt-2 input" />
                        <x-jet-input-error for="contact_name" class="text-xs" />
                    </div>
                </div>
            @endif
            <div>
                <x-jet-label value="Diseñador(a)" class="mt-3 dark:text-gray-400" />
                <x-select class="mt-2 w-full input" wire:model.defer="designer_id" :options="$designers" />
                <x-jet-input-error for="designer_id" class="text-xs" />
            </div>
            <div class="lg:grid lg:grid-cols-2 lg:gap-3">
                <div>
                    <x-jet-label value="Nombre del diseño" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="design_name" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="design_name" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Clasificación" class="mt-3 dark:text-gray-400" />
                    <x-select class="mt-2 w-full input" wire:model.defer="design_type_id" :options="$design_types" />
                    <x-jet-input-error for="design_type_id" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Medidas" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="dimentions" placeholder="ancho X largo X alto" type="text"
                        class="w-full mt-2 placeholder:text-xs input" />
                    <x-jet-input-error for="dimentions" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Unidad" class="mt-3 dark:text-gray-400" />
                    <x-select class="mt-2 w-full input" wire:model.defer="measurement_unit_id" :options="$units" />
                    <x-jet-input-error for="measurement_unit_id" class="text-xs" />
                </div>
                <div class="col-span-2">
                    <x-jet-label value="Pantones" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="pantones" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="pantones" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Datos" class="mt-3 dark:text-gray-400" />
                    <textarea wire:model.defer="design_data" rows="5"
                        class="input !h-[6rem] w-full"></textarea>
                    <x-jet-input-error for="design_data" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Requerimientos/especificaciones" class="mt-3 dark:text-gray-400" />
                    <textarea wire:model.defer="especifications" rows="5"
                        class="input !h-[6rem] w-full"></textarea>
                    <x-jet-input-error for="especifications" class="text-xs" />
                </div>
                <x-image-uploader :image="$plans_image" :imageExtensions="$image_extensions" :imageId="$plans_image_id" label="Imagen de plano"
                    model="plans_image" class="col-span-2" />
                <x-image-uploader :image="$logo_image" :imageExtensions="$image_extensions" :imageId="$logo_image_id" :showAlerts="false"
                    label="Imagen de logo" model="logo_image" class="col-span-2" />
            </div>


        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="store" wire:loading.attr="disabled" wire:target="store, plans_image, logo_image"
                class="disabled:opacity-25">
                Crear
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>
