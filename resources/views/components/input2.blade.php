@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-0 ring-0 outline-0 focus:outline-0 border-gray-400 focus:border-b focus:ring-0 bg-transparent w-full leading-tight rounded-md p-1 ']) !!}>

<div class="border-0 ring-0 outline-0 "></div>