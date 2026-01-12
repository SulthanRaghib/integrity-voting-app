<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Secure Voting'))</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;

            [x-cloak] {
                display: none !important;
            }
        }
    </style>
    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col">

    <!-- Navigation -->
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-200">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('welcome') }}" class="flex items-center gap-2">
                    <div class="bg-indigo-600 text-white p-1.5 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="font-bold text-xl tracking-tight text-slate-800">SecureVote</span>
                </a>

                <div class="flex items-center gap-4">
                    @yield('navbar-actions')
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow @yield('main-class')">
        @yield('content')
    </main>

    <!-- Footer -->
    @section('footer')
        <footer class="bg-white border-t border-slate-200 mt-auto py-8">
            <div class="container mx-auto px-4 text-center">
                <p class="text-slate-500 text-sm">Sistem Voting Berintegritas &copy; {{ date('Y') }}</p>
                <p class="text-gray-400 text-xs mt-1">Hak cipta dilindungi.</p>
            </div>
        </footer>
    @show

    @stack('scripts')
</body>

</html>
