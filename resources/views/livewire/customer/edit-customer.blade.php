<div>
    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            <!--tab component start-->
            <ul class="flex justify-center items-center pb-4" x-data="setup()">
                <template x-for="(tab, index) in tabs" :key="index">
                    <li class="cursor-pointer text-sm py-2 px-6 text-gray-500 border-b-2"
                        :class="activeTab === index ? 'text-black border-black' : ''"
                        @click="activeTab = index; $dispatch('change-tab', index);" x-text="tab"></li>
                </template>
            </ul>
            <!--tab component end-->

            <script>
                function setup() {
                    return {
                        activeTab: 0,
                        tabs: [
                            "Detalles",
                            "Productos",
                        ],
                        activeTab: @entangle('active_tab'), //current tab
                    };
                };
            </script>
        </x-slot>

        <x-slot name="content">
            <div x-data="{ activeTab: @entangle('active_tab') }" @change-tab.window="activeTab = $event.detail">
                <!-- Details -->
                <div x-show="activeTab == 0">
                    <h2 class="mb-4 text-gray-500">
                        Actualizar cliente <span class="text-black">{{ $company->id }}</span>
                    </h2>

                    <h2 class="text-center font-bold text-lg text-sky-600">Datos fiscales de empresa</h2>
                    <div>
                        <x-jet-label value="Razón social" class="mt-3" />
                        <x-jet-input wire:model.defer="company.bussiness_name" type="text" class="w-full mt-2" />
                        <x-jet-input-error for="company.bussiness_name" class="mt-3" />
                    </div>
                    <div class="lg:grid lg:grid-cols-2 lg:gap-x-2">
                        <div>
                            <x-jet-label value="Teléfono" class="mt-3" />
                            <x-jet-input wire:model.defer="company.phone" type="text" class="w-full mt-2" />
                            <x-jet-input-error for="company.phone" class="mt-3" />
                        </div>

                        <div>
                            <x-jet-label value="RFC" class="mt-3" />
                            <x-jet-input wire:model.defer="company.rfc" type="text" class="w-full mt-2" />
                            <x-jet-input-error for="company.rfc" class="mt-3" />
                        </div>
                    </div>

                    <div class="lg:grid lg:grid-cols-4 lg:gap-x-2">
                        <div class="lg:col-span-3">
                            <x-jet-label value="Dirección" class="mt-3" />
                            <x-jet-input wire:model.defer="company.fiscal_address" type="text"
                                class="w-full mt-2 placeholder:text-xs"
                                placeholder="calle, colonia, # interior y/o exterior, ciudad, estado, país" />
                            <x-jet-input-error for="company.fiscal_address" class="mt-3" />
                        </div>

                        <div>
                            <x-jet-label value="C.P." class="mt-3" />
                            <x-jet-input wire:model.defer="company.post_code" type="text" class="w-full mt-2" />
                            <x-jet-input-error for="company.post_code" class="mt-3" />
                        </div>
                    </div>

                    <h2 wire:click="$set('add_branch', {{ !$add_branch }})"
                        class="hover:cursor-pointer text-center font-bold text-lg text-sky-600 mt-2">
                        Sucursales
                        @if ($add_branch)
                            <i class="fas fa-angle-up ml-1 text-gray-800"></i>
                        @else
                            <i class="fas fa-angle-down ml-1 text-gray-800"></i>
                        @endif
                    </h2>

                    <!-- add branch -->
                    @if ($add_branch || !is_null($edit_branch_index))
                        <div class="p-3 bg-sky-100 my-2 rounded-2xl">
                            <div>
                                <x-jet-label value="Nombre" class="mt-1" />
                                <x-jet-input wire:model.defer="name" type="text" class="w-full mt-2" />
                                <x-jet-input-error for="name" class="mt-3" />
                            </div>

                            <div class="lg:grid lg:grid-cols-4 lg:gap-x-2">
                                <div class="lg:col-span-3">
                                    <x-jet-label value="Dirección" class="mt-3" />
                                    <x-jet-input wire:model.defer="address" type="text"
                                        class="w-full mt-2 placeholder:text-xs"
                                        placeholder="calle, colonia, # interior y/o exterior, ciudad, estado, país" />
                                    <x-jet-input-error for="address" class="mt-3" />
                                </div>
                                <div>
                                    <x-jet-label value="C.P." class="mt-3" />
                                    <x-jet-input wire:model.defer="branch_post_code" type="text"
                                        class="w-full mt-2" />
                                    <x-jet-input-error for="branch_post_code" class="mt-3" />
                                </div>
                            </div>

                            <x-jet-label value="Método de pago" class="mt-3" />
                            <select class="input mt-2 w-3/4" wire:model.defer="sat_method_id">
                                <option value="" selected>--- Seleccione ---</option>
                                @forelse($sat_methods as $method)
                                    <option value="{{ $method->id }}">
                                        {{ $method->key . ' - ' . $method->description }}</option>
                                @empty
                                    <option value="">No hay ningun método registrado</option>
                                @endforelse
                            </select>
                            <x-jet-secondary-button class="ml-2 rounded-full" wire:click="openCreateSatMethod">
                                <i class="fas fa-plus"></i>
                            </x-jet-secondary-button>
                            <x-jet-input-error for="sat_method_id" class="mt-3" />

                            <x-jet-label value="Medio de pago" class="mt-3" />
                            <select class="input mt-2 w-3/4" wire:model.defer="sat_way_id">
                                <option value="" selected>--- Seleccione ---</option>
                                @forelse($sat_ways as $way)
                                    <option value="{{ $way->id }}">{{ $way->key . ' - ' . $way->description }}
                                    </option>
                                @empty
                                    <option value="">No hay ningun medio registrado</option>
                                @endforelse
                            </select>
                            <x-jet-secondary-button class="ml-2 rounded-full"
                                wire:click="$emitTo('currency.create-currency', 'openModal')">
                                <i class="fas fa-plus"></i>
                            </x-jet-secondary-button>
                            <x-jet-input-error for="sat_way_id" class="mt-3" />

                            <x-jet-label value="Uso de factura" class="mt-3" />
                            <x-select class="mt-2 w-3/4" wire:model.defer="sat_type_id" :options="$sat_types" name="description" />
                            <x-jet-secondary-button class="ml-2 rounded-full"
                                wire:click="$emitTo('currency.create-currency', 'openModal')">
                                <i class="fas fa-plus"></i>
                            </x-jet-secondary-button>
                            <x-jet-input-error for="sat_type_id" class="mt-3" />

                            <!-- Contacts -->
                            <h2 wire:click="$set('add_contact', {{ !$add_contact }})"
                                class="text-center font-bold text-lg text-sky-600 my-2 hover:cursor-pointer">
                                Contactos
                                @if ($add_contact)
                                    <i class="fas fa-angle-up ml-1 text-gray-800"></i>
                                @else
                                    <i class="fas fa-angle-down ml-1 text-gray-800"></i>
                                @endif
                            </h2>
                            @if ($add_contact || !is_null($edit_contact_index))
                                <div class="p-3 bg-sky-200 my-1 rounded-2xl">
                                    <div>
                                        <x-jet-label value="Nombre" class="mt-1" />
                                        <x-jet-input wire:model.defer="contact_name" type="text"
                                            class="w-full mt-2" />
                                        <x-jet-input-error for="contact_name" class="mt-3" />
                                    </div>

                                    <div class="lg:grid lg:grid-cols-2 lg:gap-x-2">
                                        <div>
                                            <x-jet-label value="Correo" class="mt-3" />
                                            <x-jet-input wire:model.defer="email" type="text"
                                                class="w-full mt-2 placeholder:text-xs" />
                                            <x-jet-input-error for="email" class="mt-3" />
                                        </div>
                                        <div>
                                            <x-jet-label value="Teléfono" class="mt-3" />
                                            <x-jet-input wire:model.defer="contact_phone" type="text"
                                                class="w-full mt-2" />
                                            <x-jet-input-error for="contact_phone" class="mt-3" />
                                        </div>
                                        <div class="col-span-full">
                                            <x-jet-label value="Cumpleaños" class="mt-3" />
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <x-jet-label value="Día" class="mt-3" />
                                                    <select class="input mt-2 w-full" wire:model.defer="day">
                                                        <option value="">-- Seleccione --</option>
                                                        @for ($day = 1; $day <= 31; $day++)
                                                            <option value="{{ $day }}">{{ $day }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    <x-jet-input-error for="day" class="mt-1" />
                                                </div>
                                                <div>
                                                    <x-jet-label value="Mes" class="mt-3" />
                                                    <select class="input mt-2 w-full" wire:model.defer="month">
                                                        <option value="">-- Seleccione --</option>
                                                        @foreach ($months as $key => $month)
                                                            <option value="{{ $key }}">{{ $month }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <x-jet-input-error for="month" class="mt-3" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-end mt-4">
                                        @if (is_null($edit_contact_index))
                                            <div wire:click="addContactToList"
                                                class="hover:cursor-pointer flex items-center">
                                                <i class="fas fa-plus-circle text-green-600"></i>
                                                <span class="text-xs text-green-500 ml-1">Agregar contacto</span>
                                            </div>
                                        @else
                                            <div class="flex">
                                                <div wire:click="updateContactFromList"
                                                    class="hover:cursor-pointer flex items-center">
                                                    <i class="fas fa-check-circle text-green-600 mr-1"></i>
                                                    <span class="text-xs text-green-500 mr-3">Actualizar
                                                        contacto</span>
                                                </div>
                                                <div wire:click="closeEditContact"
                                                    class="hover:cursor-pointer flex items-center">
                                                    <i class="fas fa-times"></i>
                                                    <span class="text-xs text-gray-600 ml-1">Cancelar</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @foreach ($contact_list as $i => $contact)
                                <x-item-list :index="$i" :active="array_key_exists('id', $contact)
                                    ? !in_array($contact['id'], $temporary_contacts_deleted_list)
                                    : true" :objectId="array_key_exists('id', $contact) ? $contact['id'] : null"
                                    inactiveMessage="Contacto eliminado de la sucursal" editMethodName="editContact"
                                    deleteMethodName="deleteContact">
                                    <x-product-quick-view name="{{ $i + 1 }}:">
                                        <i class="fas fa-user-circle mr-1"></i><span
                                            class="mr-2">{{ $contact['name'] }}</span> &nbsp;
                                        <i class="fas fa-envelope mr-1"></i><span
                                            class="mr-2">{{ $contact['email'] }}</span> &nbsp;
                                        <i class="fas fa-phone-alt mr-1"></i><span
                                            class="mr-2">{{ $contact['phone'] }}</span> &nbsp;
                                    </x-product-quick-view>
                                </x-item-list>
                            @endforeach

                            <div class="flex justify-end mt-3 p-1">
                                @if (is_null($edit_branch_index))
                                    <div wire:click="addBranchToList" class="hover:cursor-pointer flex items-center">
                                        <i class="fas fa-plus-circle text-green-600"></i>
                                        <span class="text-xs text-green-500 ml-1">Agregar Sucursal</span>
                                    </div>
                                @else
                                    <div class="flex">
                                        <div wire:click="updateBranchFromList"
                                            class="hover:cursor-pointer flex items-center">
                                            <i class="fas fa-check-circle text-green-600 mr-1"></i>
                                            <span class="text-xs text-green-500 mr-3">Actualizar Sucursal</span>
                                        </div>
                                        <div wire:click="closeEditBranch"
                                            class="hover:cursor-pointer flex items-center">
                                            <i class="fas fa-times"></i>
                                            <span class="text-xs text-gray-600 ml-1">Cancelar</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <x-jet-input-error for="contact_list" class="mt-3" />
                        </div>
                    @endif
                    @foreach ($branch_list as $i => $branch)
                        <x-item-list :index="$i" :active="array_key_exists('id', $branch['branch'])
                            ? !in_array($branch['branch']['id'], $temporary_branches_deleted_list)
                            : true" :objectId="array_key_exists('id', $branch['branch']) ? $branch['branch']['id'] : null"
                            inactiveMessage="Sucursal eliminada del cliente" editMethodName="editBranch"
                            deleteMethodName="deleteBranch">
                            <x-product-quick-view name="{{ $i + 1 }}:">
                                <i class="fas fa-building mr-1"></i></i><span
                                    class="mr-2">{{ $branch['branch']['name'] }}</span>
                                <i class="fas fa-map-marker-alt mr-1"></i><span
                                    class="mr-2">{{ $branch['branch']['address'] }}</span>
                            </x-product-quick-view>
                        </x-item-list>
                    @endforeach
                    <x-jet-input-error for="branch_list" class="mt-3" />
                </div>

                <!-- products -->
                <div x-show="activeTab == 1" class="text-gray-500">
                    <div class="flex justify-between pb-4 border-b-2">
                        <h2>
                            Editar Productos registrados
                        </h2>
                        <x-jet-secondary-button
                            wire:click="$emitTo('company-has-product-for-sell.create-company-has-product-for-sell', 'openModal', {{ $company }} )"
                            class="ml-2">
                            Registrar producto
                        </x-jet-secondary-button>
                    </div>

                    <!-- composit products list -->
                    <div class="grid-cols-1 md:grid md:grid-cols-2 md:gap-3 mt-2 text-sm">
                        @forelse($company->productsForSell as $product_for_sell)
                            @if ($product_for_sell->model_name == 'App\\Models\\' . Product::class)
                                @php
                                    $product = App\Models\Product::find($product_for_sell->model_id);
                                @endphp
                                <x-simple-product-card :simpleProduct="$product">
                                    <!-- prices and dates -->
                                    <div class="grid grid-cols-2 gap-2 border-t-2 p-2">
                                        @if ($product_for_sell->old_price)
                                            <p class="mt-1 text-gray-500">Precio anterior: <span
                                                    class="text-green-600">{{ $product_for_sell->old_price . ' ' . $product_for_sell->old_price_currency }}</span>
                                            </p>
                                            <p class="mt-1 text-gray-500">Establecido:
                                                <span class="text-sky-600">
                                                    @if ($product_for_sell->old_date->diffForHumans() == 'hace 6 horas')
                                                        {{ 'hoy a las ' . $product_for_sell->old_date->isoFormat('h:mm a') }}
                                                    @else
                                                        {{ $product_for_sell->old_date->diffForHumans() }}
                                                    @endif
                                                </span>
                                            </p>
                                        @endif
                                        <p class="mt-1 text-gray-500">Precio actual: <span
                                                class="text-green-600">{{ $product_for_sell->new_price . ' ' . $product_for_sell->new_price_currency }}</span>
                                        </p>
                                        <p class="mt-1 text-gray-500">Establecido:
                                            <span class="text-sky-600">
                                                @if ($product_for_sell->new_date->diffForHumans() == 'hace 6 horas')
                                                    {{ 'hoy a las ' . $product_for_sell->new_date->isoFormat('h:mm a') }}
                                                @else
                                                    {{ $product_for_sell->new_date->diffForHumans() }}
                                                @endif
                                            </span>
                                        </p>
                                    </div>
                                    <div class="flex justify-end text-xs p-2">
                                        <div wire:click="updatePriceProductForSell({{ $product_for_sell }})"
                                            class="mr-3 text-green-600 hover:cursor-pointer">
                                            <i class="fas fa-money-bill-wave"></i>
                                            <i class="fas fa-angle-double-up"></i>
                                        </div>
                                        <i wire:click="editProductForSell( {{ $product_for_sell }} )"
                                            class="fas fa-edit mr-3 text-blue-500 hover:cursor-pointer"></i>
                                        <i wire:click="deleteProductForSell({{ $product_for_sell }})"
                                            class="fas fa-trash-alt text-red-500 hover:cursor-pointer"></i>
                                    </div>
                                </x-simple-product-card>
                            @else
                                @php
                                    $product = App\Models\CompositProduct::find($product_for_sell->model_id);
                                @endphp
                                <x-composit-product-card :compositProduct="$product">
                                    <!-- prices and dates -->
                                    <div class="grid grid-cols-2 gap-2 border-t-2 p-2">
                                        @if ($product_for_sell->old_price)
                                            <p class="mt-1 text-gray-500">Precio anterior: <span
                                                    class="text-green-600">{{ $product_for_sell->old_price . ' ' . $product_for_sell->old_price_currency }}</span>
                                            </p>
                                            <p class="mt-1 text-gray-500">Establecido:
                                                <span class="text-sky-600">
                                                    @if ($product_for_sell->old_date->diffForHumans() == 'hace 6 horas')
                                                        {{ 'hoy a las ' . $product_for_sell->old_date->isoFormat('h:mm a') }}
                                                    @else
                                                        {{ $product_for_sell->old_date->diffForHumans() }}
                                                    @endif
                                                </span>
                                            </p>
                                        @endif
                                        <p class="mt-1 text-gray-500">Precio actual: <span
                                                class="text-green-600">{{ $product_for_sell->new_price . ' ' . $product_for_sell->new_price_currency }}</span>
                                        </p>
                                        <p class="mt-1 text-gray-500">Establecido:
                                            <span class="text-sky-600">
                                                @if ($product_for_sell->new_date->diffForHumans() == 'hace 6 horas')
                                                    {{ 'hoy a las ' . $product_for_sell->new_date->isoFormat('h:mm a') }}
                                                @else
                                                    {{ $product_for_sell->new_date->diffForHumans() }}
                                                @endif
                                            </span>
                                        </p>
                                    </div>
                                    <div class="flex justify-end text-xs p-2">
                                        <div wire:click="updatePriceProductForSell({{ $product_for_sell }})"
                                            class="mr-3 text-green-600 hover:cursor-pointer">
                                            <i class="fas fa-money-bill-wave"></i>
                                            <i class="fas fa-angle-double-up"></i>
                                        </div>
                                        <i wire:click="editProductForSell( {{ $product_for_sell }} )"
                                            class="fas fa-edit mr-3 text-blue-500 hover:cursor-pointer"></i>
                                        <i wire:click="deleteProductForSell({{ $product_for_sell }})"
                                            class="fas fa-trash-alt text-red-500 hover:cursor-pointer"></i>
                                    </div>
                                </x-composit-product-card>
                            @endif
                        @empty
                            <h2 class="text-center py-10">No hay productos registrados</h2>
                        @endforelse
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cerrar
            </x-jet-secondary-button>
            <x-jet-button wire:click="update" wire:loading.attr="disabled" wire:target="update"
                class="disabled:opacity-25">
                Actualizar
            </x-jet-button>
        </x-slot>


    </x-jet-dialog-modal>

</div>
