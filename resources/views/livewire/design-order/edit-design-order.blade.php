<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Editar nueva orden de diseño
        </x-slot>

        <x-slot name="content">
            <div class="flex border rounded-full overflow-hidden m-4 text-xs">
                <div class="py-2 my-auto px-5 bg-blue-500 text-white font-semibold mr-3">
                    Cliente
                </div>
                <label class="flex items-center radio p-2 cursor-pointer">
                    <input wire:model="new_customer" value="1" class="my-auto" type="radio" name="n-customer" />
                    <div class="px-2">Nuevo</div>
                </label>

                <label class="flex items-center radio p-2 cursor-pointer">
                    <input wire:model="new_customer" value="0" class="my-auto" type="radio" name="n-customer" />
                    <div class="px-2">Registrado</div>
                </label>
            </div>
            @if(!$new_customer)
            <div>
                @livewire('customer.search-customer')
                @if($customer)
                <div class="grid grid-cols-2 gap-2 text-xs mt-2 font-bold">
                    <p>Razón social: <span class="font-normal">{{ $customer->company->bussiness_name }}</span></p>
                    <p>RFC: <span class="font-normal">{{ $customer->company->rfc }}</span></p>
                    <p>Sucursal: <span class="font-normal">{{ $customer->name }}</span></p>
                    <p>Método de pago: <span class="font-normal">{{ $customer->satMethod->key }} - {{ $customer->satMethod->description }}</span></p>
                    <p>Medio de pago: <span class="font-normal">{{ $customer->satWay->key }} - {{ $customer->satWay->description }}</span></p>
                    <p>Uso de factura: <span class="font-normal">{{ $customer->satType->key }} - {{ $customer->satType->description }}</span></p>
                    <p class="col-span-2">Dirección: <span class="font-normal">{{ $customer->address }} - C.P.{{ $customer->post_code }}</span></p>
                    <div class="col-span-2 flex flex-col">
                        <x-jet-label value="Contacto" class="mt-3" />
                        @foreach($customer->contacts as $contact)
                        @if($contact->model_name == "App\\Models\\".Customer::class)
                        <label class="flex items-center radio cursor-pointer">
                            <input wire:model="design_order.contact_id" value="{{$contact->id}}" type="radio" name="for" />
                            <div class="px-2">
                                <div class="col-span-2 flex flex-col lg:flex-row items-center text-sm mb-1 py-2 mx-6 border-b-2 lg:justify-center">
                                    <div>
                                        <i class="fas fa-user-circle mr-1"></i><span class="mr-2">{{ $contact->name }}</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-envelope mr-1"></i><span class="mr-2">{{ $contact->email }}</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-phone-alt mr-1"></i><span class="mr-2">{{ $contact->phone }}</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                        @endif
                        @endforeach
                        <x-jet-input-error for="design_order.contact_id" class="mt-2" />
                    </div>
                </div>
                @endif
                <x-jet-input-error for="customer" class="mt-2" />
            </div>
            @else
            <div class="lg:grid lg:grid-cols-2 lg:gap-3">
                <div>
                    <x-jet-label value="Cliente" class="mt-3" />
                    <x-jet-input wire:model.defer="design_order.customer_name" type="text" class="w-full mt-2" />
                    <x-jet-input-error for="design_order.customer_name" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Contacto" class="mt-3" />
                    <x-jet-input wire:model.defer="design_order.contact_name" type="text" class="w-full mt-2" />
                    <x-jet-input-error for="design_order.contact_name" class="mt-3" />
                </div>
            </div>
            @endif
            <div>
                <x-jet-label value="Diseñador(a)" class="mt-3" />
                <x-select class="mt-2 w-full" wire:model.defer="design_order.designer_id">
                    <option value="" selected>-- Seleccione --</option>
                    @forelse($designers as $designer)
                    <option value="{{ $designer->id }}">{{ $designer->name }}</option>
                    @empty
                    <option value="">No hay ningun(a) diseñador(a) registrado(a)</option>
                    @endforelse
                </x-select>
                <x-jet-input-error for="design_order.designer_id" class="mt-3" />
            </div>
            <div class="lg:grid lg:grid-cols-2 lg:gap-3">
                <div>
                    <x-jet-label value="Nombre del diseño" class="mt-3" />
                    <x-jet-input wire:model.defer="design_order.design_name" type="text" class="w-full mt-2" />
                    <x-jet-input-error for="design_order.design_name" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Clasificación" class="mt-3" />
                    <x-select class="mt-2 w-full" wire:model.defer="design_order.design_type_id">
                        <option value="" selected>-- Seleccione --</option>
                        @forelse($design_types as $design_type)
                        <option value="{{ $design_type->id }}">{{ $design_type->name }}</option>
                        @empty
                        <option value="">No hay ninguna clasificación registrada</option>
                        @endforelse
                    </x-select>
                    <x-jet-input-error for="design_order.design_type_id" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Medidas" class="mt-3" />
                    <x-jet-input wire:model.defer="design_order.dimentions" placeholder="ancho X largo X alto" type="text" class="w-full mt-2 placeholder:text-xs" />
                    <x-jet-input-error for="design_order.dimentions" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Unidad" class="mt-3" />
                    <x-select class="mt-2 w-full" wire:model.defer="design_order.measurement_unit_id">
                        <option value="" selected>-- Seleccione --</option>
                        @forelse($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @empty
                        <option value="">No hay ninguna unidad de medida registrada</option>
                        @endforelse
                    </x-select>
                    <x-jet-input-error for="design_order.measurement_unit_id" class="mt-3" />
                </div>
                <div class="col-span-2">
                    <x-jet-label value="Pantones" class="mt-3" />
                    <x-jet-input wire:model.defer="design_order.pantones" type="text" class="w-full mt-2" />
                    <x-jet-input-error for="design_order.pantones" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Datos" class="mt-3" />
                    <textarea wire:model.defer="design_order.design_data" rows="5" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
                    <x-jet-input-error for="design_order.design_data" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Requerimientos/especificaciones" class="mt-3" />
                    <textarea wire:model.defer="design_order.especifications" rows="5" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
                    <x-jet-input-error for="design_order.especifications" class="mt-3" />
                </div>
                <x-image-uploader :image="$plans_image" :imageExtensions="$image_extensions" :imageId="$plans_image_id" label="Imagen de plano" model="plans_image" :registeredImage="$design_order->plans_image ?? null" class="col-span-2" />
                <x-image-uploader :image="$logo_image" :imageExtensions="$image_extensions" :imageId="$logo_image_id" :showAlerts="false" label="Imagen de logo" model="logo_image" :registeredImage="$design_order->logo_image ?? null" class="col-span-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>
            @if(Auth::user()->can('autorizar_ordenes_diseño') && !$design_order->authorized_user_id)
            <x-jet-button wire:click="authorize" wire:loading.attr="disabled" wire:target="authorize" class="disabled:opacity-25 mr-2">
                Autorizar
            </x-jet-button>
            @endif
            <x-jet-button wire:click="update" wire:loading.attr="disabled" wire:target="update, plans_image, logo_image" class="disabled:opacity-25">
                Actualizar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>