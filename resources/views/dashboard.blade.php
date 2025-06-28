<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>University SMS Dashboard</title>
    @vite('resources/css/app.css')

    <!-- Google Fonts -->
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

    <!-- Header -->
    <header class="bg-gray-800 px-6 py-4 shadow-md border-b border-indigo-600 flex justify-between items-center">
        <h1 class="text-2xl sm:text-3xl text-cyan-400 font-playfair">University SMS</h1>
        <span class="text-sm sm:text-base text-gray-300">Logged in as: {{ Auth::user()->name }}</span>
    </header>

    <!-- Main Content -->
    <main class="flex-1 px-4 py-10 sm:px-10 bg-gray-800">
        <section class="bg-gray-700 border border-indigo-600 rounded-3xl shadow-2xl p-8 sm:p-12 max-w-6xl mx-auto text-center">

            <!-- Welcome Heading -->
            <h2 class="text-4xl sm:text-5xl font-playfair font-extrabold text-cyan-400 mb-6">
                🎓 Welcome, {{ Auth::user()->name }}!
            </h2>

            <!-- Subtext -->
            <p class="text-lg sm:text-xl text-gray-300 mb-10 max-w-3xl mx-auto">
                Efficiently manage student records, academic programs, and more inside the University Student Management System. 🚀
            </p>

            <!-- Buttons -->
            <div class="flex flex-wrap justify-center gap-6">
                <a href="{{ route('students.index') }}"
                   class="bg-cyan-600 hover:bg-cyan-500 text-white font-semibold px-6 sm:px-8 py-3 rounded-xl shadow-lg transition transform hover:scale-105">
                    📚 Manage Students
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="bg-gray-600 hover:bg-gray-500 text-white font-semibold px-6 sm:px-8 py-3 rounded-xl shadow-lg transition transform hover:scale-105">
                    ⚙️ Edit Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 sm:px-8 py-3 rounded-xl shadow-lg transition transform hover:scale-105">
                        🔒 Logout
                    </button>
                </form>
            </div>

        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-center py-6 text-sm text-gray-500">
        &copy; {{ date('Y') }} University Student Management System
    </footer>

</body>
</html>
