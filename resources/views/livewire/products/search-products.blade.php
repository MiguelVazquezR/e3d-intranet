<div>
    <x-jet-label value="Producto" class="mt-3 dark:text-gray-400" />
    <x-select class="mt-2 w-full input" wire:model.defer="family" :options="$families" default="Todas las familias" />
    <x-jet-input 
    wire:model="query"
    wire:keydown.tab="clear"
    wire:keydown.arrow-up="decrementIndex"
    wire:keydown.arrow-down="incrementIndex"
    wire:keydown.enter="selectProduct"
    type="text"
    class="w-full mt-2 relative placeholder:text-sm input"
    placeholder="Buscar producto"
     />

    @if(!empty($query))
    <div wire:click="clear" class="fixed top-0 bottom-0 left-0 right-0"></div>
    <div class="absolute z-50 w-2/3 dark:bg-slate-900 bg-white text-xs text-gray-600 border-2 rounded-b-2xl shadow-lg p-3">
        @if(!empty($products))
        @foreach($matching_products as $i => $product)
        <p wire:click="selectProduct({{$i}})" class="hover:cursor-pointer hover:bg-gray-200 p-1 rounded-lg {{ $selected_index == $i ? 'bg-gray-200' : '' }}">{{$product['name']}}</p>
        @endforeach
        @else
        <div>Sin resultados</div>
        @endif
    </div>
    @endif
</div>