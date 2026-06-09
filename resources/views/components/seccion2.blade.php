
<div x-show="step === 2" x-cloak x-transition:enter="transition ease-out duration-300" class="max-w-6xl mx-auto space-y-8 py-8">
    <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight px-4">II. DISTRIBUCIÓN DE LA ESPECIE</h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
            <div class="flex flex-col" x-data="{ open: false, filter: '' }">
                <label class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center">11. Distribución mundial:</label>
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4 block">a) Selecciona país(es):</label>

                <div class="relative">
                    <div @click="open = !open"
                        class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 cursor-pointer hover:border-indigo-300 transition-all shadow-inner">

                        <template x-if="form.paises_seleccionados.length === 0">
                            <span class="text-xs text-slate-400 p-2 font-black uppercase tracking-widest">SELECCIONAR...</span>
                        </template>

                        <template x-for="sel in form.paises_seleccionados" :key="sel">
                            <div class="bg-indigo-600 text-white text-[10px] font-black px-4 py-1.5 rounded-xl flex items-center shadow-md">
                                <span x-text="sel"></span>
                                <button type="button"
                                    @click.stop="form.paises_seleccionados = form.paises_seleccionados.filter(i => i !== sel)"
                                    class="ml-2 hover:text-rose-300 transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <div x-show="open" @click.away="open = false"
                        class="absolute z-[110] w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden" x-cloak>
                        <div class="p-2 border-b border-slate-100 bg-slate-50">
                            <input type="text" x-model="filter" @click.stop placeholder="Buscar país..."
                                class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-indigo-400">
                        </div>
                        <div class="max-h-60 overflow-y-auto">
                            <template x-for="option in paisesOptions.filter(i => i.nombrepais.toLowerCase().includes(filter.toLowerCase()))" :key="option.paisId">
                                <div @click="if(!form.paises_seleccionados.includes(option.nombrepais)) { form.paises_seleccionados.push(option.nombrepais); } open = false; filter = ''"
                                    class="px-5 py-3 text-xs font-black text-slate-600 hover:bg-indigo-50 cursor-pointer transition-colors border-b border-slate-50"
                                    x-text="option.nombrepais"></div>
                            </template>
                        </div>
                    </div>
                </div>

                <label class="text-[10px] font-black text-slate-500 uppercase mb-3 ml-1 tracking-widest" style="margin-top: 20px">Información adicional paises:</label>
                <textarea x-model="form.dist_mundial_info" class="w-full rounded-2xl border border-slate-200 p-4 text-sm bg-slate-50 focus:border-indigo-400 outline-none min-h-[100px] text-slate-700 font-bold"></textarea>

                <div x-show="form.paises_seleccionados.includes('México')" x-transition>
                    <label class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center" style="margin-top: 60px">12. Distribución histórica en México:</label>
                    <div class="space-y-6">
                        <div class="flex flex-col">
                            <label class="text-[10px] font-black text-slate-500 uppercase mb-2 ml-1 tracking-widest">a) Estado:</label>
                            <select x-model="form.dist_historica_estado"
                                @change="cargarMunicipios($el.value); form.dist_historica_municipio = ''"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-bold text-slate-700 outline-none focus:border-indigo-400">
                                <option value="">Seleccionar estado...</option>
                                <template x-for="edo in estadosOptions" :key="edo.estadoId">
                                    <option :value="edo.nombreEstado" x-text="edo.nombreEstado"></option>
                                </template>
                            </select>
                            <label class="text-[10px] font-black text-slate-500 uppercase mb-3 ml-1 tracking-widest" style="margin-top: 20px">Info adicional Estado:</label>
                            <textarea x-model="form.info_adicional_estado" class="w-full rounded-2xl border border-slate-200 p-4 text-sm bg-slate-50 focus:border-indigo-400 outline-none min-h-[80px] text-slate-700 font-bold"></textarea>
                        </div>

                        <div class="flex flex-col">
                            <label class="text-[10px] font-black text-slate-500 uppercase mb-2 ml-1 tracking-widest">b) Municipio:</label>
                            <select x-model="form.dist_historica_municipio" :disabled="municipiosOptions.length === 0"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-bold text-slate-700 outline-none focus:border-indigo-400 disabled:opacity-50">
                                <option value="">Seleccionar municipio...</option>
                                <template x-for="mun in municipiosOptions" :key="mun.municipioId">
                                    <option :value="mun.nombreMunicipio" x-text="mun.nombreMunicipio"></option>
                                </template>
                            </select>
                            <label class="text-[10px] font-black text-slate-500 uppercase mb-3 ml-1 tracking-widest" style="margin-top: 20px">Info adicional Municipio:</label>
                            <textarea x-model="form.info_adicional_municipio" class="w-full rounded-2xl border border-slate-200 p-4 text-sm bg-slate-50 focus:border-indigo-400 outline-none min-h-[80px] text-slate-700 font-bold"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-6 pb-6 border-b border-slate-100">
                    <label
                        class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center">13.
                        Distribución potencial en México:</label>
                    <div class="flex bg-slate-100 p-1 rounded-xl border border-slate-200">
                        <label class="flex items-center px-6 py-2 rounded-lg cursor-pointer transition-all"
                            :class="form.siNoPotencial == '1' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400'">
                            <input type="radio" x-model="form.siNoPotencial" value="1" class="hidden">
                            <span class="text-[10px] font-black">SÍ</span>
                        </label>
                        <label class="flex items-center px-6 py-2 rounded-lg cursor-pointer transition-all"
                            :class="form.siNoPotencial == '0' ? 'bg-white text-rose-500 shadow-sm' : 'text-slate-400'">
                            <input type="radio" x-model="form.siNoPotencial" value="0" class="hidden">
                            <span class="text-[10px] font-black">NO</span>
                        </label>
                    </div>
                </div>
                <div x-show="form.siNoPotencial == '1'" x-transition>
                    <textarea x-model="form.potencial_info" placeholder="Info adicional sobre potencial..."
                        class="w-full rounded-2xl border border-slate-200 p-4 text-sm bg-slate-50 focus:border-indigo-400 outline-none min-h-[100px] text-slate-700 font-bold"></textarea>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-8 flex items-center">
                    <span class="mr-4">14. Endemismo</span>
                    <div class="h-px bg-slate-100 flex-grow"></div>
                </h3>
                <div class="space-y-6">
                    <div class="flex items-center justify-between pb-6 border-b border-slate-100">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest leading-tight">a)
                            ¿Endémica en México?</label>
                        <div class="flex bg-slate-100 p-1 rounded-xl border border-slate-200">
                            <label class="flex items-center px-6 py-2 rounded-lg cursor-pointer transition-all"
                                :class="form.siNoEndemismo == '1' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400'">
                                <input type="radio" x-model="form.siNoEndemismo" value="1" class="hidden">
                                <span class="text-[10px] font-black">SÍ</span>
                            </label>
                            <label class="flex items-center px-6 py-2 rounded-lg cursor-pointer transition-all"
                                :class="form.siNoEndemismo == '0' ? 'bg-white text-rose-500 shadow-sm' : 'text-slate-400'">
                                <input type="radio" x-model="form.siNoEndemismo" value="0" class="hidden">
                                <span class="text-[10px] font-black">NO</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-[10px] font-black text-slate-500 uppercase mb-3 ml-1 tracking-widest">b)
                            Endémica a:</label>
                        <input type="text" x-model="form.endemica_a"
                            class="px-5 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm font-black text-indigo-900 shadow-inner outline-none focus:border-indigo-400 transition-all"
                            placeholder="Ej. Sierra Madre Oriental">
                    </div>
                    <div class="flex flex-col">
                        <label
                            class="text-[10px] font-black text-slate-500 uppercase mb-3 ml-1 tracking-widest">Información adicional sobre endemismo:</label>
                        <textarea x-model="form.endemismo_info"
                            class="w-full rounded-2xl border border-slate-200 p-4 text-sm bg-slate-50 focus:border-indigo-400 outline-none min-h-[100px] text-slate-700 font-bold"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-16 flex justify-between items-center pb-24 px-4">
        <button type="button" @click="step = 1"
            class="group flex items-center px-10 py-5 bg-white text-slate-400 rounded-full font-black text-xs uppercase tracking-[0.3em] shadow-lg border border-slate-100 hover:bg-slate-50 transition-all">
            <svg class="w-5 h-5 mr-4 group-hover:-translate-x-2 transition-transform" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                    d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
            </svg>
            Anterior
        </button>
        <button type="button" @click="avanzarSeccion()"
            class="group flex items-center px-12 py-5 bg-indigo-600 text-white rounded-full font-black text-xs uppercase tracking-[0.3em] shadow-2xl shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-1 transition-all">
            Siguiente Sección
            <svg class="w-5 h-5 ml-4 group-hover:translate-x-2 transition-transform" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6">
                </path>
            </svg>
        </button>
    </div>
</div>
