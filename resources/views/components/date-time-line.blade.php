@props(['date', 'time'])

<div>
    <i class="far fa-calendar-alt"></i>
    {{ $date }}
    <i class="far fa-clock ml-3"></i>
    {{ $time }}
</div>