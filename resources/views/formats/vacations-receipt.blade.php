<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet | Recibo de vacaciones</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
</head>

<body class="text-xs">
    <div class="flex justify-around mb-3">
        <x-jet-application-logo class="h-5" />
        <h1>Recibo de vacaciones</h1>
    </div>
    <p class="mb-2">
        <b>{{ $user->name }}</b>, firma de recibido sus <b>{{ $user->employee->vacations }}</b> días de
        vacaciones con su respectiva prima vacacional,
        correspondientes del periodo <b>{{ $user->employee->currentVacationPeriod()['start'] }}</b> al
        <b>{{ $user->employee->currentVacationPeriod()['end'] }}</b>, ya habiéndose descontado <b>{{ $user->employee->vacationsTaken()->count() }}</b> día(s) otorgados
        a cuenta de vacaciones dentro del periodo anterior.
    </p>
    @if($user->employee->vacationsTaken()->count())
    <p class="mb-1">Día(s) de vacaciones otorgado(s):</p>
        @foreach ($user->employee->vacationsTaken() as $register)
        <li class="list-disc">{{ $register->day->isoFormat('dddd, DD MMMM YYYY') }}</li>
        @endforeach
    @endif
    <div class="mt-2 mb-10 grid grid-cols-2 gap-x-8">
        <p class="flex justify-between">
            <span>Sueldo por hora</span>
            <span>${{ $user->employee->salary }}</span>
        </p>
        <p class="flex justify-between">
            <span>Horas por día</span>
            <span>{{ $user->employee->hoursPerDay() }}</span>
        </p>
        <p class="flex justify-between">
            <span>Pago por días de vacaciones</span>
            <span>${{ $user->employee->vacationsPay() }}</span>
        </p>
        <p class="flex justify-between">
            <span>Prima vacacional</span>
            <span>${{ $user->employee->vacationsBonus() }}</span>
        </p>
        <p class="flex justify-between">
            <span>Total recibido</span>
            <span>${{ $user->employee->totalVacationsPay() }}</span>
        </p>

    </div>

    <span class="border-t-2 border-black pt-1 px-5">
        Firma de recibido
    </span>
</body>

</html>
