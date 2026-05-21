<div class="p-8">
    <div x-show="step === 2" x-cloak x-transition:enter="transition ease-out duration-300 transform"
        class="max-w-5xl mx-auto">
        <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 mb-8" style="margin-top: 40px">
            <p class="text-xs text-indigo-700 font-bold uppercase tracking-widest">II. Distribución de la especie</p>
        </div>

        <div class="mt-12 flex justify-between">

            <button type="button" @click="retrocederSeccion()"
                class="px-8 py-3 bg-gray-200 text-gray-700 text-xs font-black uppercase tracking-widest rounded-full hover:bg-gray-300 transition-all shadow-md">
                Sección Anterior
            </button>

            <button type="button" @click="avanzarSeccion()"
                class="px-8 py-3 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-full hover:bg-indigo-800 transition-all shadow-lg">
                Siguiente Sección
            </button>
        </div>
    </div>
    <x-modal />
</div>
