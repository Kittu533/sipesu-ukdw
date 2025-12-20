<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SiSUA UKDW</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .ukdw-green { background-color: #00796B; }
        .ukdw-text-green { color: #00796B; }
        .ukdw-bg-light { background-color: #E0F2F1; }
        .ukdw-border-green { border-color: #00796B; }
        .ukdw-green-hover:hover { background-color: #004d40; }
        
        .building-bg {
            background-image: url('/gedung-ukdw.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    <div class="min-h-screen flex">
        
        <!-- Left Side - Login Form -->
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                
                <!-- Logo and Header -->
                <div class="text-center mb-8">
                    <img class="mx-auto h-16 w-auto mb-6" src="https://ukdw.ac.id/wp-content/uploads/2023/07/LOGO-UKDW-WARNA-TEXT-W-PNG.png" alt="Logo UKDW">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">
                        Selamat Datang
                    </h2>
                    <p class="text-lg text-gray-600 mb-1">
                        Sistem Surat Akademik UKDW
                    </p>
                    <p class="text-sm text-gray-500">
                        Masuk untuk mengakses layanan administrasi
                    </p>
                </div>

                @if (session('status'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg" role="alert">
                        <span class="block sm:inline">{{ session('status') }}</span>
                    </div>
                @endif
                
                <!-- Login Form -->
                <form class="space-y-6" action="{{ route('login') }}" method="POST">
                    @csrf 
                    
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            Username / NIM / NIP
                        </label>
                        <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus
                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-200 @error('username') border-red-300 @enderror"
                            placeholder="Masukkan username, NIM, atau NIP"
                        >
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-200 @error('password') border-red-300 @enderror"
                            placeholder="Masukkan password"
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember_me" type="checkbox"
                                class="h-4 w-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500"
                            >
                            <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                                Ingat saya
                            </label>
                        </div>

                        <div class="text-sm">
                            <a href="#" class="font-medium text-teal-600 hover:text-teal-500 transition duration-200">
                                Lupa password?
                            </a>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition duration-200 transform hover:scale-[1.02]"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Masuk ke Sistem
                        </button>
                    </div>
                </form>
                
                <!-- Back to Home -->
                <div class="mt-8 text-center">
                    <a href="/" class="inline-flex items-center text-sm text-gray-500 hover:text-teal-600 transition duration-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Right Side - UKDW Building Image -->
        <div class="hidden lg:block relative w-0 flex-1">
            <div class="building-bg absolute inset-0"></div>
        </div>
    </div>
</body>
</html>