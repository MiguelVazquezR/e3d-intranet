<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    @can('ver_nóminas')
        <x-jet-button wire:click="openModal">
            Nómina en curso
        </x-jet-button>
    @endcan

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Nómina en curso
        </x-slot>

        <x-slot name="content">
            <div class="lg:grid lg:grid-cols-2 lg:gap-2">
                @can('ver_todas_las_nóminas')
                    <div>
                        <x-jet-label value="Usuario" class="mt-3 dark:text-gray-400" />
                        <x-select class="mt-2 w-full input" wire:model="user" :options="$users" />
                        <x-jet-input-error for="user" class="text-xs" />
                    </div>
                @endcan
                @if ($user)
                    <div>
                        @if ($user->nextPayRollRegister() == 'Terminado')
                            <span
                                class="mt-3 lg:mt-11 relative inline-block px-3 py-1 font-semibold text-blue-900 leading-tight">
                                <span aria-hidden class="absolute inset-0 bg-blue-200 opacity-50 rounded-full"></span>
                                <span class="relative text-sm">Día terminado</span>
                            </span>
                        @else
                            <button id="geolocation" wire:loading.attr="disabled" wire:target="verifyLocation"
                                wire:click="verifyLocation"
                                class="mt-3 lg:mt-11 text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-3 py-1 text-center disabled:opacity-25 disabled:cursor-not-allowed">
                                Registrar {{ $user->nextPayRollRegister() }}
                            </button>
                        @endif
                    </div>
                    <div
                        class="mt-3 flex items-center text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                        <a class="hover:cursor-pointer" target="_blank" href="{{ $user->profile_photo_url }}">
                            <img class="h-10 w-10 rounded-full object-cover border-2"
                                src="{{ $user->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                        </a>
                        <p class="text-gray-900 dark:text-gray-400 whitespace-no-wrap ml-1">
                            {{ $user->name }}
                        </p>
                    </div>
                    <div class="flex flex-col justify-center h-full col-span-full">
                        <!-- banner -->
                        <div x-data="{ open: true }" x-show="open"
                            class="w-11/12 flex justify-between mx-auto dark:bg-pink-300 bg-pink-100 rounded-lg p-4 my-6 text-sm font-medium text-pink-700"
                            role="alert">
                            <div class="w-11/12 flex">
                                <i class="fas fa-exclamation-circle w-5 h-5 inline mr-3"></i>
                                <div>
                                    A partir del <u>viernes 5 de Agosto</u>, la acumulación de <b>15 minutos en delante
                                        de retardos por semana</b>, se penalizará con un día completo rebajado de
                                    la nómina. <br>
                                    Esto lo hará automáticamente el sistema y nadie lo puede revertir.
                                </div>
                            </div>

                            <i @click="open = false" class="fal fa-times text-right hover:cursor-pointer"></i>
                        </div>
                        {{-- @if ($user->hasRole('Auxiliar_producción')) --}}
                        @if ($user->additionalTimeRequest())
                            <div
                                class="flex items-center justify-center px-2 py-1 rounded text-center mt-2 {{ $user->additionalTimeRequest()->authorized_by ? 'bg-green-50 text-green-500' : 'bg-orange-50 text-orange-500' }}">
                                {{ $user->additionalTimeRequest()->authorized_by ? 'Horas autorizadas' : 'Esperando autorización' }}
                                <button wire:loading.attr="disabled" wire:target="editRequest"
                                    wire:click="editRequest({{ $user->additionalTimeRequest() }})"
                                    class="ml-3 flex items-center justify-center w-6 h-6 rounded-full {{ $user->additionalTimeRequest()->authorized_by ? 'bg-green-300 text-green-600 hover:bg-green-500 hover:text-green-300' : 'bg-orange-300 text-orange-600 hover:bg-orange-500 hover:text-orange-300' }}  disabled:bg-gray-400/80 disabled:shadow-none disabled:cursor-not-allowed transition-colors duration-200"
                                    title="Editar solicitud">
                                    <i class="fas fa-pen text-xs"></i>
                                </button>
                            </div>
                        @else
                            <button wire:loading.attr="disabled" wire:target="requestTime" wire:click="requestTime"
                                class="px-4 py-2 bg-blue-500 outline-none rounded text-white shadow-blue-200 shadow-lg font-medium active:shadow-none active:scale-95 hover:bg-blue-600 focus:bg-blue-600 disabled:bg-gray-400/80 disabled:shadow-none disabled:cursor-not-allowed transition-colors duration-200">
                                Solicitar horas adicionales
                            </button>
                        @endif
                        {{-- @endif --}}
                        <!-- Table -->
                        <div
                            class="w-full max-w-2xl mx-auto dark:text-gray-400 dark:bg-slate-800 dark:border-slate-500 bg-white shadow-lg rounded-sm border border-gray-200">
                            <header class="px-5 py-4 border-b border-gray-100 flex justify-between text-sm">
                                <h2 class="font-semibold text-gray-600 dark:text-gray-400">Semana</h2>
                                <h2 class="font-semibold text-gray-600 dark:text-gray-400">Tiempo hasta ahora: <span
                                        class="{{ $user->employee->exceedWeeklyHours() ? 'text-red-500' : '' }}">{{ $user->totalTime() }}</span>
                                    / {{ $user->weeklyLimitTime() }}
                                </h2>
                            </header>
                            <div class="p-3">
                                <div class="overflow-x-auto">
                                    <table class="table-auto w-full">
                                        <thead
                                            class="text-xs font-semibold uppercase dark:bg-slate-700 text-gray-400 bg-gray-50">
                                            <tr>
                                                <th class="p-2 whitespace-nowrap">
                                                    <div class="font-semibold text-left">dia</div>
                                                </th>
                                                <th class="p-2 whitespace-nowrap">
                                                    <div class="font-semibold text-left">entrada</div>
                                                </th>
                                                <th class="p-2 whitespace-nowrap">
                                                    <div class="font-semibold text-left">inicio break</div>
                                                </th>
                                                <th class="p-2 whitespace-nowrap">
                                                    <div class="font-semibold text-left">fin break</div>
                                                </th>
                                                <th class="p-2 whitespace-nowrap">
                                                    <div class="font-semibold text-left">salida</div>
                                                </th>
                                                <th class="p-2 whitespace-nowrap">
                                                    <div class="font-semibold text-left">hrs break</div>
                                                </th>
                                                <th class="p-2 whitespace-nowrap">
                                                    <div class="font-semibold text-left">hrs totales</div>
                                                </th>
                                                <th class="p-2 whitespace-nowrap">
                                                    <div class="font-semibold text-left">&nbsp;</div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-sm divide-y dark:divide-gray-500 divide-gray-100">
                                            @foreach ($current_week_registers as $i => $register)
                                                <tr>
                                                    <td class="p-2 whitespace-nowrap text-left">
                                                        {{ App\Models\Employee::WEEK[$i] }}
                                                    </td>
                                                    @if (!is_string($register))
                                                        <td class="p-2 whitespace-nowrap text-left">
                                                            @if ($register)
                                                                <x-jet-input
                                                                    wire:model.defer="current_week_registers.{{ $i }}.check_in"
                                                                    type="time"
                                                                    class="input w-full text-xs mt-2 {{ $register->late ? 'text-yellow-500 dark:text-yellow-600 font-bold' : '' }}" />
                                                            @else
                                                                <x-jet-input
                                                                    wire:model.defer="current_week_registers.{{ $i }}.check_in"
                                                                    type="time" class="input w-full text-xs mt-2" />
                                                            @endif
                                                        </td>
                                                        <td class="p-2 whitespace-nowrap text-left">
                                                            <x-jet-input
                                                                wire:model.defer="current_week_registers.{{ $i }}.start_break"
                                                                type="time" class="input w-full text-xs mt-2" />
                                                        </td>
                                                        <td class="p-2 whitespace-nowrap text-left">
                                                            <x-jet-input
                                                                wire:model.defer="current_week_registers.{{ $i }}.end_break"
                                                                type="time" class="input w-full text-xs mt-2" />
                                                        </td>
                                                        <td class="p-2 whitespace-nowrap text-left">
                                                            <x-jet-input
                                                                wire:model.defer="current_week_registers.{{ $i }}.check_out"
                                                                type="time" class="input w-full text-xs mt-2" />
                                                        </td>
                                                        <td class="p-2 whitespace-nowrap text-left">
                                                            {{ $user->breakTime($register) }}
                                                        </td>
                                                        <td class="p-2 whitespace-nowrap text-left">
                                                            {{ $user->timeForRegister($register, true, false) }}
                                                            @if (!is_string($register) && !is_null($register))
                                                                @if ($register->extras_enabled)
                                                                    <span class="text-green-500"
                                                                        style="font-size: 10px;"> +
                                                                        {{ $user->extraTime($register) }}
                                                                        (dobles)
                                                                    </span>
                                                                @endif
                                                            @endif
                                                        </td>
                                                    @elseif($register == 'Falta')
                                                        <td colspan="4"
                                                            class="p-2 whitespace-nowrap text-left text-lg font-bold">
                                                            <div class="bg-red-100 rounded-lg text-red-600 p-2">
                                                                {{ $register }}</div>
                                                        </td>
                                                    @else
                                                        <td colspan="4"
                                                            class="p-2 whitespace-nowrap text-left text-lg font-bold">
                                                            <div class="bg-green-100 rounded-lg text-green-600 p-2">
                                                                {{ $register }}</div>
                                                        </td>
                                                    @endif
                                                    @can('editar_nóminas')
                                                        <td class="p-2 whitespace-nowrap text-left">
                                                            <x-jet-dropdown align="right" width="20">
                                                                <x-slot name="trigger">
                                                                    @if ($register != 'Descanso')
                                                                        <button
                                                                            class="flex text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-3 py-1 text-center"">
                                                                            Acciones
                                                                            <svg class="   ml-2 -mr-0.5 h-4 w-4"
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                viewBox="0 0 20 20" fill="currentColor">
                                                                                <path fill-rule="evenodd"
                                                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                                    clip-rule="evenodd" />
                                                                            </svg>
                                                                        </button>
                                                                    @endif
                                                                </x-slot>

                                                                <x-slot name="content">
                                                                    @foreach ($justification_events as $j_e)
                                                                        @if (is_string($register) || is_null($register))
                                                                            <x-jet-dropdown-link
                                                                                wire:click="$emit('confirm', { 0:'pay-roll-register.create-pay-roll-register', 1:'justification' ,2:[{{ $i }}, {{ $j_e->id }}], 3:'{{ $j_e->description }}' })"
                                                                                :link="false" class="block">
                                                                                {{ $j_e->name }}
                                                                            </x-jet-dropdown-link>
                                                                        @endif
                                                                    @endforeach
                                                                    @if (!is_string($register) && !is_null($register))
                                                                        @if ($register->late)
                                                                            <x-jet-dropdown-link
                                                                                wire:click="unmarkLate({{ $i }})"
                                                                                :link="false">
                                                                                Quitar retardo
                                                                            </x-jet-dropdown-link>
                                                                        @else
                                                                            <x-jet-dropdown-link
                                                                                wire:click="markLate({{ $i }})"
                                                                                :link="false">
                                                                                Poner retardo
                                                                            </x-jet-dropdown-link>
                                                                        @endif
                                                                        <x-jet-dropdown-link
                                                                            wire:click="extrasToDouble({{ $i }})"
                                                                            :link="false">
                                                                            @if ($register->extras_enabled)
                                                                                <span class="text-red-500">Desactivar
                                                                                    extras dobles</span>
                                                                            @else
                                                                                <span class="text-green-500">Activar extras
                                                                                    dobles</span>
                                                                            @endif
                                                                        </x-jet-dropdown-link>
                                                                    @elseif($register == 'Falta')
                                                                        <x-jet-dropdown-link
                                                                            wire:click="removeAbsence({{ $i }})"
                                                                            :link="false">
                                                                            Quitar falta
                                                                        </x-jet-dropdown-link>
                                                                    @elseif (!is_null($register))
                                                                        <x-jet-dropdown-link
                                                                            wire:click="removeJustification({{ $i }})"
                                                                            :link="false">
                                                                            Quitar Justificación
                                                                        </x-jet-dropdown-link>
                                                                    @endif

                                                                </x-slot>
                                                            </x-jet-dropdown>
                                                        </td>
                                                    @endcan
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>
            @if ($user)
                @can('editar_nóminas')
                    <x-jet-button class="mr-2 disabled:opacity-25 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled" wire:target="update" wire:click="update">
                        Actualizar
                    </x-jet-button>
                @endcan
            @endif
            @can('cerrar_nóminas')
                @if (App\Models\PayRoll::canClose())
                    <x-jet-button
                        wire:click="$emit('confirm', {0:'pay-roll-register.create-pay-roll-register', 1:'close' ,2:null, 3:'Se generará la nómina de la nueva semana y se cerrará la actual.'})">
                        Cerrar nómina
                    </x-jet-button>
                @endif
            @endcan
        </x-slot>

    </x-jet-dialog-modal>

</div>
