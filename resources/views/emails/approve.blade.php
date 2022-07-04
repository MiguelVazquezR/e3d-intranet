<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <title>Aprobar</title>
</head>

<body>
    <p>
        <b>{{ $model->creator->name }}</b> ha creado nueva <b>{{ $module_name }}</b>
        con ID <b>{{ $model->id }}</b> y se requiere de su aprobación.
    </p>
</body>

</html>
