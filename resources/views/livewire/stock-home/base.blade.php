<div>
    <header class="bg-white shadow dark:bg-slate-800">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-400 leading-tight flex justify-between">
                <div class="flex items-center">
                    <i class="fas fa-box-open mr-2"></i>
                    Inventario
                </div>
                @if ($simple_product_stock)
                    @livewire('stock.create-stock')
                @else
                    @livewire('stock-composit-product.create-stock-composit-product')
                @endif
            </h2>
        </div>
    </header>

    <div class="flex items-center justify-between w-11/12 my-3 mx-auto">

        <div class="rounded-full p-2 bg-white dark:bg-gray-300 shadow-md">
            <span wire:click="$set('simple_product_stock', true)"
                class="{{ $simple_product_stock ? 'dark:bg-blue-200 dark:text-blue-600 dark:border-blue-700 bg-blue-100 text-blue-500 border border-blue-500 rounded-full p-1 hover:cursor-pointer' : 'text-gray-700 p-1 hover:cursor-pointer' }}">
                Productos simples
            </span>
            <span wire:click="$set('simple_product_stock', false)"
                class="{{ $simple_product_stock ? 'text-gray-700 p-1 hover:cursor-pointer' : 'dark:bg-blue-200 dark:text-blue-600 dark:border-blue-700 bg-blue-100 text-blue-500 border border-blue-500 rounded-full p-1 hover:cursor-pointer' }}">
                Productos compuestos
            </span>
        </div>
    </div>

    <!-- module type content (simple or composite products) -->
    <div wire:loading.remove wire:target="toggleTrue,toggleFalse">
        @if ($simple_product_stock)
            @livewire('stock.stocks')
        @else
            @livewire('stock-composit-product.stock-composit-products')
        @endif
    </div>
    <div wire:loading.block wire:target="toggleTrue,toggleFalse" class="text-center mt-10">
        <i class="fas fa-circle-notch text-4xl text-gray-500 animate-spin"></i>
    </div>

</div>
