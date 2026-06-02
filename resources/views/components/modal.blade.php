<!-- Aqui esta el contenido de los modales de nombres comunes y de sinonimos, no estan juntos son cosas separadas pero aqui van a ir el contendido de modales -->

<div x-show="showModalNombre" x-cloak class="fixed inset-0 z-[100] ...">
<x-modalGeneral id="showModalNombre" title="Agregar Nombre Común" saveFunction="guardarNombreComun()">
        <div>
            <label class="text-[10px] font-bold text-gray-500 uppercase">Nombre Común*</label>
            <input type="text" x-model="tempNombre.nombre" class="w-full px-4 py-2 mt-1 rounded-full border-2 border-gray-100 bg-gray-50 text-xs focus:border-indigo-300 outline-none transition-all">
        </div>
        <div>
            <label class="text-[10px] font-bold text-gray-500 uppercase">Lengua</label>
            <x-select-lenguas model="tempNombre.lengua" />
        </div>
        <div>
            <label class="text-[10px] font-bold text-gray-500 uppercase">Bibliografía</label>
            <textarea id="bibliografia_editor" class="w-full px-4 py-3 mt-1 rounded-2xl border-2 border-gray-100 bg-gray-50 text-xs focus:border-indigo-300 outline-none h-32 resize-none"></textarea>
        </div>
    </x-modalGeneral>
</div>

<div x-show="showModalSinonimo" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50">
    <x-modalGeneral id="showModalSinonimo" title="Agregar Sinónimo" saveFunction="guardarSinonimoManual()">
    <div>
        <label class="text-[10px] font-bold text-gray-500 uppercase">Sinónimo</label>
        <input type="text" x-model="tempSinonimo.sinonimo" class="w-full px-4 py-2 mt-1 rounded-full border-2 border-gray-100 bg-gray-50 text-xs italic focus:border-indigo-300 outline-none transition-all">
    </div>
    <div>
        <label class="text-[10px] font-bold text-gray-500 uppercase">Autor</label>
        <input type="text" x-model="tempSinonimo.autor" class="w-full px-4 py-2 mt-1 rounded-full border-2 border-gray-100 bg-gray-50 text-xs italic focus:border-indigo-300 outline-none transition-all">
    </div>
    <div>
        <label class="text-[10px] font-bold text-gray-500 uppercase">Año</label>
        <input type="number" min="0" x-model="tempSinonimo.anio" class="w-full px-4 py-2 mt-1 rounded-full border-2 border-gray-100 bg-gray-50 text-xs italic focus:border-indigo-300 outline-none transition-all">
    </div>
</x-modalGeneral>
</div>
</main>
