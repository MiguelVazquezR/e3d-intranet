<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            @if ($project)
                <span class="font-bold">{{ $project->project_name }}</span>
            @endif
        </x-slot>

        <x-slot name="content">
            @if ($project)
                <div>
                    <x-jet-label value="Objetivo(s)" />
                    <p>{!! $project->objective !!}</p>
                </div>
                <div class="lg:grid grid-cols-3 gap-x-2">
                    <div class="mt-3">
                        <x-jet-label value="Costo de desarrollo" />
                        <span>MXN$ {{ $project->project_cost }}</span>
                    </div>
                    <div class="mt-3">
                        <x-jet-label value="Propietario" />
                        <x-avatar-with-title-subtitle :user="$project->owner">
                            <x-slot name="title">
                                {{ $project->owner->name }}
                            </x-slot>
                            <x-slot name="subtitle">
                                <span class="text-xs text-gray-400">
                                    {{ $project->created_at->isoFormat('D MMMM YYYY, hh:mm a') }}
                                </span>
                            </x-slot>
                        </x-avatar-with-title-subtitle>
                    </div>
                    <div class="mt-3">
                        <x-jet-label value="Autorización" />
                        @if ($project->authorizedBy)
                            <x-avatar-with-title-subtitle :user="$project->authorizedBy">
                                <x-slot name="title">
                                    {{ $project->authorizedBy->name }}
                                </x-slot>
                                <x-slot name="subtitle">
                                    <span class="text-xs text-gray-400">
                                        {{ $project->authorized_at->isoFormat('D MMMM YYYY, hh:mm a') }}
                                    </span>
                                </x-slot>
                            </x-avatar-with-title-subtitle>
                        @else
                            <span class="text-red-500 bg-red-100 rounded px-2 py-px mt-1">No autorizado</span>
                        @endif
                    </div>
                </div>

                {{-- marketing tasks --}}
                <div class="mx-auto text-gray-500 mt-3">
                    <h2 class="mb-2 text-2xl font-semibold leading-tight">Tareas del proyecto</h2>
                    <div
                        class="overflow-auto max-h-72 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100">
                        <table class="min-w-full text-xs">
                            <thead class="bg-white border">
                                <tr class="text-left">
                                    <th class="p-3">Tarea</th>
                                    <th class="p-3">Fecha estimada de término</th>
                                    <th class="p-3">Involucrados</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks as $task)
                                    <tr class="border border-opacity-20 border-gray-700 bg-white">
                                        <td class="p-3">
                                            <p>{{ $task->description }}</p>
                                        </td>
                                        <td class="p-3">
                                            <p>{{ $task->estimated_finish->isoFormat('DD MMM YYYY hh:mm a') }}</p>
                                        </td>
                                        <td class="p-3">
                                            @foreach ($task->users as $user)
                                                <p
                                                    class="text-xs rounded lg:rounded-full px-2 py-px bg-blue-100 text-gray-600 mt-1">
                                                    {{ $user->name }}</p>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @if (auth()->user()->can('autorizar_proyectos_marketing'))
                    <div wire:ignore>
                        <x-jet-label value="Dejar comentarios" class="mt-3" />
                        <textarea wire:model.refer="feedback" rows="3" class="w-full border-gray-300"></textarea>
                    </div>
                @else
                    <x-jet-label value="Comentarios" class="mt-3" />
                    <div
                        class="{{ $project->feedbak ? 'px-4 py-2 text-sm bg-blue-100 text-gray-700 shadow-lg rounded-lg' : 'text-gray-400 text-xs' }} mb-2">
                        {!! $project->feedbak ?? 'No hay comentarios de retroalimentación' !!}</div>
                @endif
            @endif

            {{-- buttons --}}
            <div>
                <button
                    class="mt-1 text-white bg-gradient-to-r from-green-500 via-green-600 to-green-700 hover:bg-gradient-to-br focus:ring-1 focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-3 py-1 text-center disabled:opacity-25 disabled:cursor-not-allowed">
                    button
                </button>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>
