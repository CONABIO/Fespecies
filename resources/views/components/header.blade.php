<header class="bg-[#003D4A] w-full py-2 shadow-md relative z-[1100]" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 flex justify-center items-center text-white">
        <img src="{{ asset('images/logo_conabio_transparente.png') }}" style="width: 300px">
    </div>

    <div class="absolute right-6 top-1/2 -translate-y-1/2">
        <div class="relative">
            <button @click="open = !open" class="flex items-center space-x-2 text-white focus:outline-none opacity-90 hover:opacity-100 transition">
                <span class="text-[13px] font-bold tracking-widest">{{ Auth::user()?->name }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </button>

            <div x-show="open"
                 @click.away="open = false"
                 x-cloak
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-[2000] border border-gray-200">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-[18px]  font-bold tracking-widest text-gray-700 hover:bg-gray-100 transition">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </div>
</header>
