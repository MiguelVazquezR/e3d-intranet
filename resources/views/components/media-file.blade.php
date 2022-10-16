@props(['nextFolder', 'media'])

<div class="flex items-center">
    @if ($nextFolder)
        <i onclick="Livewire.emitTo('media-library.index', 'change-current-path', '{{ $nextFolder }}')"
            class="fas fa-folder mr-2 text-3xl text-blue-300 cursor-pointer"></i>
        <span onclick="Livewire.emitTo('media-library.index', 'change-current-path', '{{ $nextFolder }}')"
            class="text-gray-700 text-sm cursor-pointer">{{ $nextFolder }}</span>
    @else
        <a href="{{ $media->getUrl() }}" target="_blank" class="cursor-pointer">
            <i class="fas fa-file text-gray-400 text-2xl mr-2"></i>
            <span class="text-gray-700 text-sm">{{ $media->name }}</span>
        </a>
    @endif
</div>

<script>
    function changeCurrentPath(param) {
        Livewire.emitTo('media-library.index', 'change-current-path');
    }
</script>
