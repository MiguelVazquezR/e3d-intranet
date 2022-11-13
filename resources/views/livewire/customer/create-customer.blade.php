<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    @can('crear_clientes')
    <x-jet-button wire:click="openModal">
        + nuevo
    </x-jet-button>
    @endcan

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Crear nuevo cliente
        </x-slot>

        <x-slot name="content">
            <h2 class="text-center font-bold text-lg text-sky-600">Datos fiscales de empresa</h2>
            <div>
                <x-jet-label value="Razón social" class="mt-3 dark:text-gray-400" />
                <x-jet-input wire:model.defer="bussiness_name" type="text" class="w-full mt-2 input" />
                <x-jet-input-error for="bussiness_name" class="text-xs" />
            </div>
            <div class="lg:grid lg:grid-cols-2 lg:gap-x-2">
                <div>
                    <x-jet-label value="Teléfono" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="phone" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="phone" class="text-xs" />
                </div>

                <div>
                    <x-jet-label value="RFC" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="rfc" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="rfc" class="text-xs" />
                </div>
            </div>

            <div class="lg:grid lg:grid-cols-4 lg:gap-x-2">
                <div class="lg:col-span-3">
                    <x-jet-label value="Dirección" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="fiscal_address" type="text" class="w-full mt-2 placeholder:text-xs input" placeholder="calle, colonia, # interior y/o exterior, ciudad, estado, país" />
                    <x-jet-input-error for="fiscal_address" class="text-xs" />
                </div>

                <div>
                    <x-jet-label value="C.P." class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="post_code" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="post_code" class="text-xs" />
                </div>
            </div>

            <h2 wire:click="$set('add_branch', {{!$add_branch}})" class="hover:cursor-pointer text-center font-bold text-lg text-sky-600 mt-2">
                Sucursales
                @if($add_branch)
                <i class="fas fa-angle-up ml-1 text-gray-800 dark:text-gray-500"></i>
                @else
                <i class="fas fa-angle-down ml-1 text-gray-800 dark:text-gray-500"></i>
                @endif
            </h2>

            <!-- agregar sucursal -->
            @if( $add_branch || !is_null($edit_branch_index) )
            <div class="p-3 bg-sky-100 dark:bg-sky-300 my-2 rounded-2xl">
                <div class="flex justify-end">
                    @if( is_null($edit_branch_index) )
                    <i wire:click="addBranchToList" class="fas fa-plus-circle text-green-600 hover:cursor-pointer"></i>
                    @else
                    <div>
                        <i wire:click="updateBranchFromList" class="fas fa-check-circle text-green-600 hover:cursor-pointer mr-1"></i>
                        <i wire:click="closeEditBranch" class="fas fa-times hover:cursor-pointer"></i>
                    </div>
                    @endif
                </div>

                <div>
                    <x-jet-label value="Nombre" class="mt-1 dark:text-gray-600" />
                    <x-jet-input wire:model.defer="name" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="name" class="text-xs" />
                </div>

                <div class="lg:grid lg:grid-cols-4 lg:gap-x-2">
                    <div class="lg:col-span-3">
                        <x-jet-label value="Dirección" class="mt-3 dark:text-gray-600" />
                        <x-jet-input wire:model.defer="address" type="text" class="w-full mt-2 placeholder:text-xs input" placeholder="calle, colonia, # interior y/o exterior, ciudad, estado, país" />
                        <x-jet-input-error for="address" class="text-xs" />
                    </div>
                    <div>
                        <x-jet-label value="C.P." class="mt-3 dark:text-gray-600" />
                        <x-jet-input wire:model.defer="branch_post_code" type="text" class="w-full mt-2 input" />
                        <x-jet-input-error for="branch_post_code" class="text-xs" />
                    </div>
                </div>

                <x-jet-label value="Método de pago" class="mt-3 dark:text-gray-600" />
                <select class="input mt-2 w-3/4 input" wire:model.defer="sat_method_id">
                    <option value="" selected>--- Seleccione ---</option>
                    @forelse($sat_methods as $method)
                    <option value="{{ $method->id }}">{{ $method->key .' - '. $method->description }}</option>
                    @empty
                    <option value="">No hay ningun método registrado</option>
                    @endforelse
                </select>
                <x-jet-secondary-button class="ml-2 rounded-full" wire:click="$emitTo('sat-method.create-sat-method', 'openModal')">
                    <i class="fas fa-plus"></i>
                </x-jet-secondary-button>
                <x-jet-input-error for="sat_method_id" class="text-xs" />

                <x-jet-label value="Medio de pago" class="mt-3 dark:text-gray-600" />
                <select class="input mt-2 w-3/4 input" wire:model.defer="sat_way_id">
                    <option value="" selected>--- Seleccione ---</option>
                    @forelse($sat_ways as $way)
                    <option value="{{ $way->id }}">{{ $way->key .' - '. $way->description }}</option>
                    @empty
                    <option value="">No hay ningun medio registrado</option>
                    @endforelse
                </select>
                <x-jet-secondary-button class="ml-2 rounded-full" wire:click="$emitTo('sat-way.create-sat-way', 'openModal')">
                    <i class="fas fa-plus"></i>
                </x-jet-secondary-button>
                <x-jet-input-error for="sat_way_id" class="text-xs" />

                <x-jet-label value="Uso de factura" class="mt-3 dark:text-gray-600" />
                <x-select class="mt-2 w-3/4 input" wire:model.defer="sat_type_id" :options="$sat_types" name="description" />
                <x-jet-secondary-button class="ml-2 rounded-full" wire:click="$emitTo('sat-type.create-sat-type', 'openModal')">
                    <i class="fas fa-plus"></i>
                </x-jet-secondary-button>
                <x-jet-input-error for="sat_type_id" class="text-xs" />

                <!-- Contacts -->
                <h2 wire:click="$set('add_contact', {{!$add_contact}})" class="text-center font-bold text-lg text-sky-600 my-2 hover:cursor-pointer">
                    Contactos
                    @if($add_contact)
                    <i class="fas fa-angle-up ml-1 text-gray-800"></i>
                    @else
                    <i class="fas fa-angle-down ml-1 text-gray-800"></i>
                    @endif
                </h2>
                @if($add_contact || !is_null($edit_contact_index) )
                <div class="p-3 bg-sky-200 dark:bg-sky-400 my-1 rounded-2xl">
                    <div class="flex justify-end">
                        @if( is_null($edit_contact_index) )
                        <i wire:click="addContactToList" class="fas fa-plus-circle text-green-600 hover:cursor-pointer"></i>
                        @else
                        <div>
                            <i wire:click="updateContactFromList" class="fas fa-check-circle text-green-600 hover:cursor-pointer mr-1"></i>
                            <i wire:click="closeEditContact" class="fas fa-times hover:cursor-pointer"></i>
                        </div>
                        @endif
                    </div>

                    <div>
                        <x-jet-label value="Nombre" class="mt-1" />
                        <x-jet-input wire:model.defer="contact_name" type="text" class="w-full mt-2" />
                        <x-jet-input-error for="contact_name" class="text-xs" />
                    </div>

                    <div class="lg:grid lg:grid-cols-2 lg:gap-x-2">
                        <div>
                            <x-jet-label value="Correo" class="mt-3" />
                            <x-jet-input wire:model.defer="email" type="text" class="w-full mt-2 placeholder:text-xs" />
                            <x-jet-input-error for="email" class="text-xs" />
                        </div>
                        <div>
                            <x-jet-label value="Teléfono" class="mt-3" />
                            <x-jet-input wire:model.defer="contact_phone" type="text" class="w-full mt-2" />
                            <x-jet-input-error for="contact_phone" class="text-xs" />
                        </div>
                        <div class="col-span-full">
                            <x-jet-label value="Cumpleaños" class="mt-3" />
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <x-jet-label value="Día" class="mt-3" />
                                    <select class="input mt-2 w-full" wire:model.defer="day">
                                        <option value="">-- Seleccione --</option>
                                        @for ($day = 1; $day <= 31; $day++) <option value="{{ $day }}">{{ $day }}</option>
                                            @endfor
                                    </select>
                                    <x-jet-input-error for="day" class="text-xs" />
                                </div>
                                <div>
                                    <x-jet-label value="Mes" class="mt-3" />
                                    <select class="input mt-2 w-full" wire:model.defer="month">
                                        <option value="">-- Seleccione --</option>
                                        @foreach($months as $key => $month)
                                        <option value="{{ $key }}">{{ $month }}</option>
                                        @endforeach
                                    </select>
                                    <x-jet-input-error for="month" class="text-xs" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @foreach($contact_list as $i => $contact)
                <x-item-list :index="$i" active="true" editMethodName="editContact" deleteMethodName="deleteContact" :objectId="null">
                    <x-product-quick-view name="{{$i+1}}:">
                        <i class="fas fa-user-circle mr-1"></i><span class="mr-2">{{ $contact['name'] }}</span> &nbsp;
                        <i class="fas fa-envelope mr-1"></i><span class="mr-2">{{ $contact['email'] }}</span> &nbsp;
                        <i class="fas fa-phone-alt mr-1"></i><span class="mr-2">{{ $contact['phone'] }}</span> &nbsp;
                    </x-product-quick-view>
                </x-item-list>
                @endforeach
                <x-jet-input-error for="contact_list" class="text-xs" />
            </div>
            @endif
            @foreach($branch_list as $i => $branch)
            <x-item-list :index="$i" active="true" editMethodName="editBranch" deleteMethodName="deleteBranch" :objectId="null">
                <x-product-quick-view name="{{$i+1}}:">
                    <i class="fas fa-building mr-1"></i></i><span class="mr-2">{{ $branch['branch']['name'] }}</span>
                    <i class="fas fa-map-marker-alt mr-1"></i><span class="mr-2">{{ $branch['branch']['address'] }}</span>
                </x-product-quick-view>
            </x-item-list>
            @endforeach
            <x-jet-input-error for="branch_list" class="mt-3" />
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="store" wire:loading.attr="disabled" wire:target="store" class="disabled:opacity-25">
                Crear
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>