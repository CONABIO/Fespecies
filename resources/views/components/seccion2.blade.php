{{-- SECCIÓN 2: DISTRIBUCIÓN --}}
<div x-show="step === 2"
     x-cloak
     x-transition:enter="transition ease-out duration-500"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     class="w-full space-y-12">

    <!-- TÍTULO DE SECCIÓN -->
    <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight mb-10 border-l-8 border-indigo-600 pl-6">
        II. DISTRIBUCIÓN DE LA ESPECIE
    </h2>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

        <!-- COLUMNA IZQUIERDA -->
        <div class="space-y-10">
            <!-- 11. DISTRIBUCIÓN MUNDIAL -->
            <div class="bg-white p-10 rounded-[2.5rem] shadow-xl shadow-slate-100 border border-slate-100">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.25em] mb-8 flex items-center">
                    <span class="mr-4">11. Distribución mundial</span>
                    <div class="h-px bg-slate-100 flex-grow"></div>
                </h3>

                <div class="space-y-8">
                    <div class="flex flex-col" x-data="{ open: false, filter: '', options: ['México', 'Estados Unidos', 'Canadá', 'Guatemala', 'Belice', 'Brasil', 'Argentina'] }">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest mb-4 block ml-1">a) Selecciona país(es):</label>
                        <div class="relative" @click.away="open = false">
                            <div @click="open = !open" class="min-h-[60px] p-4 rounded-2xl border-2 border-slate-100 bg-slate-50/50 flex flex-wrap gap-2 cursor-pointer hover:border-indigo-300 transition-all shadow-inner">
                                <template x-if="form.paises_seleccionados.length === 0">
                                    <span class="text-xs text-slate-400 p-2 font-bold uppercase tracking-widest">Seleccionar países...</span>
                                </template>
                                <template x-for="sel in form.paises_seleccionados" :key="sel">
                                    <div class="bg-indigo-600 text-white text-[10px] font-black px-4 py-2 rounded-xl flex items-center shadow-lg">
                                        <span x-text="sel"></span>
                                        <button type="button" @click.stop="form.paises_seleccionados = form.paises_seleccionados.filter(i => i !== sel)" class="ml-2 hover:text-rose-300">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            <div x-show="open" class="absolute z-[110] w-full mt-3 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden" x-cloak>
                                <div class="p-3 border-b border-slate-100 bg-slate-50">
                                    <input type="text" x-model="filter" placeholder="Buscar país..." class="w-full px-4 py-2.5 text-xs border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-400 font-bold">
                                </div>
                                <div class="max-h-60 overflow-y-auto">
                                    <template x-for="option in options.filter(i => i.toLowerCase().includes(filter.toLowerCase()))" :key="option">
                                        <div @click="if(!form.paises_seleccionados.includes(option)) form.paises_seleccionados.push(option); open = false" class="px-6 py-4 text-xs font-bold text-slate-600 hover:bg-indigo-50 cursor-pointer">
                                            <span x-text="option"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-[11px] font-black text-slate-500 uppercase mb-4 ml-1">Información adicional sobre la distribución mundial:</label>
                        <div class="rounded-2xl overflow-hidden border-2 border-slate-100">
                            <textarea id="distMundialInfo_editor"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 12. DISTRIBUCIÓN HISTÓRICA -->
            <div x-show="form.paises_seleccionados.includes('México')" x-transition class="bg-white p-10 rounded-[2.5rem] shadow-xl border-l-[12px] border-l-indigo-600 border border-slate-100">
                <h3 class="text-xs font-black text-indigo-600 uppercase tracking-[0.25em] mb-8">12. Distribución histórica en México</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <select x-model="form.dist_historica_estado" class="w-full px-5 py-3.5 rounded-2xl border-2 border-slate-100 bg-slate-50 text-sm font-bold shadow-inner outline-none focus:border-indigo-400 appearance-none">
                        <option value="">Seleccionar estado...</option>
                    </select>
                    <select x-model="form.dist_historica_municipio" class="w-full px-5 py-3.5 rounded-2xl border-2 border-slate-100 bg-slate-50 text-sm font-bold shadow-inner outline-none focus:border-indigo-400 appearance-none">
                        <option value="">Seleccionar municipio...</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA -->
        <div class="space-y-10">
            <!-- 13. DISTRIBUCIÓN POTENCIAL -->
            <div class="bg-white p-10 rounded-[2.5rem] shadow-xl border border-slate-100">
                <div class="flex items-center justify-between gap-6 mb-8 pb-8 border-b border-slate-100">
                    <label class="text-[11px] font-black text-slate-600 uppercase tracking-widest">13. Distribución potencial en México:</label>
                    <div class="flex bg-slate-100 p-1.5 rounded-2xl border border-slate-200">
                        <label class="px-8 py-3 rounded-xl cursor-pointer transition-all" :class="form.siNoPotencial == '1' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400'">
                            <input type="radio" x-model="form.siNoPotencial" value="1" class="hidden">
                            <span class="text-[11px] font-black">SÍ</span>
                        </label>
                        <label class="px-8 py-3 rounded-xl cursor-pointer transition-all" :class="form.siNoPotencial == '0' ? 'bg-white text-rose-500 shadow-md' : 'text-slate-400'">
                            <input type="radio" x-model="form.siNoPotencial" value="0" class="hidden">
                            <span class="text-[11px] font-black">NO</span>
                        </label>
                    </div>
                </div>
                <div x-show="form.siNoPotencial == '1'" x-transition>
                    <textarea id="potencialInfo_editor"></textarea>
                </div>
            </div>

            <!-- 14. ENDEMISMO -->
            <div class="bg-white p-10 rounded-[2.5rem] shadow-xl border border-slate-100 space-y-8">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.25em]">14. Endemismo</h3>
                <div class="flex items-center justify-between pb-8 border-b border-slate-50">
                    <label class="text-[11px] font-black text-slate-600 uppercase">a) ¿Endémica en México?</label>
                    <div class="flex bg-slate-100 p-1.5 rounded-2xl border border-slate-200">
                        <label class="px-8 py-3 rounded-xl cursor-pointer transition-all" :class="form.siNoEndemismo == '1' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400'">
                            <input type="radio" x-model="form.siNoEndemismo" value="1" class="hidden">
                            <span class="text-[11px] font-black">SÍ</span>
                        </label>
                        <label class="px-8 py-3 rounded-xl cursor-pointer transition-all" :class="form.siNoEndemismo == '0' ? 'bg-white text-rose-500 shadow-md' : 'text-slate-400'">
                            <input type="radio" x-model="form.siNoEndemismo" value="0" class="hidden">
                            <span class="text-[11px] font-black">NO</span>
                        </label>
                    </div>
                </div>
                <div class="flex flex-col space-y-3">
                    <label class="text-[11px] font-black text-slate-500 uppercase ml-1">b) Endémica a:</label>
                    <input type="text" x-model="form.endemica_a" class="px-6 py-4 rounded-2xl border-2 border-slate-100 bg-slate-50 text-sm font-bold text-indigo-900 shadow-inner focus:border-indigo-400 outline-none" placeholder="Ej. Sierra Madre Oriental">
                </div>
                <div class="flex flex-col space-y-3">
                    <label class="text-[11px] font-black text-slate-500 uppercase ml-1">Información adicional sobre endemismo:</label>
                    <div class="rounded-2xl overflow-hidden border-2 border-slate-100">
                        <textarea id="endemismoInfo_editor"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BOTONES -->
    <div class="mt-20 flex justify-between items-center pb-10">
        <button type="button" @click="step = 1" class="px-12 py-5 bg-slate-100 text-slate-500 rounded-full font-black text-[10px] uppercase tracking-[0.4em] shadow-lg hover:bg-slate-200 transition-all">
            Anterior
        </button>

        <button type="button" @click="avanzarSeccion()" class="px-16 py-5 bg-indigo-600 text-white rounded-full font-black text-[10px] uppercase tracking-[0.4em] shadow-2xl shadow-indigo-200 hover:bg-indigo-700 transition-all">
            Siguiente
        </button>
    </div>
</div>
