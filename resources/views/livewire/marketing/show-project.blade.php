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
                    <div class="px-4 py-2 text-sm dark:bg-blue-300 dark:text-blue-700 bg-blue-100 text-gray-700 shadow-lg rounded-lg">{!! $project->objective !!}
                    </div>
                </div>
                <div class="lg:grid grid-cols-3 gap-x-2">
                    <div class="mt-3">
                        <x-jet-label value="Costo de desarrollo" />
                        <span>MXN$ {{ number_format($project->project_cost, 2) }}</span>
                    </div>
                    <div class="mt-3">
                        <x-jet-label value="Propietario" />
                        <x-avatar-with-title-subtitle :user="$project->creator">
                            <x-slot name="title">
                                {{ $project->creator->name }}
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
                            <thead class="bg-white border dark:bg-slate-700 dark:text-gray-400">
                                <tr class="text-left">
                                    <th class="p-3">Tarea</th>
                                    <th class="p-3">Fecha estimada de término</th>
                                    <th class="p-3">Involucrados</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks as $task)
                                    <tr x-data="{ tooltip_open: false }"
                                        class="relative border border-opacity-20 border-gray-700 bg-white dark:bg-slate-700 dark:border-slate-500 dark:text-gray-400">
                                        <td class="p-3">
                                            <p>
                                                {{ $task->description }}
                                            </p>
                                        </td>
                                        <td class="p-3">
                                            <p>{{ $task->estimated_finish->isoFormat('DD MMM YYYY hh:mm a') }}</p>
                                        </td>
                                        <td class="p-3">
                                            @foreach ($task->users as $user)
                                                <div title="{{ $user->pivot->finished_at ? 'Tarea completada' : 'Tarea sin completar' }}"
                                                    class="flex justify-between items-center text-xs rounded lg:rounded-full px-2 py-px {{ $user->pivot->finished_at ? 'bg-green-100 dark:bg-green-300' : 'dark:bg-blue-300 bg-blue-100' }} text-gray-600 mt-1">
                                                    @if ($user->pivot->finished_at)
                                                        <i class="fas fa-check text-green-400 mr-2"></i>
                                                        <div class="flex flex-col">
                                                            {{ $user->name }}
                                                            <div>
                                                                <span title="{{ $user->pivot->finished_at }}"
                                                                    class="text-gray-400 cursor-help"
                                                                    title="{{ $user->pivot->finished_at }}">{{ Carbon\Carbon::parse($user->pivot->finished_at)->diffForHumans() }}
                                                                </span>
                                                                @php
                                                                    $_evidence = App\Models\MarketingResult::where('marketing_task_user_id', $user->pivot->id)->first();
                                                                @endphp
                                                                <a href="{{ Storage::url($_evidence->file) }}"
                                                                    target="_blank">
                                                                    <i title="Ver evidencia"
                                                                        class="fas fa-file cursor-pointer text-green-400 hover:text-green-600 ml-3"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @else
                                                        {{ $user->name }}
                                                    @endif
                                                    @if ($project->authorizedBy && auth()->user()->id == $user->id && !$user->pivot->finished_at)
                                                        <span @click="tooltip_open = !tooltip_open"
                                                            class="flex items-center justify-center cursor-pointer w-4 h-4 rounded-full text-blue-400 dark:text-blue-900 dark:bg-blue-300 dark:border-blue-700 bg-blue-100 border-blue-400 border"
                                                            title="Marcar como terminada">
                                                            <i class="fas fa-angle-up" style="font-size: 10px;"></i>
                                                        </span>
                                                        <div x-show="tooltip_open" x-transition
                                                            x-transition:enter.duration.500ms
                                                            x-transition:leave.duration.400ms title="Evidencia de tarea"
                                                            class="w-full lg:w-2/3 absolute right-0 lg:right-4 -top-6 px-3 py-1 rounded bg-gray-800 text-white shadow">
                                                            <div class="flex items-center">
                                                                <x-jet-label class="text-white hidden lg:block"
                                                                    value="Evidencia:" />
                                                                <div>
                                                                    <input wire:model.defer="evidence" type="file"
                                                                        class="text-xs mx-2 file:py-1 file:px-1
                                                                        file:rounded-full file:border-0
                                                                        file:text-xs file:font-semibold
                                                                        file:bg-blue-50 file:text-blue-700
                                                                        hover:file:bg-blue-100"
                                                                        id="{{ $evidence_id }}">
                                                                    <x-jet-input-error for="evidence" class="text-xs" />
                                                                </div>
                                                                <i wire:loading wire:target="evidence"
                                                                    class="fas fa-sync-alt text-sm text-gray-400 animate-spin"></i>
                                                                @if ($evidence)
                                                                    <button wire:loading.remove wire:target="evidence"
                                                                        @click="tooltip_open = false"
                                                                        wire:click="completeTask({{ $task->id }}, {{ $user->id }}, {{ $user->pivot->id }})"
                                                                        class="flex justify-center items-center rounded-full w-5 h-5 bg-blue-300"
                                                                        title="Subir evidencia"><i
                                                                            class="fas fa-upload text-xs text-blue-600"></i></button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- feedback --}}
                @if (auth()->user()->can('autorizar_proyectos_mercadotecnia'))
                    <div wire:ignore>
                        <x-jet-label value="Dejar comentarios" class="mt-3 dark:text-gray-400" />
                        <textarea wire:model="project.feedback" rows="3" class="w-full border-gray-300 input !h-[6rem]"></textarea>
                    </div>
                    {{-- buttons --}}
                    <div>
                        <button wire:click="sendFeedback" wire:loading.attr="disabled" wire:target="sendFeedback"
                            class="text-white bg-gradient-to-r from-green-500 via-green-600 to-green-700 hover:bg-gradient-to-br focus:ring-1 focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-3 py-1 text-center disabled:opacity-25 disabled:cursor-not-allowed">
                            Enviar comentario
                        </button>
                    </div>
                @else
                    <x-jet-label value="Comentarios" class="mt-3 dark:text-gray-400" />
                    <div
                        class="{{ $project->feedback ? 'px-4 py-2 text-sm dark:bg-blue-300 bg-blue-100 text-gray-700 shadow-lg rounded-lg' : 'text-gray-400 text-xs' }} mb-2">
                        {{ $project->feedback ?? 'No hay comentarios de retroalimentación' }}</div>
                @endif
            @endif

        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>
            @if ($project)
                @if (auth()->user()->can('autorizar_proyectos_mercadotecnia'))
                    @if (!$project->authorized_by_id)
                        <x-jet-button wire:click="authorize" wire:loading.attr="disabled" wire:target="authorize"
                            class="disabled:opacity-25">
                            Autorizar
                        </x-jet-button>
                    @else
                        <x-jet-danger-button wire:click="cancel" wire:loading.attr="disabled" wire:target="cancel"
                            class="disabled:opacity-25">
                            Cancelar
                        </x-jet-danger-button>
                    @endif
                @endif
            @endif
        </x-slot>

    </x-jet-dialog-modal>

</div>
