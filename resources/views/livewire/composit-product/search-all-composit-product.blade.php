<div>
    <x-jet-label value="Producto" class="mt-3" />
    <x-jet-input 
    wire:model="query"
    wire:keydown.tab="clear"
    wire:keydown.arrow-up="decrementIndex"
    wire:keydown.arrow-down="incrementIndex"
    wire:keydown.enter="selectCompositProduct"
    type="text"
    class="w-full mt-2 relative placeholder:text-sm"
    placeholder="Buscar producto"
     />
    @if(!empty($query))
    <div wire:click="clear" class="fixed top-0 bottom-0 left-0 right-0"></div>
    <div class="absolute z-50 w-2/3 dark:text-gray-400 dark:bg-slate-900 bg-white text-xs text-gray-600 border-2 rounded-b-2xl shadow-lg p-3">
        @if(!empty($composit_products))
        @foreach($matching_composit_products as $i => $composit_product)
        <p wire:click="selectCompositProduct({{$i}})" class="hover:cursor-pointer hover:bg-gray-200 p-1 rounded-lg {{ $selected_index == $i ? 'bg-gray-200' : '' }}">
            {{$composit_product['alias']}}
        </p>
        @endforeach
        @else
        <div>Sin resultados</div>
        @endif
    </div>
    @endif
</div>