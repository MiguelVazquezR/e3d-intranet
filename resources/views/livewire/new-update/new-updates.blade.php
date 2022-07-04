<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
        <div class="flex items-center">
            <i class="fa fa-book mr-2"></i>
            Novedades
        </div>
        @livewire('new-update.create-new-update')
    </h2>
</x-slot>
<div>
    <!-- component -->
    <div class="container bg-gray-100 mx-auto w-full h-full">
        <div class="relative wrap overflow-hidden p-10 h-full">
            <div class="lg:border-2-2 absolute border-opacity-20 border-gray-700 h-full lg:border" style="left: 50%"></div>
            @forelse ($updates as $i => $update)
                <div
                    class="mb-1 flex justify-between items-center w-full {{ $i % 2 == 0 ? 'right-timeline' : 'left-timeline flex-row-reverse' }}">
                    <div class="order-1 lg:w-5/12"></div>
                    <div class="z-20 flex items-center order-1 bg-gray-800 shadow-xl w-8 h-8 rounded-full">
                        <h1 class="mx-auto font-semibold text-lg text-white">{{ $i + 1 }}</h1>
                    </div>
                    <div
                        class="order-1 {{ $i % 2 == 0 ? 'bg-blue-400' : 'bg-red-400' }} rounded-lg shadow-xl w-5/6 lg:w-5/12 px-6 py-4">
                        <div class="mb-1">
                            <h3 class="font-bold text-gray-800 text-xl">{{ $update->title }}</h3>
                            <span class="text-xs text-gray-600">
                                <i class="far fa-calendar-alt"></i>
                                {{ $update->created_at->isoFormat('dddd DD MMMM, YYYY') }}
                                <i class="far fa-clock ml-1"></i>
                                {{ $update->created_at->isoFormat('hh:mm a') }}
                            </span>
                        </div>
                        <p class="text-sm leading-snug tracking-wide text-gray-900 text-opacity-100">
                            {!! $update->description !!}
                        </p>
                    </div>
                </div>
            @empty
                <h1>No hay actualizaciones </h1>
            @endforelse
        </div>
    </div>
</div>