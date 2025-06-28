<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>University SMS</title>
    @vite('resources/css/app.css')

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto&display=swap" rel="stylesheet">
    <style>
        .font-playfair {
            font-family: 'Playfair Display', serif;
        }
        .font-roboto {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-900 text-white font-roboto min-h-screen flex flex-col">

    <!-- Navbar -->
    <header class="w-full bg-gray-800 px-6 py-4 border-b border-indigo-600 shadow-md">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <img src="{{ asset('build/assets/Logo.png') }}" alt="University Logo" class="w-12 h-12 rounded-full border-2 border-yellow-400">
                <span class="text-xl sm:text-2xl font-bold text-white">University SMS</span>
            </div>
            <nav class="flex flex-wrap items-center gap-6 text-white text-sm sm:text-base">
                <a href="#" class="hover:text-cyan-400 transition">Home</a>
                <a href="#" class="hover:text-cyan-400 transition">About</a>
                <a href="#" class="hover:text-cyan-400 transition">Contact</a>
                <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md font-semibold transition">Login</a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="flex-1 flex flex-col items-center justify-center text-center px-6 py-16 bg-gray-900">
        <div class="max-w-3xl">
            <h1 class="text-4xl sm:text-6xl font-playfair font-bold text-white mb-6 tracking-wide">
                STUDENT MANAGEMENT SYSTEM
            </h1>
            <p class="text-lg sm:text-xl text-gray-300 mb-10">
                Efficiently manage student records, academic programs, and more.
            </p>

            <div class="flex flex-wrap justify-center gap-6">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="bg-cyan-600 hover:bg-cyan-500 text-white font-semibold px-6 py-3 rounded-lg transition transform hover:scale-105">
                        🚀 Go to Dashboard
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="border-2 border-white text-white hover:bg-white hover:text-gray-900 font-semibold px-6 py-3 rounded-lg transition transform hover:scale-105">
                            🔒 Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition transform hover:scale-105">
                        🔐 Login
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="border-2 border-white text-white hover:bg-white hover:text-gray-900 font-semibold px-6 py-3 rounded-lg transition transform hover:scale-105">
                            📝 Register
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </main>

    <!-- Footer (optional) -->
    <footer class="text-center py-6 text-sm text-gray-500">
        &copy; {{ date('Y') }} University Student Management System
    </footer>

</body>
</html>
