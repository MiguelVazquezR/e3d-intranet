<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Usuario {{ $user->id }}
        </x-slot>

        <x-slot name="content">

            @if($user->id)
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-2">
                <div>
                    <x-jet-label value="Nombre" class="mt-3" />
                    <p>{{ $user->name }}</p>
                </div>
                <div class="col-span-2">
                    <x-jet-label value="Correo" class="mt-3" />
                    <p>
                        @if($user->email)
                        {{ $user->email }}
                        @else
                        --
                        @endif
                    </p>
                </div>
                @if($user->employee)
                <div>
                    <x-jet-label value="Fecha ingreso" class="mt-3" />
                    <p>{{ $user->employee->join_date->isoFormat('D MMMM YYYY') }}</p>
                </div>
                <div>
                    <x-jet-label value="Fecha de nacimiento" class="mt-3" />
                    <p>{{ $user->employee->birth_date->isoFormat('D MMMM YYYY') }}</p>
                </div>
                <div>
                    <x-jet-label value="Departamento" class="mt-3" />
                    <p>{{ $user->employee->department->name }}</p>
                </div>
                <div>
                    <x-jet-label value="Puesto" class="mt-3" />
                    <p>{{ $user->employee->job_position }}</p>
                </div>
                <div>
                    <x-jet-label value="Salario por hora" class="mt-3" />
                    <p>
                        @if(Auth::user()->can('crear_usuarios'))
                        ${{ $user->employee->salary }}
                        @else
                        *****
                        @endif
                    </p>
                </div>
                <div>
                    <x-jet-label value="Retenciones" class="mt-3" />
                    <p>${{ $user->employee->discounts }}</p>
                </div>
                <div>
                    <x-jet-label value="Horas semanales" class="mt-3" />
                    <p>{{ $user->employee->hours_per_week }}</p>
                </div>
                <div>
                    <x-jet-label value="Vacaciones" class="mt-3" />
                    <p>{{ $user->employee->vacations }} días</p>
                </div>
                <div class="col-span-2">
                    <x-jet-label value="Bonos" class="mt-3" />
                    @forelse($user->employee->bonuses as $bonus)
                    @php
                    $bonus = App\Models\Bonus::find($bonus);
                    @endphp
                    <span class="text-xs mr-1 rounded-full py-1 px-2 bg-blue-500 text-white mb-2">
                        {{ $bonus->name . " ($" . $bonus->amount($user->employee->hours_per_week) . ")"  }}
                    </span>
                    @empty
                    <span>Sin bonos</span>
                    @endforelse
                </div>
                <div>
                    <x-jet-label value="Día(s) de descanso" class="mt-3" />
                    @forelse($user->employee->days_off as $day_off)
                    <span class="text-xs mr-1 rounded-full py-1 px-2 bg-blue-500 text-white">
                        {{ App\Models\Employee::WEEK[$day_off] }}
                    </span>
                    @empty
                    <span>Sin descanso</span>
                    @endforelse
                </div>
                <div class="col-span-2">
                    <x-jet-label value="Hora de entrada" class="mt-3" />
                    @if( count($user->employee->check_in_times) > 1)
                    <div class="flex flex-wrap">
                        @foreach($user->employee->getCheckInTimesWithDay() as $i => $check_in)
                        <span class="text-xs mr-1 mb-1 rounded-full py-1 px-2 bg-blue-500 text-white">
                            {{ mb_substr($i, 0, 2) . '-' . $check_in }}
                        </span>
                        @endforeach
                    </div>
                    @else
                    <span class="text-xs mr-1 rounded-full py-1 px-2 bg-blue-500 text-white">
                        {{ $user->employee->getCheckInTimesWithDay()[0] }}
                    </span>
                    @endif
                </div>
                @endif
            </div>
            @endif

        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cerrar
            </x-jet-secondary-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>