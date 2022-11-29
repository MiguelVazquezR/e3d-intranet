<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            @if ($pay_roll)
                Nómina semana {{ $pay_roll->week }}
            @endif
        </x-slot>

        <x-slot name="content">
            <div class="lg:grid lg:grid-cols-2 lg:gap-2">
                @can('editar_nóminas')
                    <div>
                        <x-jet-label value="Usuario" class="mt-3 dark:text-gray-400" />
                        <x-select class="mt-2 w-full input" wire:model="user" :options="$users" />
                        <x-jet-input-error for="user" class="text-xs" />
                    </div>
                @endcan
                @if ($user)

                    <div
                        class="mt-3 flex items-center text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                        <a class="hover:cursor-pointer" target="_blank" href="{{ $user->profile_photo_url }}">
                            <img class="h-10 w-10 rounded-full object-cover border-2"
                                src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
                        </a>
                        <p class="text-gray-900 dark:text-gray-400 whitespace-no-wrap ml-1">
                            {{ $user->name }}
                        </p>
                    </div>
                    <div class="flex flex-col justify-center h-full col-span-full mt-2">
                        <!-- Table -->
                        <div class="w-full max-w-2xl mx-auto dark:text-gray-400 dark:bg-slate-800 dark:border-slate-600 bg-white shadow-lg rounded-sm border border-gray-200">
                            <header class="px-5 py-4 border-b dark:border-slate-600 border-gray-100 flex justify-between text-sm">
                                <h2 class="font-semibold text-gray-800">Semana</h2>
                                @if ($pay_roll)
                                    <h2 class="font-semibold text-gray-600">Tiempo hecho:
                                        {{ $user->totalTime($pay_roll->id) }}</h2>
                                @endif
                            </header>
                            <div class="p-3">
                                <div class="overflow-x-auto">
                                    <table class="table-auto w-full">
                                        <thead class="text-xs font-semibold uppercase dark:bg-slate-700 text-gray-400 bg-gray-50">
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
                                                                    class="input w-full text-xs mt-2 {{ $register->late ? 'text-yellow-500 font-bold' : '' }}" />
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
                                                            <div class="bg-red-100 dark:bg-red-300 dark:text-red-700 rounded-lg text-red-600 p-2">
                                                                {{ $register }}</div>
                                                        </td>
                                                        <td class="p-2 whitespace-nowrap text-left">
                                                            {{ $user->timeForRegister($register, true, false) }}
                                                        </td>
                                                    @else
                                                        <td colspan="4"
                                                            class="p-2 whitespace-nowrap text-left text-lg font-bold">
                                                            <div class="bg-green-100 dark:bg-green-300 dark:text-green-700 rounded-lg text-green-600 p-2">
                                                                {{ $register }}</div>
                                                        </td>
                                                        <td class="p-2 whitespace-nowrap text-left">
                                                            {{ $user->timeForRegister($register, true, false) }}
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
                                                                    <svg class="    ml-2 -mr-0.5 h-4 w-4"
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
                                                                                wire:click="$emit('confirm', { 0:'pay-roll-register.edit-pay-roll-register', 1:'justification' ,2:[{{ $i }}, {{ $j_e->id }}], 3:'{{ $j_e->description }}' })"
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
                    <x-jet-button class="mr-2" wire:loading.attr="disabled" wire:target="update"
                        wire:click="update">
                        Actualizar
                    </x-jet-button>
                @endcan
            @endif
        </x-slot>

    </x-jet-dialog-modal>

</div>
