<div>
    <header class="bg-white shadow dark:bg-slate-800">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-400 leading-tight flex justify-between">
                <div class="flex items-center">
                    <i class="fas fa-box-open mr-2"></i>
                    Departamento de mercadotecnia
                </div>
                @if ($projects_tab)
                    @livewire('marketing.create-project')
                @endif
            </h2>
        </div>
    </header>

    {{-- tabs --}}
     <!--<div class="flex items-center justify-between w-11/12 my-3 mx-auto">
        <div class="rounded-full p-2 dark:bg-gray-400 bg-white shadow-md">
            <span wire:click="toggleTrue"
                class="{{ $projects_tab ? 'dark:bg-blue-300 dark:text-blue-700 dark:border-blue-700 bg-blue-100 text-blue-500 border border-blue-500 rounded-full p-1 hover:cursor-pointer' : 'text-gray-700 p-1 hover:cursor-pointer' }}">
                Proyectos
            </span>
            <span wire:click="toggleFalse"
                class="{{ $projects_tab ? 'text-gray-700 p-1 hover:cursor-pointer' : 'dark:bg-blue-300 dark:text-blue-700 dark:border-blue-700 bg-blue-100 text-blue-500 border border-blue-500 rounded-full p-1 hover:cursor-pointer' }}">
                Órdenes
            </span>
        </div>
    </div>-->

    <!-- module type content (projects or orders) -->
    <div wire:loading.remove wire:target="toggleTrue,toggleFalse">
        @if ($projects_tab)
            @livewire('marketing.m-d-projects-index')
        @else
            @livewire('marketing.m-d-orders-index')
        @endif
    </div>
    <div wire:loading.block wire:target="toggleTrue,toggleFalse" class="text-center mt-10">
        <i class="fas fa-circle-notch text-4xl text-gray-500 animate-spin"></i>
    </div>

</div>
