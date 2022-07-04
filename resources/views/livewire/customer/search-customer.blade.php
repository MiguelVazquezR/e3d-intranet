<div>
    <x-jet-label value="Cliente" class="mt-3" />
    <x-jet-input 
    wire:model="query"
    wire:keydown.tab="clear"
    wire:keydown.arrow-up="decrementIndex"
    wire:keydown.arrow-down="incrementIndex"
    wire:keydown.enter="selectCustomer"
    type="text"
    class="w-full mt-2 relative placeholder:text-sm"
    placeholder="Buscar cliente"
     />

    @if(!empty($query))
    <div wire:click="clear" class="fixed top-0 bottom-0 left-0 right-0"></div>
    <div class="absolute z-50 w-2/3 bg-white text-xs text-gray-600 border-2 rounded-b-2xl shadow-lg p-3">
        @if(!empty($customers))
        @foreach($matching_customers as $i => $customer)
        <p wire:click="selectCustomer({{$i}})" class="hover:cursor-pointer hover:bg-gray-200 p-1 rounded-lg {{ $selected_index == $i ? 'bg-gray-200' : '' }}">
            {{$customer['name']}}
        </p>
        @endforeach
        @else
        <div>Sin resultados</div>
        @endif
    </div>
    @endif
</div>