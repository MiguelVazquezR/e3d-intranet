<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Emblems 3D | Intranet</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- F awesome -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    @livewireStyles

    @stack('css')

    <!-- Scripts -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="font-sans antialiased" x-data="{ darkMode: false }" x-init="if (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    localStorage.setItem('darkMode', JSON.stringify(true));
}
darkMode = JSON.parse(localStorage.getItem('darkMode'));
$watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))" x-cloak>
    <div x-bind:class="{ 'dark': darkMode === true }" class="min-h-screen bg-gray-100">
        <x-jet-banner />

        <div class="min-h-screen bg-gray-100 dark:bg-slate-900">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-slate-800 dark:text-gray-400 shadow dark:shadow-gray-400">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="dark:bg-slate-900">
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts

        <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
        @stack('js')

        <!-- Main js Scripts -->
        <script src="{{ mix('js/app.js') }}"></script>

        @auth
            <script>
                Echo.private('App.Models.User.' + {{ Auth::user()->id }})
                    .notification((notification) => {
                        Livewire.emit('messages-counter-refresh');
                    });
            </script>
        @endauth

</body>

</html>
