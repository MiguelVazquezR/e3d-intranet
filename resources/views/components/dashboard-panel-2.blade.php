@props(['title', 'icon', 'modalParam' => null])

<div {{ $attributes->merge(['class' => 'bg-white shadow-xl sm:rounded-lg mb-4 px-3']) }}>
    <div class="bg-white border-b lg:border-b-0 lg:border-gray-300 sm:rounded-lg">
        <div class="{{ $modalParam ? 'flex justify-between' : 'text-center' }} py-1 sm:rounded-t-lg">
            <span>{{ $title }} {!! $icon !!}</span>
            @if ($modalParam)
                <i wire:click="showDetails({{ $modalParam }})" class="fas fa-external-link-alt hover:cursor-pointer"></i>
            @endif
        </div>
        <div class="max-h-56 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100">
            {{ $slot }}
        </div>
    </div>
</div>
