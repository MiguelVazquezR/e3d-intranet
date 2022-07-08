@props(['disabled' => false, 'options' => [], 'default' => null, 'id' => 'id', 'name' => 'name'])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'text-gray-600 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm disabled:bg-gray-100 disabled:text-gray-500']) !!}>
    <option value="" selected>{{ $default ?? '-- Seleccione --'}}</option>
    @forelse($options as $option)
        <option value="{{ $option->$id }}">{{ $option->$name }}</option>
    @empty
        <option value="">No hay ningún elemento registrado</option>
    @endforelse
</select>
