@if($active)
<div class="flex justify-between text-sm border-b-2 py-2">
    {{$slot}}
    <div class="flex items-center">
        {{ $aditional_buttons ?? ''}}
        @if($edit)
        <i wire:click="{{$edit_method_name}}({{ $index }})" class="fas fa-edit mr-3 text-blue-500 hover:cursor-pointer"></i>
        @endif
        @if($delete)
        <i wire:click="{{$delete_method_name}}({{ $index }})" class="fas fa-trash-alt text-red-500 hover:cursor-pointer"></i>
        @endif
    </div>
</div>
@else
<div class="flex justify-between border-b-2 py-3 text-gray-500">
    <span class="text-xs">
        {{$inactive_message}}
    </span>
    @if($can_undo)
    <span wire:click="{{$undo_method_name}}( {{$object_id}} )" class="text-sm text-blue-500 hover:cursor-pointer">
        Deshacer
    </span>
    @endif
</div>
@endif