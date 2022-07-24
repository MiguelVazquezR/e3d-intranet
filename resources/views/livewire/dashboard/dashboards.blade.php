<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
            <div class="flex items-center">
                <i class="fas fa-home mr-2"></i>
                Inicio
            </div>
            <a href="{{ route('news') }}" class="text-blue-400">
                <i class="fas fa-book mr-1"></i>
                Novedades
            </a>
        </h2>
    </x-slot>

    <div class="py-12 px-6">
        <div wire:loading wire:target="showDetails">
            <x-loading-indicator />
        </div>

        <h2 class="text-2xl text-gray-400 mb-3 ml-6">Mios</h2>
        <div class="lg:grid lg:grid-cols-4 lg:gap-3 mb-2">

            <!-- Reminders -->
            <x-dashboard-panel-2 class="lg:col-span-2 mb-4" title="Recordatorios"
                icon='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-alarm-fill inline-block" viewBox="0 0 16 16">
                <path d="M6 .5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1H9v1.07a7.001 7.001 0 0 1 3.274 12.474l.601.602a.5.5 0 0 1-.707.708l-.746-.746A6.97 6.97 0 0 1 8 16a6.97 6.97 0 0 1-3.422-.892l-.746.746a.5.5 0 0 1-.707-.708l.602-.602A7.001 7.001 0 0 1 7 2.07V1h-.5A.5.5 0 0 1 6 .5zm2.5 5a.5.5 0 0 0-1 0v3.362l-1.429 2.38a.5.5 0 1 0 .858.515l1.5-2.5A.5.5 0 0 0 8.5 9V5.5zM.86 5.387A2.5 2.5 0 1 1 4.387 1.86 8.035 8.035 0 0 0 .86 5.387zM11.613 1.86a2.5 2.5 0 1 1 3.527 3.527 8.035 8.035 0 0 0-3.527-3.527z"/>
              </svg>'>
                <div class="flex justify-center items-center text-sm text-gray-500 p-4">
                    <div class="mr-3">
                        No hay recordatorios para hoy
                    </div>
                </div>
            </x-dashboard-panel-2>

            <!-- meetings -->
            <x-dashboard-panel-2 class="lg:col-span-2" title="Reuniones" icon="<i class='fas fa-handshake'></i>">
                <!-- as creator -->
                @foreach ($meetings_as_creator as $meeting)
                    <div class="my-3 ml-4 text-sm border-b pb-2 mr-3">
                        <div class="text-right">
                            <span class="text-xs font-normal text-gray-500 rounded-full bg-green-100"> Creado
                                por mi </span>
                            <span
                                class="ml-4 text-xs font-normal text-gray-500 rounded-full {{ $meeting->status == 'Iniciada' ? 'bg-green-100' : 'bg-amber-100' }}">
                                {{ $meeting->status }} </span>
                        </div>
                        <div class="lg:grid lg:grid-cols-5 lg:gap-x-2">
                            <span class="font-semibold text-gray-500">Inicia</span>
                            <div class="lg:col-span-4 mb-1 font-normal leading-none text-gray-400">
                                <x-date-time-line :date="$meeting->start->isoFormat('dddd DD MMMM, YYYY')" :time="$meeting->start->isoFormat('hh:mm a')" />
                            </div>
                            <span class="font-semibold text-gray-500">Termina</span>
                            <div class="lg:col-span-4 mb-1 font-normal leading-none text-gray-400">
                                <x-date-time-line :date="$meeting->end->isoFormat('dddd DD MMMM, YYYY')" :time="$meeting->end->isoFormat('hh:mm a')" />
                            </div>
                            <span class="font-semibold text-gray-500">Título</span>
                            <p class="text-gray-400 lg:col-span-4">
                                {{ $meeting->title }}
                            </p>
                            <span class="font-semibold text-gray-500">Descripción</span>
                            <p class="text-gray-400 lg:col-span-4">
                                {{ $meeting->description }}
                            </p>
                            @if ($meeting->url)
                                <span class="font-semibold text-gray-500">URL</span>
                                <a href="{{ $meeting->url }}" target="_blank"
                                    class="lg:col-span-4 text-blue-400 italic">
                                    {{ $meeting->url }}
                                </a>
                            @else
                                <span class="font-semibold text-gray-500">Lugar</span>
                                <p class="text-gray-400 lg:col-span-4">
                                    {{ $meeting->location }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
                <!-- as participant -->
                @foreach ($meetings_as_participant as $meeting)
                    <div class="my-3 ml-4 text-sm border-b pb-2 mr-3">
                        <div class="text-right">
                            <span class="text-xs font-normal text-gray-500 rounded-full bg-blue-100">
                                Invitado por {{ $meeting->creator->name }} </span>
                            <span
                                class="ml-4 text-xs font-normal text-gray-500 rounded-full {{ $meeting->status == 'Iniciada' ? 'bg-green-100' : 'bg-amber-100' }}">
                                {{ $meeting->status }} </span>
                        </div>
                        <div class="lg:grid lg:grid-cols-5 lg:gap-x-2">
                            <span class="font-semibold text-gray-500">Inicia</span>
                            <div class="lg:col-span-4 mb-1 font-normal leading-none text-gray-400">
                                <i class="far fa-calendar-alt"></i>
                                {{ $meeting->start->isoFormat('dddd DD MMMM, YYYY') }}
                                <i class="far fa-clock ml-3"></i>
                                {{ $meeting->start->isoFormat('hh:mm a') }}
                            </div>
                            <span class="font-semibold text-gray-500">Termina</span>
                            <div class="lg:col-span-4 mb-1 font-normal leading-none text-gray-400">
                                <i class="far fa-calendar-alt"></i>
                                {{ $meeting->end->isoFormat('dddd DD MMMM, YYYY') }}
                                <i class="far fa-clock ml-3"></i>
                                {{ $meeting->end->isoFormat('hh:mm a') }}
                            </div>
                            <span class="font-semibold text-gray-500">Título</span>
                            <p class="text-gray-400 lg:col-span-4">
                                {{ $meeting->title }}
                            </p>
                            <span class="font-semibold text-gray-500">Descripción</span>
                            <p class="text-gray-400 lg:col-span-4">
                                {{ $meeting->description }}
                            </p>
                            @if ($meeting->url)
                                <span class="font-semibold text-gray-500">URL</span>
                                <a href="{{ $meeting->url }}" target="_blank"
                                    class="lg:col-span-4 text-blue-400 italic">
                                    {{ $meeting->url }}
                                </a>
                            @else
                                <span class="font-semibold text-gray-500">Lugar</span>
                                <p class="text-gray-400 lg:col-span-4">
                                    {{ $meeting->location }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
                @if (!$meetings_as_creator->count() && !$meetings_as_participant->count())
                    <div class="my-3 lg:col-span-full text-center text-sm text-gray-500">
                        No hay reuniones próximas
                    </div>
                @endif
            </x-dashboard-panel-2>

        </div>

        <h2 class="text-2xl text-gray-400 mb-3 mt-8 ml-6">Operativo</h2>
        <div class="lg:grid lg:grid-cols-4 lg:gap-3">

            <!-- quotes for authorize -->
            @can('autorizar_cotizaciones')
                <x-dashboard-panel-1 href="{{ route('quotes') }}" title="Cotizaciones por autorizar" :counter="$quotes_for_authorize->count()"
                    icon="fas fa-file-invoice-dollar" />
            @endcan

            <!-- SO for authorize -->
            @can('autorizar_ordenes_venta')
                <x-dashboard-panel-1 href="{{ route('sell-orders') }}" title="Órdenes de venta por autorizar"
                    :counter="$sell_orders_for_authorize->count()" icon="fas fa-hand-holding-usd" />
            @endcan

            <!-- PO for authorize -->
            @can('autorizar_ordenes_compra')
                <x-dashboard-panel-1 href="{{ route('purchase-orders') }}" title="Órdenes de compra por autorizar"
                    :counter="$purchase_orders_for_authorize->count()" icon="fas fa-shopping-cart" />
            @endcan

            <!-- design orders for authorize -->
            @can('autorizar_ordenes_diseño')
                <x-dashboard-panel-1 href="{{ route('design-orders') }}" title="Órdenes de diseño por autorizar"
                    :counter="$design_orders_for_authorize->count()" icon="fas fa-ruler-combined" />
            @endcan

            <!-- low stock -->
            @can('editar_inventarios')
                <x-dashboard-panel-1 href="{{ route('stocks') }}" title="Productos con existencia baja" :counter="$low_stock->count()"
                    icon="fas fa-box-open" />
            @endcan

            <!-- Production department -->
            @can('editar_departamento_producción')
                <x-dashboard-panel-1 href="{{ route('production-department') }}" title="Órdenes sin iniciar"
                    :counter="$production_to_start->count()" icon="fas fa-hard-hat" />
            @endcan

            <!-- design department -->
            @can('editar_departamento_diseño')
                <x-dashboard-panel-1 href="{{ route('design-department') }}" title="Órdenes sin iniciar" :counter="$design_to_start->count()"
                    icon="fas fa-drafting-compass" />
            @endcan

            <!-- SO to start -->
            @can('asignar_operadores_a_ov')
                <x-dashboard-panel-1 href="{{ route('sell-orders') }}" title="Órdenes de venta sin asignar tareas"
                    :counter="$so_to_start->count()" icon="fas fa-tasks" />
            @endcan

            <!-- Additional time for authorize -->
            @can('autorizar_tiempo_adicional')
                <x-dashboard-panel-1 href="{{ route('additional_time_requests') }}" title="Hrs adicionales por autorizar"
                    :counter="$additional_time_for_authorize->count()" icon="fas fa-user-clock" />
            @endcan
        </div>

        <h2 class="text-2xl text-gray-400 mb-3 mt-8 ml-6">Colaboradores (empleados)</h2>

        <div class="lg:grid lg:grid-cols-4 lg:gap-3 mb-2">

            <!-- weekly performance -->
            <x-dashboard-panel-2 class="lg:col-span-2" title="Desempeño semanal producción"
                icon="<i class='fas fa-medal'></i>">
                @if ($employee_performance)
                    <p class="text-center my-2 text-sm text-green-500 font-bold">
                        Agradecemos su esfuerzo
                    </p>
                @endif
                <div class="mb-1 text-sm text-gray-500 p-1">
                    @forelse($employee_performance as $performance)
                        @php
                            $user = App\Models\User::find($performance['user_id']);
                        @endphp
                        <div class="mb-3">
                            <x-avatar-with-title-subtitle :user="$user">
                                <x-slot name="title">
                                    {{ $user->name }}
                                </x-slot>
                                <x-slot name="subtitle">
                                    <p class="flex flex-col lg:flex-row items-center">
                                        <span>Tiempo invertido: {{ $performance['time'] }} min.</span>
                                        <i class="fas fa-circle px-1 hidden lg:inline" style="font-size: 4px;"></i>
                                        <span>Órdenes terminadas: {{ $performance['orders'] }}</span>
                                        <i class="fas fa-circle px-1 hidden lg:inline" style="font-size: 4px;"></i>
                                        <span>Tiempo pausado: {{ $performance['paused'] }} min.</span>
                                    </p>
                                </x-slot>
                            </x-avatar-with-title-subtitle>
                        </div>
                    @empty
                        <div class="my-3 lg:col-span-full text-center">
                            Los resultados se muestran cada viernes
                        </div>
                    @endforelse
                </div>
            </x-dashboard-panel-2>

            <!-- employees Birthdays -->
            <x-dashboard-panel-2 class="lg:col-span-2" title="Próximos cumpleaños"
                icon="<i class='fas fa-birthday-cake'></i>">
                @if ($soon_birthdays)
                    <p class="text-center my-2 text-sm text-green-500 font-bold">
                        ¡Muchas felicidades te desea la familia Emblemas 3d!
                    </p>
                @endif
                <div class="lg:grid lg:grid-cols-2 lg:gap-2 mb-1 text-sm text-gray-500 p-1">
                    @forelse($soon_birthdays as $birthday)
                        @php
                            $birthday['employee'] = App\Models\Employee::find($birthday['employee']['id']);
                        @endphp
                        <x-avatar-with-title-subtitle :user="$birthday['employee']->user">
                            <x-slot name="title">
                                {{ $birthday['employee']->user->name }}
                            </x-slot>
                            <x-slot name="subtitle">
                                @if ($birthday['days'])
                                    <span>
                                        cumple el
                                        {{ $birthday['employee']->birth_date->isoFormat('DD MMMM') }} (En
                                        {{ $birthday['days'] }} días)
                                    </span>
                                @else
                                    <span>
                                        cumple el
                                        {{ $birthday['employee']->birth_date->isoFormat('DD MMMM') }}
                                        (Hoy)
                                    </span>
                                @endif
                            </x-slot>
                        </x-avatar-with-title-subtitle>
                    @empty
                        <div class="my-3 lg:col-span-full text-center">
                            No hay cumpleaños próximos
                        </div>
                    @endforelse
                </div>
            </x-dashboard-panel-2>

            <!-- new employees -->
            <x-dashboard-panel-2 class="lg:col-span-2" title="Agregados recientemente"
                icon="<i class='fas fa-user-plus'></i>">
                @if ($new_employees)
                    <p class="text-center my-2 text-sm text-green-500 font-bold">
                        Ciertamente viviremos grandes momentos y todos juntos lograremos mucho éxito.
                        Tus cualidades y habilidades agregarán muchos puntos positivos al grupo y sabemos que
                        aprenderemos mucho de ti. Bienvenido(a) a la familia Emblemas 3d
                    </p>
                @endif
                <div class="lg:grid lg:grid-cols-2 lg:gap-2 mb-1 text-sm text-gray-500 p-1">
                    @forelse($new_employees as $employee)
                        @php
                            $employee = App\Models\Employee::find($employee['id']);
                        @endphp
                        <x-avatar-with-title-subtitle :user="$employee->user">
                            <x-slot name="title">
                                {{ $employee->user->name }}
                            </x-slot>
                            <x-slot name="subtitle">
                                Se unió el {{ $employee->join_date->isoFormat('DD MMMM') }}
                            </x-slot>
                        </x-avatar-with-title-subtitle>
                    @empty
                        <div class="my-3 lg:col-span-full text-center">
                            No hay colaboradores agregados recientemente
                        </div>
                    @endforelse
                </div>
            </x-dashboard-panel-2>

            <!-- Anniversaries -->
            <x-dashboard-panel-2 class="lg:col-span-2" title="Aniversarios"
                icon="<i class='fas fa-glass-cheers'></i>">
                @if ($anniversaries)
                    <p class="text-center my-2 text-sm text-green-500 font-bold">
                        Te tenemos un gran agradecimiento por continuar a nuestro lado, por tu esfuerzo día a día y
                        desarrollarte
                        tan disciplinada y profesionalmente.
                    </p>
                @endif
                <div class="lg:grid lg:grid-cols-2 lg:gap-2 mb-1 text-sm text-gray-500 p-1">
                    @forelse($anniversaries as $employee)
                        @php
                            $employee = App\Models\Employee::find($employee['id']);
                        @endphp
                        <x-avatar-with-title-subtitle :user="$employee->user">
                            <x-slot name="title">
                                {{ $employee->user->name }}
                            </x-slot>
                            <x-slot name="subtitle">
                                Se unió el {{ $employee->join_date->isoFormat('DD MMMM, YYYY') }}
                                ({{ $employee->join_date->diffInYears() }} años)
                            </x-slot>
                        </x-avatar-with-title-subtitle>
                    @empty
                        <div class="my-3 lg:col-span-full text-center">
                            No hay aniversarios hoy
                        </div>
                    @endforelse
                </div>
            </x-dashboard-panel-2>

        </div>

        <h2 class="text-2xl text-gray-400 mb-3 mt-8 ml-6">Clientes</h2>

        <div class="lg:grid lg:grid-cols-4 lg:gap-3 mb-2">

            <!-- customer contacts Birthdays -->
            <x-dashboard-panel-2 class="lg:col-span-2" title="Próximos cumpleaños"
                icon="<i class='fas fa-birthday-cake'></i>">
                @if ($soon_customers_birthdays)
                    <p class="text-center my-2 text-sm text-green-500 font-bold">
                        ¡Enviar felicitaciones de parte de Emblemas 3d!
                    </p>
                @endif
                <div class="lg:grid lg:grid-cols-2 lg:gap-2 mb-1 text-sm text-gray-500 p-1">
                    @forelse($soon_customers_birthdays as $birthday)
                        <x-avatar-with-title-subtitle>
                            <x-slot name="title">
                                {{ $birthday['contact']->name }} <br>
                                <i class="text-gray-500 fas fa-envelope mr-1"
                                    style="font-size: 9px;"></i>{{ $birthday['contact']->email }} <br>
                                <i class="text-gray-500 fas fa-building mr-1"
                                    style="font-size: 9px;"></i>{{ $birthday['customer']->name }}
                            </x-slot>
                            <x-slot name="subtitle">
                                @if ($birthday['days'])
                                    <span class="text-xs ml-1">cumple el
                                        {{ $birthday['contact']->birth_date->isoFormat('DD MMMM') }} (En
                                        {{ $birthday['days'] }} días)</span>
                                @else
                                    <span class="text-xs ml-1">cumple el
                                        {{ $birthday['contact']->birth_date->isoFormat('DD MMMM') }} (Hoy)</span>
                                @endif
                            </x-slot>
                        </x-avatar-with-title-subtitle>
                    @empty
                        <div class="my-3 lg:col-span-full text-center">
                            No hay cumpleaños próximos
                        </div>
                    @endforelse
                </div>
            </x-dashboard-panel-2>

        </div>

        @can('ver_historial_movimientos')
            <h2 class="text-2xl text-gray-400 mb-3 mt-8 ml-6">Historial de movimientos</h2>

            <div class="lg:grid lg:grid-cols-3 lg:gap-3 mb-2">
                <!-- Created -->
                <x-dashboard-panel-2 title="Creaciones" icon="<i class='fas fa-plus-square'></i>" :modalParam="1">
                    <ol class="ml-2 relative border-l border-gray-200 text-xs mt-3">
                        @forelse ($created_histories as $c_h)
                            <x-time-line :event="$c_h" />
                        @empty
                            <p class="text-xs text-center text-gray-600">No hay registros para mostrar</p>
                        @endforelse
                    </ol>
                </x-dashboard-panel-2>

                <!-- Edited -->
                <x-dashboard-panel-2 title="Ediciones" icon="<i class='fas fa-edit'></i>" :modalParam="2">
                    <ol class="ml-2 relative border-l border-gray-200 text-xs mt-3">
                        @forelse ($edited_histories as $e_h)
                            <x-time-line :event="$e_h" />
                        @empty
                            <p class="text-xs text-center text-gray-600">No hay registros para mostrar</p>
                        @endforelse
                    </ol>
                </x-dashboard-panel-2>

                <!-- Deleted -->
                <x-dashboard-panel-2 title="Eliminaciones" icon="<i class='fas fa-trash'></i>" :modalParam="3">
                    <ol class="ml-2 relative border-l border-gray-200 text-xs mt-3">
                        @forelse ($deleted_histories as $d_h)
                            <x-time-line :event="$d_h" />
                        @empty
                            <p class="text-xs text-center text-gray-600">No hay registros para mostrar</p>
                        @endforelse
                    </ol>
                </x-dashboard-panel-2>
            </div>
        @endcan

        {{-- others modals --}}
        @livewire('common.detail-modal')

    </div>
