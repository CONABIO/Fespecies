<div x-show="step === 2" x-cloak x-transition:enter="transition ease-out duration-300"
    class="space-y-8 pt-10">
    <div class="max-w-3xl mx-auto space-y-12">
    <h2 class="text-[30px] font-black text-slate-800 tracking-tight">
            II. Distribución de la especie
        </h2>
       <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
            <div class="flex flex-col" x-data="{ openPais: false, filterPais: '' }">
                <label class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center" style="margin-bottom: 10px">1. Distribución mundial:</label>
                <label class="text-[16px] font-bold text-slate-800 mb-2">a) Selecciona país(es):</label>
                <div class="relative mb-6">
                    <div @click="openPais = !openPais; if(openPais) $nextTick(() => $refs.inputBusquedaPais.focus())"
                        class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 cursor-pointer hover:border-indigo-300 transition-all shadow-inner">
                        <template x-if="form.paises_seleccionados.length === 0">
                            <span class="text-xs text-slate-400 p-2 font-bold uppercase tracking-widest">SELECCIONAR PAÍS...</span>
                        </template>
                        <template x-for="sel in form.paises_seleccionados" :key="sel">
                            <div class="bg-indigo-600 text-white text-[10px] font-black px-4 py-1.5 rounded-xl flex items-center shadow-md">
                                <span x-text="sel"></span>
                                <button type="button" @click.stop="form.paises_seleccionados = form.paises_seleccionados.filter(i => i !== sel)" class="ml-2 hover:text-rose-300 transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                    <div x-show="openPais" @click.away="openPais = false" class="absolute z-[110] w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden" x-cloak>
                        <div class="p-2 border-b border-slate-100 bg-slate-50">
                            <input type="text" x-model="filterPais" @click.stop placeholder="Buscar país..." x-ref="inputBusquedaPais" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-indigo-400">
                        </div>
                        <div class="max-h-40 overflow-y-auto">
                            <template x-for="option in paisesOptions.filter(i => i.nombrepais.toLowerCase().includes(filterPais.toLowerCase()))" :key="option.paisId">
                                <div @click="if(!form.paises_seleccionados.includes(option.nombrepais)) { form.paises_seleccionados.push(option.nombrepais); } openPais = false; filterPais = ''" class="px-5 py-3 text-xs font-black text-slate-600 hover:bg-indigo-50 cursor-pointer transition-colors border-b border-slate-50" x-text="option.nombrepais"></div>
                            </template>
                        </div>
                    </div>
                </div>

                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center mb-2">Información adicional</label>
                <textarea id="infoAddDistribucionMundialPais" class="w-full rounded-2xl border border-slate-200 p-4 text-sm"></textarea>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100" x-show="form.paises_seleccionados.includes('México')" x-transition x-data="{ openEdo: false, filterEdo: '', openMun: false, filterMun: '' }">
            <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">2. Distribución histórica en México:
            </h3>
            <div class="space-y-6">
                <div class="flex flex-col">
                    <label class="text-[16px] font-bold text-slate-800 mb-2">a) Estado(s):</label>
                    <div class="relative mb-4">
                        <div @click="openEdo = !openEdo; if(openEdo) $nextTick(() => $refs.inputBusquedaEdo.focus())"
                            class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 cursor-pointer hover:border-indigo-300 transition-all shadow-inner">
                            <template x-if="form.estados_seleccionados.length === 0">
                                <span class="text-xs text-slate-400 p-2 font-bold uppercase tracking-widest">SELECCIONAR ESTADO...</span>
                            </template>
                            <template x-for="sel in form.estados_seleccionados" :key="sel">
                                <div class="bg-emerald-600 text-white text-[10px] font-black px-4 py-1.5 rounded-xl flex items-center shadow-md">
                                    <span x-text="sel"></span>
                                    <button type="button" @click.stop="form.estados_seleccionados = form.estados_seleccionados.filter(i => i !== sel); municipiosOptions = municipiosOptions.filter(m => m.nombreEstado !== sel);" class="ml-2 hover:text-rose-300 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                        <div x-show="openEdo" @click.away="openEdo = false" class="absolute z-[100] w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden" x-cloak>
                            <div class="p-2 border-b border-slate-100 bg-slate-50">
                                <input type="text" x-model="filterEdo" @click.stop x-ref="inputBusquedaEdo" placeholder="Buscar estado..." class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-indigo-400">
                            </div>
                            <div class="max-h-40 overflow-y-auto">
                                <template x-for="edo in estadosOptions.filter(i => i.nombreEstado.toLowerCase().includes(filterEdo.toLowerCase()))" :key="edo.estadoId">
                                    <div @click="if(!form.estados_seleccionados.includes(edo.nombreEstado)) { form.estados_seleccionados.push(edo.nombreEstado); cargarMunicipios(edo.nombreEstado); } openEdo = false; filterEdo = ''" class="px-5 py-3 text-xs font-black text-slate-600 hover:bg-emerald-50 cursor-pointer transition-colors border-b border-slate-50" x-text="edo.nombreEstado"></div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center mb-2">Información adicional</label>
                    <textarea id="infoAddDistribucionMundialEstado" class="w-full rounded-2xl border border-slate-200 p-4 text-sm mb-6"></textarea>
                </div>
                <div class="flex flex-col">
                    <label class="text-[16px] font-bold text-slate-800 mb-2">b) Municipio(s):</label>
                    <div class="relative mb-4">
                        <div @click="if(municipiosOptions.length > 0) { openMun = !openMun; if(openMun) $nextTick(() => $refs.inputBusquedaMun.focus()) }"
                            :class="municipiosOptions.length === 0 ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:border-indigo-300'"
                            class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 transition-all shadow-inner">
                            <template x-if="form.municipios_seleccionados.length === 0">
                                <span class="text-xs text-slate-400 p-2 font-bold uppercase tracking-widest" x-text="municipiosOptions.length === 0 ? 'Primero selecciona un estado...' : 'SELECCIONAR MUNICIPIO...'"></span>
                            </template>
                            <template x-for="sel in form.municipios_seleccionados" :key="sel">
                                <div class="bg-sky-600 text-white text-[10px] font-black px-4 py-1.5 rounded-xl flex items-center shadow-md">
                                    <span x-text="sel"></span>
                                    <button type="button" @click.stop="form.municipios_seleccionados = form.municipios_seleccionados.filter(m => m !== sel)" class="ml-2 hover:text-rose-300 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                        <div x-show="openMun" @click.away="openMun = false" class="absolute z-[90] w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden" x-cloak>
                            <div class="p-2 border-b border-slate-100 bg-slate-50">
                                <input type="text" x-model="filterMun" @click.stop x-ref="inputBusquedaMun" placeholder="Buscar municipio..." class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-indigo-400">
                            </div>
                            <div class="max-h-40 overflow-y-auto">
                                <template x-for="edoNombre in form.estados_seleccionados" :key="edoNombre">
                                    <div>
                                        <div class="bg-slate-100/80 px-4 py-1 text-[9px] font-black text-indigo-600 uppercase border-y border-slate-200 sticky top-0">
                                            <span x-text="edoNombre"></span>
                                        </div>
                                        <template x-for="mun in municipiosOptions.filter(m => m.nombreEstado === edoNombre && m.nombreMunicipio.toLowerCase().includes(filterMun.toLowerCase()))" :key="mun.municipioId">
                                            <div @click="if(!form.municipios_seleccionados.includes(mun.nombreMunicipio)) { form.municipios_seleccionados.push(mun.nombreMunicipio); } openMun = false; filterMun = ''" class="px-5 py-2 text-xs font-bold text-slate-600 hover:bg-indigo-50 cursor-pointer border-b border-slate-50 flex justify-between">
                                                <span x-text="mun.nombreMunicipio"></span>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center mb-2">Información adicional</label>
                    <textarea id="infoAddDistribucionMundialMunicipio" class="w-full rounded-2xl border border-slate-200 p-4 text-sm"></textarea>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-10">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <label class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">3. Distribución potencial en México:</label>
                    <div class="flex bg-slate-100 p-1 rounded-xl border border-slate-200">
                        <label class="flex items-center px-6 py-2 rounded-lg cursor-pointer transition-all" :class="form.siNoPotencial == '1' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400'">
                            <input type="radio" x-model="form.siNoPotencial" value="1" class="hidden">
                            <span class="text-[10px] font-black">SÍ</span>
                        </label>
                        <label class="flex items-center px-6 py-2 rounded-lg cursor-pointer transition-all" :class="form.siNoPotencial == '0' ? 'bg-white text-rose-500 shadow-sm' : 'text-slate-400'">
                            <input type="radio" x-model="form.siNoPotencial" value="0" class="hidden">
                            <span class="text-[10px] font-black">NO</span>
                        </label>
                    </div>
                </div>
                <div x-show="form.siNoPotencial == '1'" x-transition>
                    <textarea id="infoAddDistPotMex" class="w-full rounded-2xl border border-slate-200 p-4 text-sm"></textarea>
                </div>
            </div>

            <div class="h-px bg-slate-100"></div>

            <div>
                <div class="flex items-center justify-between mb-6">
                    <label class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">4. Endemismo:</label>
                    <div class="flex bg-slate-100 p-1 rounded-xl border border-slate-200">
                        <label class="flex items-center px-6 py-2 rounded-lg cursor-pointer transition-all" :class="form.siNoEndemismo == '1' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400'">
                            <input type="radio" x-model="form.siNoEndemismo" value="1" class="hidden">
                            <span class="text-[10px] font-black">SÍ</span>
                        </label>
                        <label class="flex items-center px-6 py-2 rounded-lg cursor-pointer transition-all" :class="form.siNoEndemismo == '0' ? 'bg-white text-rose-500 shadow-sm' : 'text-slate-400'">
                            <input type="radio" x-model="form.siNoEndemismo" value="0" class="hidden">
                            <span class="text-[10px] font-black">NO</span>
                        </label>
                    </div>
                </div>

                <div x-show="form.siNoEndemismo == '1'" x-transition class="space-y-4">
                    <div class="flex flex-col">
                        <label class="text-[16px] font-bold text-slate-800 mb-2">a) Endémica a:</label>
                        <input type="text" x-model="form.endemica_a" class="px-5 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm font-bold text-indigo-900 outline-none focus:border-indigo-400 transition-all mb-4" placeholder="Ej. Sierra Madre Oriental">
                        <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center mb-2">Información adicional</label>
                        <textarea id="infoAddEndemismo" class="w-full rounded-2xl border border-slate-200 p-4 text-sm"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-16 flex justify-between pb-24">
            <button type="button" @click="navegarSeccion(1)"
                class="group flex items-center px-10 py-5 bg-white text-slate-400 rounded-full font-black text-xs uppercase tracking-[0.3em] shadow-lg border border-slate-100 hover:bg-slate-50 transition-all">
                <svg class="w-5 h-5 mr-4 group-hover:-translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                </svg>
                Anterior
            </button>
            <button type="button" @click="avanzarSeccion()"
                class="group flex items-center px-12 py-5 bg-indigo-600 text-white rounded-full font-black text-xs uppercase tracking-[0.3em] shadow-2xl shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-1 transition-all">
                Siguiente Sección
                <svg class="w-5 h-5 ml-4 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </button>
        </div>
    </div>
</div>
