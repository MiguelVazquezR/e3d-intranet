@props(['customer'])

<div class="grid grid-cols-2 gap-2 text-xs mt-2 font-bold">
    <p>Razón social: <span class="font-normal">{{ $customer->company->bussiness_name }}</span></p>
    <p>RFC: <span class="font-normal">{{ $customer->company->rfc }}</span></p>
    <p>Sucursal: <span class="font-normal">{{ $customer->name }}</span></p>
    <p>Método de pago: <span class="font-normal">{{ $customer->satMethod->key }} - {{ $customer->satMethod->description }}</span></p>
    <p>Medio de pago: <span class="font-normal">{{ $customer->satWay->key }} - {{ $customer->satWay->description }}</span></p>
    <p>Uso de factura: <span class="font-normal">{{ $customer->satType->key }} - {{ $customer->satType->description }}</span></p>
    <p class="col-span-2">Dirección: <span class="font-normal">{{ $customer->address }} - C.P.{{ $customer->post_code }}</span></p>
</div>