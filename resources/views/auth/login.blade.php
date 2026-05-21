<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fespecies</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-white">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div
            class="bg-white rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] flex overflow-hidden max-w-4xl w-full border border-gray-100">
            <div class="hidden md:flex md:w-5/12 relative items-center justify-center overflow-hidden">
                <img src="{{ asset('images/fondo.png') }}">
            </div>

            <div class="w-full md:w-7/12 p-8 md:p-12 flex flex-col justify-center bg-white">
                <div class="flex flex-col items-center mb-10">
                    <div class="w-80">
                        <img src="{{ asset('images/lolo_fespecies.png') }}" alt="Logo CONABIO"
                            class="w-full h-auto object-contain">
                    </div>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-6 relative">
                        <input type="username" name="username"
                            class="w-full pl-2 py-2 border-0 border-b-2 border-gray-200 focus:border-[#641E16] focus:ring-0 outline-none text-gray-700 placeholder-gray-400 bg-transparent transition"
                            placeholder="Correo" value="{{ old('username') }}">
                        @error('username')
                            <p class="text-red-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6 relative">
                        <input type="password" name="password"
                            class="w-full pl-2 py-2 border-0 border-b-2 border-gray-200 focus:border-[#641E16] focus:ring-0 outline-none text-gray-700 placeholder-gray-400 bg-transparent transition"
                            placeholder="Contrasena">
                        @error('password')
                            <p class="text-red-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between mb-10">
                        <a href="#" class="text-xs text-[#1A570E] hover:underline font-medium">Olvidaste tu contraseña?</a>
                        <button type="submit"
                            class="bg-[#1A570E] hover:bg-[#137000] text-white px-10 py-2 rounded-full font-bold text-sm shadow-lg transition-all hover:scale-105 uppercase">
                            Ingresar
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</body>

</html>
