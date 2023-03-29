<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @if (session()->has('error'))
        <div id="notification" class="fixed top-0 right-0 mt-5 mr-5">
            <div class="bg-red-500 text-white font-bold rounded-t px-4 py-2">
                Error
            </div>
            <div class="border border-t-0 border-red-400 rounded-b bg-green-100 px-4 py-3 text-red-700">
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif
    @if (session()->has('success'))
        <div id="notification" class="fixed top-0 right-0 mt-5 mr-5">
            <div class="bg-green-500 text-white font-bold rounded-t px-4 py-2">

            </div>
            <div class="border border-t-0 border-green-400 rounded-b bg-green-100 px-4 py-3 text-green-700">
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif
    @if (session()->has('message'))
        <div id="notification" class="fixed top-0 right-0 mt-5 mr-5">
            <div class="bg-blue-500 text-white font-bold rounded-t px-4 py-2">
                Info
            </div>
            <div class="border border-t-0 border-blue-400 rounded-b bg-green-100 px-4 py-3 text-blue-700">
                <p>{{ session('message') }}</p>
            </div>
        </div>
    @endif
    <script>
        setTimeout(function() {
            var element = document?.getElementById("notification");
            if (element) {
            var opacity = 1;
            var intervalID = setInterval(function() {
                opacity -= 0.1;
                element.style.opacity = opacity;
                if (opacity <= 0) {
                    clearInterval(intervalID);
                    element.parentNode.removeChild(element);
                }
            }, 100);
        }
        }, 3000);
    </script>
    @yield('script')
</body>

</html>
