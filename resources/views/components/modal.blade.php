<div x-show="showModalNombre" x-cloak class="fixed inset-0 z-[2000] flex items-center justify-center bg-black/50">
<x-modalGeneral id="showModalNombre" title="Agregar Nombre Común" saveFunction="guardarNombreComun()">
        <div>
            <label class="text-[16px] font-bold text-gray-500">Nombre Común*</label>
            <input type="text" x-model="tempNombre.nombre" class="w-full px-4 py-2 mt-1 rounded-full border-2 border-gray-100 bg-gray-50 text-xs focus:border-indigo-300 outline-none transition-all">
        </div>
        <div>
            <label class="text-[16px] font-bold text-gray-500">Lengua</label>
            <x-select-lenguas model="tempNombre.lengua" />
            <div x-show="tempNombre.lengua === 'Otro'"
                x-transition
                class="mt-2 p-3 bg-indigo-50 rounded-xl border border-indigo-100">
                <label class="text-[10px] font-black text-indigo-900 tracking-widest" style="margin-bottom: 20px">Especifique la lengua:</label>
                <input type="text" x-model="tempNombre.lengua_otra" class="w-full px-4 py-2 mt-1 rounded-full border-2 border-gray-100 bg-gray-50 text-xs focus:border-indigo-300 outline-none transition-all">
            </div>
        </div>
        <div>
            <label class="text-[16px] font-bold text-gray-500">Bibliografía</label>
            <textarea id="bibliografia_editor" class="w-full px-4 py-3 mt-1 rounded-2xl border-2 border-gray-100 bg-gray-50 text-xs focus:border-indigo-300 outline-none h-32 resize-none"></textarea>
        </div>
    </x-modalGeneral>
</div>

<div x-show="showModalSinonimo" x-cloak class="fixed inset-0 z-[2000] flex items-center justify-center bg-black/50">
    <x-modalGeneral id="showModalSinonimo" title="Agregar Sinónimo" saveFunction="guardarSinonimoManual()">
    <div>
        <label class="text-[16px] font-bold text-gray-500">Sinónimo</label>
        <input type="text" x-model="tempSinonimo.sinonimo" class="w-full px-4 py-2 mt-1 rounded-full border-2 border-gray-100 bg-gray-50 text-xs focus:border-indigo-300 outline-none transition-all">
    </div>
    <div>
        <label class="text-[16px] font-bold text-gray-500">Autor</label>
        <input type="text" x-model="tempSinonimo.autor" class="w-full px-4 py-2 mt-1 rounded-full border-2 border-gray-100 bg-gray-50 text-xs italic focus:border-indigo-300 outline-none transition-all">
    </div>
    <div>
        <label class="text-[16px] font-bold text-gray-500">Año</label>
         <input type="number"
               min="1500"
               max="2026"
               maxlength="4"
               @input="if ($el.value.length > 4) $el.value = $el.value.slice(0, 4); tempSinonimo.anio = $el.value;"
               x-model="tempSinonimo.anio"
               class="w-full px-4 py-2 mt-1 rounded-full border-2 border-gray-100 bg-gray-50 text-xs italic focus:border-indigo-300 outline-none transition-all">
    </div>
</x-modalGeneral>
</div>
</main>
