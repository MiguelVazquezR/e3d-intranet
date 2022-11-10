<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Ver nóminas
        </x-slot>

        <x-slot name="content">
            @if($pay_roll)
            <h1 class="text-blue-500 font-bold">
                Semana {{ $pay_roll->week }}:
                {{ $pay_roll->start_period->isoFormat('DD MMMM YYYY') }} al {{ $pay_roll->end_period->isoFormat('DD MMMM YYYY') }}
            </h1>
            @endif
            <x-jet-label value="Ver nómina de" class="mt-3" />
            <div class="grid grid-cols-3 lg:grid-cols-4 gap-3 mt-1 mb-6">
                @forelse($employees as $id => $name)
                <label class="inline-flex items-center mt-3 text-xs">
                    <input wire:model.defer="employees_selected" type="checkbox" value="{{$id}}" class="rounded">
                    <span class="ml-1 text-gray-700">{{$name}}</span>
                </label>
                @empty
                <p class="col-span-full text-sm text-red-700 mt-1">No hay nóminas esta semana</p>
                @endforelse
            </div>
            @if(count($employees_selected))
            <a href="{{ route('pay-roll-pdf', ['item' => json_encode(array($pay_roll->id, $employees_selected))] ) }}" target="_blank" class="mt-3 lg:mt-11 text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-3 py-1 text-center">
                Ver nóminas
            </a>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>