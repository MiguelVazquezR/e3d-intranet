<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nóminas semana {{ $pay_roll->week }}</title>
    <!-- <link rel="stylesheet" href="{{ public_path('css/app.css') }}" type="text/css"> -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
</head>

<body class="relative text-xs">


    @foreach ($users as $user)
        <div class="grid grid-cols-3 gap-1">
            <div class="col-span-2 flex justify-between">
                <!-- logo -->
                <x-jet-application-logo class="h-auto w-1/5 mr-3 inline-block" />
                <!-- address -->
                <span class="text-gray-500" style="font-size: 10px;">{{ $organization->address }} CP
                    {{ $organization->post_code }}</span>
            </div>
            <div class="col-span-2">
                <!-- Table -->
                <div class="w-full px-2 bg-white shadow-lg rounded-lg border border-gray-400 mt-1">
                    <header class="px-2 py-2 border-b border-gray-200">
                        <h2 class="font-semibold text-gray-800">
                            <div class="flex items-center justify-between mt-1">
                                <!-- name -->
                                <p> <i class="fas fa-user"></i> {{ $user->name }} </p>
                                <!-- period -->
                                <p> <i class="fas fa-calendar-alt"></i> del
                                    {{ $pay_roll->start_period->isoFormat('DD MMMM YYYY') }} al
                                    {{ $pay_roll->end_period->isoFormat('DD MMMM YYYY') }} </p>
                                @if ($user->additionalTimeRequest($pay_roll->id))
                                    @if ($user->additionalTimeRequest($pay_roll->id)->authorized_by)
                                        <p class="bg-green-50 text-green-500 rounded-full px-1 py-px border">
                                            {{ substr($user->additionalTimeRequest($pay_roll->id)->additional_time, 0, 5) }} hrs.
                                            autorizadas</p>
                                    @endif
                                @endif
                            </div>
                        </h2>
                    </header>
                    <div class="p-px">
                        <table class="table-auto w-full">
                            <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                                <tr>
                                    <th class="p-px whitespace-nowrap">
                                        <div class="font-semibold text-left">dia</div>
                                    </th>
                                    <th class="p-px whitespace-nowrap">
                                        <div class="font-semibold text-left">entrada</div>
                                    </th>
                                    <th class="p-px whitespace-nowrap">
                                        <div class="font-semibold text-left">inicio break</div>
                                    </th>
                                    <th class="p-px whitespace-nowrap">
                                        <div class="font-semibold text-left">fin break</div>
                                    </th>
                                    <th class="p-px whitespace-nowrap">
                                        <div class="font-semibold text-left">salida</div>
                                    </th>
                                    <th class="p-px whitespace-nowrap">
                                        <div class="font-semibold text-left">Hrs. x día</div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-200 text-gray-600">
                                @foreach ($user->currentWeekRegisters($pay_roll->id) as $i => $register)
                                    <tr>
                                        <td class="p-px whitespace-nowrap text-left font-semibold w-1/6">
                                            {{ $pay_roll->start_period->addDays($i)->isoFormat('dddd, DD-MM') }}
                                        </td>
                                        @if (!is_string($register) && !is_null($register))
                                            <td class="p-px whitespace-nowrap text-left">
                                                <span
                                                    class="{{ $register->late ? 'text-yellow-500 font-semibold' : '' }}">{{ $register->check_in }}</span>
                                            </td>
                                            <td class="p-px whitespace-nowrap text-left">
                                                <span>{{ $register->start_break }}</span>
                                            </td>
                                            <td class="p-px whitespace-nowrap text-left">
                                                <span>{{ $register->end_break }}</span>
                                            </td>
                                            <td class="p-px whitespace-nowrap text-left">
                                                <span>{{ $register->check_out }}</span>
                                            </td>
                                            <td class="p-px whitespace-nowrap text-left">
                                                <span>
                                                    {{ $user->timeForRegister($register) }}
                                                    @if ($register->extras_enabled)
                                                        <span class="text-green-500" style="font-size: 10px;"> +
                                                            {{ $user->extraTime($register) }} (dobles)</span>
                                                    @endif
                                                </span>
                                            </td>
                                        @elseif($register == 'Falta' || is_null($register))
                                            <td colspan="4" class="p-px whitespace-nowrap text-left">
                                                <div class="bg-red-100 rounded-lg text-red-600 p-px font-semibold">Falta
                                                </div>
                                            </td>
                                            <td colspan="4" class="p-px whitespace-nowrap text-left">
                                                <div class="bg-red-100 rounded-lg text-red-600 p-px font-semibold">--:--
                                                </div>
                                            </td>
                                        @else
                                            <td colspan="4" class="p-px whitespace-nowrap text-left">
                                                <div class="bg-green-100 rounded-lg text-green-600 p-px font-semibold">
                                                    {{ $register }}</div>
                                            </td>
                                            <td colspan="4" class="p-px whitespace-nowrap text-left">
                                                <div class="bg-green-100 rounded-lg text-green-600 p-px font-semibold">
                                                    {{ $user->timeForRegister($register) }}</div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- details -->
            <div class="grid grid-cols-2 gap-x-2 leading-snug uppercase text-xs" style="font-size: 11px;">
                <span class="col-span-2">Fecha:</span>
                <span class="">semanal</span>
                <span class="">${{ $user->weekSalary() }}</span>
                <span class="">faltas</span>
                <span class="">{{ $user->absences($pay_roll->id) }}</span>
                <span class="">hrs semanales</span>
                <span class="">{{ $user->employee->hours_per_week }}</span>
                <span class="">hrs hechas</span>
                <span class="">
                    @if ($user->totalTime($pay_roll->id, false, true) < $user->employee->hours_per_week)
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    @endif
                    {{ $user->totalTime($pay_roll->id, true, true) }}
                </span>
                <span class="">sueldo/hora</span>
                <span class="">${{ $user->employee->salary }}</span>
                @foreach ($user->getBonuses($pay_roll->id) as $bonus_id => $earned)
                    @php
                        $bonus = App\Models\Bonus::find($bonus_id);
                    @endphp
                    <span class="">{{ $bonus->name }}</span>
                    <span class="">${{ $earned }}</span>
                @endforeach
                <span class="">sueldo sin bonos</span>
                <span class="">${{ $user->normalSalary($pay_roll->id) }}</span>
                <span class="">Hrs extra</span>
                <span class="">{{ $user->totalExtraTime($pay_roll->id) }}
                    (${{ $user->extraSalary($pay_roll->id) }})</span>
                <span class="">deducciones</span>
                <span class="">-${{ $user->employee->discounts }}</span>
                <span class="">total</span>
                <span class="">${{ $user->totalSalary($pay_roll->id) }}</span>
                <span class="col-span-2">&nbsp;</span>
                <span class="col-span-2">firma</span>
            </div>
        </div>
        <p class="text-gray-500 mb-6 mt-1 leading-tight" style="font-size: 6px;">RECIBI DE LA EMPRESA EMBLEMAS 3D MEXICO
            SA
            DE CV LA CANTIDAD SEÑALADA
            MISMA QUE CUBRE LAS PERCEPCIONES QUE ME CORRESPONDEN EN EL PERIODO INDICADO,
            NO EXISTIENDO NINGUN ADEUDO POR PARTE DE LA EMPRESA PARA CON EL SUSCRITO,
            PUES ESTOY TOTALMENTE PAGADO DE MIS SALARIOS Y ESTE DOCUMENTO ES UNICAMENTE
            PARA FINES INFORMATIVOS SIN QUE PUEDA SER USADO COMO MEDIO PROBATORIO ANTE
            UNA AUTORIDAD Y/O INSTITUCION DE CREDITO.
        </p>
    @endforeach

</body>

</html>
