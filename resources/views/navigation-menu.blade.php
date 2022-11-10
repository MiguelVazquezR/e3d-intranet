<nav x-data="{ open: false, open_messages: false, open_reminders: false, open_notifications: false }" class="bg-white dark:bg-slate-700 border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-jet-application-mark class="block h-6 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 md:flex">
                    <x-jet-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        Inicio
                    </x-jet-nav-link>
                </div>

                <div
                    class="hidden md:flex sm:items-center sm:-my-px pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-200 focus:outline-none focus:text-gray-700 focus:border-gray-300 dark:focus:text-gray-200 dark:focus:border-gray-200 transition">

                    <!-- Items -->
                    <div class="relative">
                        <x-jet-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <div class="hidden space-x-8 sm:ml-10 md:flex">
                                    <span class="hover:cursor-pointer inline-flex items-center">
                                        Artículos
                                        <svg class="-mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </div>
                            </x-slot>

                            <x-slot name="content">
                                @can('tabla_productos')
                                    <x-jet-dropdown-link href="{{ route('products') }}">
                                        <i class="fab fa-product-hunt"></i>
                                        Productos simples
                                    </x-jet-dropdown-link>
                                    <x-jet-dropdown-link href="{{ route('composit-products') }}">
                                        <i class="fas fa-puzzle-piece"></i>
                                        Productos compuestos
                                    </x-jet-dropdown-link>
                                @endcan
                                @can('tabla_inventarios')
                                    <x-jet-dropdown-link href="{{ route('stocks') }}">
                                        <i class="fas fa-box-open"></i>
                                        Inventario
                                    </x-jet-dropdown-link>
                                @endcan
                            </x-slot>
                        </x-jet-dropdown>
                    </div>

                    <!-- Sells -->
                    <div class="relative">
                        <x-jet-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <div class="hidden space-x-8 sm:ml-10 md:flex">
                                    <span class="hover:cursor-pointer inline-flex items-center">
                                        Ventas
                                        <svg class="-mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </div>
                            </x-slot>

                            <x-slot name="content">
                                @can('tabla_cotizaciones')
                                    <x-jet-dropdown-link href="{{ route('quotes') }}">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                        Cotizaciones
                                    </x-jet-dropdown-link>
                                @endcan
                                @can('tabla_clientes')
                                    <x-jet-dropdown-link href="{{ route('customers') }}">
                                        <i class="fas fa-building"></i>
                                        Clientes
                                    </x-jet-dropdown-link>
                                @endcan
                                @can('tabla_ordenes_venta')
                                    <x-jet-dropdown-link href="{{ route('sell-orders') }}">
                                        <i class="fas fa-hand-holding-usd"></i>
                                        Órdenes de venta
                                    </x-jet-dropdown-link>
                                @endcan
                            </x-slot>
                        </x-jet-dropdown>
                    </div>

                    <!-- Purchases -->
                    <div class="relative">
                        <x-jet-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <div class="hidden space-x-8 sm:ml-10 md:flex">
                                    <span class="hover:cursor-pointer inline-flex items-center">
                                        Compras
                                        <svg class="-mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </div>
                            </x-slot>
                            <x-slot name="content">
                                @can('tabla_proveedores')
                                    <x-jet-dropdown-link href="{{ route('suppliers') }}">
                                        <i class="fas fa-building"></i>
                                        Proveedores
                                    </x-jet-dropdown-link>
                                @endcan
                                @can('tabla_ordenes_compra')
                                    <x-jet-dropdown-link href="{{ route('purchase-orders') }}">
                                        <i class="fas fa-shopping-cart"></i>
                                        Órdenes de compra
                                    </x-jet-dropdown-link>
                                @endcan
                            </x-slot>
                        </x-jet-dropdown>
                    </div>

                    <!-- Operative -->
                    <div class="relative">
                        <x-jet-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <div class="hidden space-x-8 sm:ml-10 md:flex">
                                    <span class="hover:cursor-pointer inline-flex items-center">
                                        Operaciones
                                        <svg class="-mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </div>
                            </x-slot>

                            <x-slot name="content">
                                <div class="block px-4 py-px text-[11px] text-gray-400">
                                    Generar órdenes
                                </div>
                                @can('tabla_ordenes_diseño')
                                    <x-jet-dropdown-link href="{{ route('design-orders') }}">
                                        <i class="fas fa-ruler-combined"></i>
                                        Órdenes de diseño
                                    </x-jet-dropdown-link>
                                @endcan
                                @can('ver_ordenes_mercadotecnia')
                                    <x-jet-dropdown-link href="{{ route('marketing-orders') }}">
                                        <i class="fas fa-thumbtack"></i>
                                        Órdenes de mercadotecnia
                                    </x-jet-dropdown-link>
                                @endcan
                                <div class="border-t border-gray-200"></div>
                                <div class="block px-4 py-px text-[11px] text-gray-400">
                                    Departamentos
                                </div>
                                @can('tabla_departamento_diseño')
                                    <x-jet-dropdown-link href="{{ route('design-department') }}">
                                        <i class="fas fa-drafting-compass"></i>
                                        Departamento de diseño
                                    </x-jet-dropdown-link>
                                @endcan
                                @can('tabla_departamento_producción')
                                    <x-jet-dropdown-link href="{{ route('production-department') }}">
                                        <i class="fas fa-hard-hat"></i>
                                        Departamento de producción
                                    </x-jet-dropdown-link>
                                @endcan
                                @can('tabla_departamento_mercadotecnia')
                                    <x-jet-dropdown-link href="{{ route('marketing-department') }}">
                                        <i class="fas fa-lightbulb"></i>
                                        Departamento de mercadotecnia
                                    </x-jet-dropdown-link>
                                @endcan
                                <div class="border-t border-gray-200"></div>
                                <div class="block px-4 py-px text-[11px] text-gray-400">
                                    Otros
                                </div>
                                @can('tabla_nóminas')
                                    <x-jet-dropdown-link href="{{ route('pay-rolls') }}">
                                        <i class="fas fa-money-check-alt"></i>
                                        Nóminas
                                    </x-jet-dropdown-link>
                                @endcan
                                @can('autorizar_tiempo_adicional')
                                    <x-jet-dropdown-link href="{{ route('additional_time_requests') }}">
                                        <i class="fas fa-user-clock"></i>
                                        Solicitudes de tiempo adicional
                                    </x-jet-dropdown-link>
                                @endcan
                                @can('tabla_reuniones')
                                    <x-jet-dropdown-link href="{{ route('meetings') }}">
                                        <i class="far fa-handshake"></i>
                                        Reuniones
                                    </x-jet-dropdown-link>
                                @endcan
                                <x-jet-dropdown-link href="{{ route('media-library') }}">
                                    <i class="fas fa-photo-video"></i>
                                    Biblioteca de medios
                                </x-jet-dropdown-link>
                            </x-slot>
                        </x-jet-dropdown>
                    </div>

                </div>
            </div>

            <div class="hidden md:flex sm:items-center">
                {{-- theme button --}}
                <x-theme-button />

                <!-- notifications -->
                @livewire('notification.notifications-counter')

                <!-- message notifications -->
                @livewire('message.messages-counter')

                <!-- reminder -->
                @livewire('reminder.drop-down')

                <!-- Settings -->
                <div class="text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 mr-2">
                    <div class="relative">
                        <x-jet-dropdown align="center" width="36">
                            <x-slot name="trigger">
                                <div class="hidden space-x-8 sm:ml-10 md:flex">
                                    <span class="hover:cursor-pointer inline-flex items-center">
                                        <i class="fas fa-cog"></i>
                                    </span>
                                </div>
                            </x-slot>

                            <x-slot name="content">
                                @can('tabla_usuarios')
                                    <x-jet-dropdown-link href="{{ route('users') }}">
                                        <i class="far fa-id-card"></i>
                                        Usuarios
                                    </x-jet-dropdown-link>
                                @endcan
                                @can('tabla_permisos')
                                    <x-jet-dropdown-link href="{{ route('permissions') }}">
                                        <i class="fas fa-ban"></i>
                                        Permisos
                                    </x-jet-dropdown-link>
                                @endcan
                                @can('tabla_roles')
                                    <x-jet-dropdown-link href="{{ route('roles') }}">
                                        <i class="fas fa-user-tag"></i>
                                        Roles
                                    </x-jet-dropdown-link>
                                @endcan
                                @can('tabla_días_festivos')
                                    <x-jet-dropdown-link href="{{ route('holydays') }}">
                                        <i class="far fa-calendar-times"></i>
                                        Días festivos
                                    </x-jet-dropdown-link>
                                @endcan
                                @can('configurar_organización')
                                    <x-jet-dropdown-link href="{{ route('organization') }}">
                                        <i class="fas fa-industry"></i>
                                        Organización
                                    </x-jet-dropdown-link>
                                @endcan
                                @can('tabla_bonos')
                                    <x-jet-dropdown-link href="{{ route('bonuses') }}">
                                        <i class="fas fa-coins"></i>
                                        Bonos
                                    </x-jet-dropdown-link>
                                @endcan
                            </x-slot>
                        </x-jet-dropdown>
                    </div>
                </div>

                <!-- Settings Dropdown -->
                <div class="relative ml-3">
                    <x-jet-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button
                                    class="flex items-center text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="h-8 w-8 rounded-full object-cover"
                                        src="{{ Auth::user()->profile_photo_url }}"
                                        alt="{{ Auth::user()->name }}" />
                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                        {{ Auth::user()->name }}

                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                Configuración de cuenta
                            </div>

                            <x-jet-dropdown-link href="{{ route('profile.show') }}">
                                Perfil
                            </x-jet-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-jet-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-jet-dropdown-link>
                            @endif

                            <div class="border-t border-gray-100"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-jet-dropdown-link href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    Cerrar sesión
                                </x-jet-dropdown-link>
                            </form>
                        </x-slot>
                    </x-jet-dropdown>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                {{-- Notification mobile --}}
                @livewire('notification.notification-counter-mobile')

                {{-- messages mobile --}}
                @livewire('message.message-counter-mobile')

                {{-- reminders mobile --}}
                @livewire('reminder.drop-down-mobile')

                <!-- Hamburger -->
                <div class="-mr-2 flex items-center md:hidden">
                    <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- theme button --}}
    <div class="md:hidden">
        <x-theme-button />
    </div>

    {{-- Notifications view --}}
    <div :class="{ 'block': open_notifications, 'hidden': !open_notifications }" class="hidden md:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @livewire('notification.show-notifications-mobile')
        </div>
    </div>

    {{-- messages view --}}
    <div :class="{ 'block': open_messages, 'hidden': !open_messages }" class="hidden md:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @livewire('message.show-messages-mobile')
        </div>
    </div>

    {{-- reminders view --}}
    <div :class="{ 'block': open_reminders, 'hidden': !open_reminders }" class="hidden md:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @livewire('reminder.show-reminders-mobile')
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden md:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-jet-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                Inicio
            </x-jet-responsive-nav-link>
            @can('tabla_productos')
                <x-jet-responsive-nav-link href="{{ route('products') }}" :active="request()->routeIs('products')">
                    Productos simples
                </x-jet-responsive-nav-link>
                <x-jet-responsive-nav-link href="{{ route('composit-products') }}" :active="request()->routeIs('composit-products')">
                    Productos compuestos
                </x-jet-responsive-nav-link>
            @endcan
            @can('tabla_inventarios')
                <x-jet-responsive-nav-link href="{{ route('stocks') }}" :active="request()->routeIs('stocks')">
                    Inventario
                </x-jet-responsive-nav-link>
            @endcan
            @can('tabla_cotizaciones')
                <x-jet-responsive-nav-link href="{{ route('quotes') }}" :active="request()->routeIs('quotes')">
                    Cotizaciones
                </x-jet-responsive-nav-link>
            @endcan
            @can('tabla_clientes')
                <x-jet-responsive-nav-link href="{{ route('customers') }}" :active="request()->routeIs('customers')">
                    Clientes
                </x-jet-responsive-nav-link>
            @endcan
            @can('tabla_proveedores')
                <x-jet-responsive-nav-link href="{{ route('suppliers') }}" :active="request()->routeIs('suppliers')">
                    Proveedores
                </x-jet-responsive-nav-link>
            @endcan
            @can('tabla_ordenes_venta')
                <x-jet-responsive-nav-link href="{{ route('sell-orders') }}" :active="request()->routeIs('sell-orders')">
                    Órdenes de venta
                </x-jet-responsive-nav-link>
            @endcan
            @can('tabla_ordenes_compra')
                <x-jet-responsive-nav-link href="{{ route('purchase-orders') }}" :active="request()->routeIs('purchase-orders')">
                    Órdenes de compra
                </x-jet-responsive-nav-link>
            @endcan
            @can('tabla_ordenes_diseño')
                <x-jet-responsive-nav-link href="{{ route('design-orders') }}" :active="request()->routeIs('design-orders')">
                    Órdenes de diseño
                </x-jet-responsive-nav-link>
            @endcan
            @can('ver_ordenes_mercadotecnia')
                <x-jet-responsive-nav-link href="{{ route('marketing-orders') }}" :active="request()->routeIs('marketing-orders')">
                    Órdenes de mercadotecnia
                </x-jet-responsive-nav-link>
            @endcan
            @can('tabla_usuarios')
                <x-jet-responsive-nav-link href="{{ route('users') }}" :active="request()->routeIs('users')">
                    Usuarios
                </x-jet-responsive-nav-link>
            @endcan
            @can('tabla_permisos')
                <x-jet-responsive-nav-link href="{{ route('permissions') }}" :active="request()->routeIs('permissions')">
                    Permisos
                </x-jet-responsive-nav-link>
            @endcan
            @can('tabla_roles')
                <x-jet-responsive-nav-link href="{{ route('roles') }}" :active="request()->routeIs('roles')">
                    Roles
                </x-jet-responsive-nav-link>
            @endcan
            @can('tabla_departamento_diseño')
                <x-jet-responsive-nav-link href="{{ route('design-department') }}" :active="request()->routeIs('design-department')">
                    Departamento de diseño
                </x-jet-responsive-nav-link>
            @endcan
            @can('tabla_departamento_producción')
                <x-jet-responsive-nav-link href="{{ route('production-department') }}" :active="request()->routeIs('production-department')">
                    Departamento de producción
                </x-jet-responsive-nav-link>
            @endcan
            @can('tabla_departamento_mercadotecnia')
                <x-jet-responsive-nav-link href="{{ route('marketing-department') }}" :active="request()->routeIs('marketing-department')">
                    Departamento de mercadotecnia
                </x-jet-responsive-nav-link>
            @endcan
            @can('tabla_nóminas')
                <x-jet-responsive-nav-link href="{{ route('pay-rolls') }}" :active="request()->routeIs('pay-rolls')">
                    Nóminas
                </x-jet-responsive-nav-link>
            @endcan
            @can('tabla_días_festivos')
                <x-jet-responsive-nav-link href="{{ route('holydays') }}" :active="request()->routeIs('holydays')">
                    Días festivos
                </x-jet-responsive-nav-link>
            @endcan
            @can('tabla_reuniones')
                <x-jet-responsive-nav-link href="{{ route('meetings') }}" :active="request()->routeIs('meetings')">
                    Reuniones
                </x-jet-responsive-nav-link>
            @endcan
            <x-jet-responsive-nav-link href="{{ route('media-library') }}" :active="request()->routeIs('media-library')">
                Biblioteca de medios
            </x-jet-responsive-nav-link>
            @can('configurar_organización')
                <x-jet-responsive-nav-link href="{{ route('organization') }}" :active="request()->routeIs('organization')">
                    Organización
                </x-jet-responsive-nav-link>
            @endcan
            @can('tabla_bonos')
                <x-jet-responsive-nav-link href="{{ route('bonuses') }}" :active="request()->routeIs('bonuses')">
                    Bonos
                </x-jet-responsive-nav-link>
            @endcan
            @can('autorizar_tiempo_adicional')
                <x-jet-responsive-nav-link href="{{ route('additional_time_requests') }}" :active="request()->routeIs('additional_time_requests')">
                    Solicitudes de tiempo adicional
                </x-jet-responsive-nav-link>
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 mr-3">
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                            alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-jet-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-jet-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-jet-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-jet-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-jet-responsive-nav-link href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-jet-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-jet-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}"
                        :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-jet-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-jet-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-jet-responsive-nav-link>
                    @endcan

                    <div class="border-t border-gray-200"></div>

                    <!-- Team Switcher -->
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Switch Teams') }}
                    </div>

                    @foreach (Auth::user()->allTeams() as $team)
                        <x-jet-switchable-team :team="$team" component="jet-responsive-nav-link" />
                    @endforeach
                @endif
            </div>
        </div>
    </div>


    @livewire('message.create-message')
    @livewire('message.show-message')
    @livewire('reminder.create-reminder')
</nav>
