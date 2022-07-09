<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Editar usuario
        </x-slot>

        <x-slot name="content">
            <div class="lg:grid lg:grid-cols-2 lg:gap-3">
                <div>
                    <x-jet-label value="Nombre completo" class="mt-3" />
                    <x-jet-input wire:model="user.name" type="text" class="w-full mt-2" />
                    <x-jet-input-error for="user.name" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Correo" class="mt-3" />
                    <x-jet-input wire:model.defer="user.email" type="text" class="w-full mt-2" />
                    <x-jet-input-error for="user.email" class="text-xs" />
                </div>
                <h2 class="text-center font-bold text-lg text-sky-600 col-span-2">Datos de empleado</h2>
                <div>
                    <x-jet-label value="Departamento" class="mt-3" />
                    <x-select class="mt-2 w-full" wire:model.defer="employee.department_id" :options="$departments" />
                    <x-jet-input-error for="employee.department_id" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Puesto" class="mt-3" />
                    <x-jet-input wire:model.defer="employee.job_position" type="text" class="w-full mt-2" />
                    <x-jet-input-error for="employee.job_position" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Salario por hora" class="mt-3" />
                    <x-jet-input wire:model.defer="employee.salary" type="number" class="w-full mt-2" />
                    <x-jet-input-error for="employee.salary" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Retenciones" class="mt-3" />
                    <x-jet-input wire:model.defer="employee.discounts" type="number" class="w-full mt-2" />
                    <x-jet-input-error for="employee.discounts" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Vacaciones" class="mt-3" />
                    <x-jet-input wire:model.defer="employee.vacations" type="number" class="w-full mt-2" />
                    <x-jet-input-error for="employee.vacations" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Horas semanales" class="mt-3" />
                    <x-jet-input wire:model.defer="employee.hours_per_week" type="number" class="w-full mt-2" />
                    <x-jet-input-error for="employee.hours_per_week" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Fecha de nacimiento" class="mt-3" />
                    <x-jet-input wire:model.defer="birth_date" type="date" class="w-full mt-2" />
                    <x-jet-input-error for="birth_date" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Fecha de ingreso" class="mt-3" />
                    <x-jet-input wire:model.defer="join_date" type="date" class="w-full mt-2" />
                    <x-jet-input-error for="join_date" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Bonos" class="mt-3" />
                    <x-select class="mt-2 w-full" wire:model="bonus_selected" :options="$all_bonuses" />
                </div>
                <div class="flex text-xs items-center flex-wrap my-2">
                    @foreach($bonuses as $i => $bonus)
                    <span class="rounded-full py-1 px-2 bg-blue-500 text-white mr-2">
                        {{ App\Models\Bonus::find($bonus)->name }}
                        <i wire:click="removeBonus({{ $i }})" class="fal fa-times ml-1 hover:cursor-pointer hover:font-semibold"></i>
                    </span>
                    @endforeach
                </div>
                <div>
                    <x-jet-label value="Días de descanso" class="mt-3" />
                    <select class="input mt-2 w-full" wire:model="day_off_selected">
                        <option value="" selected>-- Seleccione --</option>
                        @foreach(App\Models\Employee::WEEK as $i => $day)
                        <option value="{{ $i }}">{{ $day }}</option>
                        @endforeach
                    </select>
                    <x-jet-input-error for="days_off" class="text-xs" />
                </div>
                <div class="flex text-xs items-center flex-wrap my-2">
                    @foreach($days_off as $i => $day_off)
                    <span class="rounded-full py-1 px-2 bg-blue-500 text-white mr-2">
                        {{ App\Models\Employee::WEEK[$day_off] }}
                        <i wire:click="removeDayOff({{ $i }})" class="fal fa-times ml-1 hover:cursor-pointer hover:font-semibold"></i>
                    </span>
                    @endforeach
                </div>
                <div class="flex border rounded-full overflow-hidden text-xs col-span-2 mt-1">
                    <div class="py-2 my-auto px-5 bg-blue-500 text-white font-semibold mr-3">
                        Horas de entrada
                    </div>
                    <label class="flex items-center radio p-2 cursor-pointer">
                        <input wire:model="same_check_in" value="1" class="my-auto" type="radio" name="s-check-it" />
                        <div class="px-2">Todos los días a la misma hora</div>
                    </label>

                    <label class="flex items-center radio p-2 cursor-pointer">
                        <input wire:model="same_check_in" value="0" class="my-auto" type="radio" name="s-check-it" />
                        <div class="px-2">Variado</div>
                    </label>
                </div>
                @if($same_check_in)
                <div>
                    <x-jet-label value="Hora de entrada" class="mt-2" />
                    <x-jet-input wire:model="check_in_time_selected" type="time" class="w-full mt-1" />
                    <x-jet-input-error for="check_in_time_selected" class="text-xs" />
                </div>
                @else
                <div>
                    <x-jet-label value="Hora de entrada - {{ App\Models\Employee::WEEK[$check_in_time_index] }}" class="mt-1" />
                    <x-jet-input wire:model="check_in_time_selected" type="time" class="w-full mt-2" />
                    <x-jet-input-error for="check_in_time_selected" class="text-xs" />
                </div>
                <div class="flex items-end text-green-600 text-sm pb-3">
                    <button wire:click="addCheckInTime" class="flex items-center hover:cursor-pointer border-2 border-green-600 rounded-lg p-1">
                        <i class="fas fa-plus-circle"></i>
                        <span class="ml-1">Agregar hora</span>
                    </button>
                </div>
                @endif
                <div class="flex text-xs items-center flex-wrap my-2 col-span-2">
                    @foreach($check_in_times as $i => $check_in)
                    <span class="rounded-full py-1 px-2 bg-blue-500 text-white mr-2">
                        {{ mb_substr($i, 0, 2) . '-' . $check_in }}
                        <i wire:click="removeCheckInTime('{{ $i }}')" class="fal fa-times ml-1 hover:cursor-pointer hover:font-semibold"></i>
                    </span>
                    @endforeach
                </div>
                <x-jet-input-error for="check_in_times" class="col-span-2" />
            </div>
            <x-jet-label value="Roles" class="mt-3" />
            <div class="grid grid-cols-3 lg:grid-cols-5 gap-3 mt-1">
                @forelse($roles as $name => $id)
                <label class="inline-flex items-center mt-3 text-xs">
                    <input wire:model="roles_selected.{{$name}}" type="checkbox" value="{{$id}}" class="rounded">
                    <span class="ml-1 text-gray-700">{{$name}}</span>
                </label>
                @empty
                <p class="col-span-full text-sm text-red-700 mt-1">No hay rols registrados</p>
                @endforelse
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            @if($user)
            @if($user->active)
            <x-jet-secondary-button class="mr-2 bg-red-600 text-white hover:text-white hover:opacity-75" wire:click="disable">
                Dar de baja
            </x-jet-secondary-button>
            @else
            <x-jet-secondary-button class="mr-2 bg-green-600 text-white hover:text-white hover:opacity-75" wire:click="enable">
                Dar de alta
            </x-jet-secondary-button>
            @endif
            @endif

            <x-jet-button wire:click="update" wire:loading.attr="disable" wire:target="update" class="disabled:opacity-25">
                Actualizar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>