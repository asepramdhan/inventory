<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ? config('app.name') . ' - ' . $title : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        // Load dark mode before page renders to prevent flicker
        const loadDarkMode = () => {
            const theme = localStorage.getItem('theme') ?? 'system'
            
            if (
                theme === 'dark' ||
                (theme === 'system' &&
                    window.matchMedia('(prefers-color-scheme: dark)')
                    .matches)
            ) {
                document.documentElement.classList.add('dark')
            }
        }
                
        // Initialize on page load
        loadDarkMode();
        
        // Reinitialize after Livewire navigation (for spa mode)
        document.addEventListener('livewire:navigated', function() {
            loadDarkMode();
        });
    </script>

    @livewireStyles
</head>

<body class="bg-gray-100 dark:bg-black text-gray-800 dark:text-white">
    <x-ui.layout>
        <!-- Sidebar -->
        <livewire:sidebar />
        <x-ui.layout.main>
            <!-- Header -->
            <x-header />
            <!-- Your page content -->
            <div class="m-6">
                {{ $slot }}
            </div>
        </x-ui.layout.main>

        @livewireScripts
    </x-ui.layout>

    <script>
        loadDarkMode()
    </script>

    <x-ui.toast position="top-right" />
</body>

</html>