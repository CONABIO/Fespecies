@props(['id', 'title', 'saveFunction'])
<div x-show="{{ $id }}" x-cloak class="fixed inset-0 z-[150] overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="{{ $id }}" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="{{ $id }} = false" class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50"></div>
        <div x-show="{{ $id }}"  x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block px-6 py-6 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="mb-4">
                <h3 class="text-sm font-black text-indigo-900 tracking-widest uppercase">{{ $title }}</h3>
            </div>
            <div class="space-y-4">{{ $slot }}</div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" @click="{{ $id }} = false" class="px-4 py-2 text-[10px] font-black text-red-400 uppercase hover:text-gray-600 transition-colors">Cancelar</button>
                <button type="button" @click="{{ $saveFunction }}" class="px-6 py-2 bg-indigo-600 text-white text-[10px] font-black rounded-full hover:bg-indigo-800 shadow-md shadow-indigo-100 transition-all">Guardar Datos</button>
            </div>
        </div>
    </div>
</div>
