<div class="p-8 bg-slate-50 min-h-screen font-sans">
    <div class="max-w-4xl mx-auto mb-12 relative">
        <div class="flex items-center justify-between relative z-10">
            <template x-for="i in [1,2,3,4]">
                <div class="relative flex flex-col items-center">
                    <div :class="step >= i ? 'bg-indigo-600 border-indigo-600 text-white shadow-lg scale-110' : 'bg-white border-slate-300 text-slate-400'"
                         class="w-10 h-10 rounded-full border-2 flex items-center justify-center transition-all duration-500 font-bold text-sm" x-text="i"></div>
                </div>
            </template>
        </div>
        <div class="absolute top-5 left-0 w-full h-0.5 bg-slate-200"></div>
        <div class="absolute top-5 left-0 h-0.5 bg-indigo-600 transition-all duration-700" :style="`width: ${((step-1)/3)*100}%`"></div>
    </div>
    <div x-show="step === 1" x-cloak x-transition:enter="transition ease-out duration-300" class="max-w-6xl mx-auto space-y-8">
        <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight">I. CLASIFICACIÓN Y DESCRIPCIÓN DE LA ESPECIE</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <div class="space-y-8">
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-6">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Nombres comunes:</label>
                        <div class="flex space-x-2">
                            <button type="button" @click="abrirModalNombre()" class="flex items-center px-3 py-1.5 bg-indigo-600 text-white text-[10px] font-black rounded-xl hover:bg-indigo-700 transition-all shadow-sm">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="3" /></svg>INGRESAR
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 p-4 border-2 border-dashed border-slate-100 rounded-2xl mb-5">
                        <template x-for="(n, index) in form.nombres_comunes" :key="index">
                            <div @click="n.editable ? editarNombre(index) : null" :class="n.editable ? 'bg-amber-50 border-amber-200 cursor-pointer hover:bg-amber-100' : 'bg-indigo-50 border-indigo-200 cursor-default'" class="inline-flex items-center w-fit px-2.5 py-1 border-2 rounded-full transition-all group shadow-sm">
                                <span class="text-[11px] font-bold text-slate-700 whitespace-nowrap" x-text="n.nombre"></span>
                                <span :class="n.editable ? 'bg-amber-500' : 'bg-indigo-500'" class="ml-2 px-1.5 py-0.5 text-[9px] font-black text-white rounded-md uppercase leading-none" x-text="n.lengua || 'SIN LENGUA'"></span>
                                <template x-if="n.editable">
                                    <div class="flex items-center ml-2 border-l border-amber-200 pl-1 gap-1">
                                        <button type="button" @click.stop="editarNombre(index)" class="p-0.5 hover:bg-indigo-600 hover:text-white rounded-full text-amber-600 transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>

                                        <button type="button" @click.stop="eliminarNombre(index)" class="p-0.5 hover:bg-rose-500 hover:text-white rounded-full text-amber-600 transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center">
                        <span class="mr-4">Taxonomía</span>
                        <div class="h-px bg-slate-100 flex-grow"></div>
                    </h3>

                    <input type="hidden" x-model="form.IdCAT">

                    <div class="space-y-4">
                        <template x-for="f in [{l:'a) Reino*', k:'Reino'}, {l:'b) Phylum*', k:'Divisionphylum'}, {l:'c) Clase*', k:'Clase'}, {l:'d) Orden*', k:'Orden'}, {l:'e) Familia*', k:'Familia'}]">
                            <div class="grid grid-cols-3 items-center gap-4">
                                <label class="text-[10px] font-bold text-slate-500 uppercase" x-text="f.l"></label>
                                <input type="text" x-model="form[f.k]" class="col-span-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-semibold text-slate-700 outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/5 transition-all" disabled>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center">
                        <span class="mr-4">f) Nombre Científico</span>
                        <div class="h-px bg-slate-100 flex-grow"></div>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col">
                            <label class="text-[9px] font-black text-slate-400 uppercase mb-2 ml-1">I. Género:</label>
                            <input type="text" x-model="form.Genero" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-bold text-indigo-900 shadow-inner" disabled>
                        </div>
                        <div class="flex flex-col">
                            <label class="text-[9px] font-black text-slate-400 uppercase mb-2 ml-1">II. Epíteto específico:</label>
                            <input type="text" x-model="form.Especie_epiteto" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-bold text-indigo-900 shadow-inner" disabled>
                        </div>
                        <div class="flex flex-col">
                            <label class="text-[9px] font-black text-slate-400 uppercase mb-2 ml-1">III. Epíteto infraespecífico:</label>
                            <input type="text" x-model="form.Nombreinfra" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-bold text-indigo-900 shadow-inner" disabled>
                        </div>
                        <div class="flex flex-col">
                            <label class="text-[9px] font-black text-slate-400 uppercase mb-2 ml-1">IV. Categoría infraespecífica*:</label>
                            <input type="text" x-model="form.Categinfra" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-bold text-indigo-900 shadow-inner" disabled>
                        </div>
                        <div class="flex flex-col">
                            <label class="text-[9px] font-black text-slate-400 uppercase mb-2 ml-1">V. Estatus:</label>
                            <input type="text" x-model="form.EstatusTaxon" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-bold text-indigo-900 shadow-inner" disabled>
                        </div>
                        <div class="flex flex-col">
                            <label class="text-[9px] font-black text-slate-400 uppercase mb-2 ml-1">VI. Autor y año*:</label>
                            <input type="text" x-model="form.AutorTaxon" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-500" disabled>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-100">
                        <div class="flex items-center justify-between mb-4">
                            <label class="text-[10px] font-black text-slate-500 uppercase">Sinónimos</label>
                            <button type="button" @click="abrirModalSinonimo()" class="flex items-center px-3 py-1.5 bg-indigo-600 text-white text-[9px] font-black rounded-xl hover:bg-indigo-700 transition-all">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round"/></svg>INGRESAR
                            </button>
                        </div>
                        <div class="min-h-[50px] p-4 rounded-2xl bg-slate-50 border border-slate-200 flex flex-wrap gap-2">
                           <template x-for="(s, index) in form.sinonimos" :key="index">
                                <div @click="s.editable ? editarSinonimo(index) : null" :class="s.editable ? 'bg-amber-50 border-amber-200 cursor-pointer hover:bg-amber-100' : 'bg-indigo-50 border-indigo-200 cursor-default'" class="flex flex-col p-3 border-2 rounded-2xl transition-all group">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold italic text-slate-700" x-text="s.sinonimo"></span>
                                        <template x-if="s.editable">
                                            <svg class="w-3 h-3 text-amber-500" ...></svg>
                                        </template>
                                        <template x-if="s.editable">
                                            <div class="flex items-center ml-2 border-l border-amber-200 pl-1 gap-0.5">
                                                <button type="button" @click.stop="editarSinonimo(index)" class="p-0.5 hover:bg-indigo-600 hover:text-white rounded-full text-amber-600 transition-all" title="Editar">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                        <path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </button>

                                                <button type="button" @click.stop="eliminarSinonimo(index)" class="p-0.5 hover:bg-rose-500 hover:text-white rounded-full text-amber-600 transition-all" title="Eliminar">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>

                                    </div>
                                    <div :class="s.editable ? 'text-slate-500' : 'text-indigo-600'" class="text-[9px] mt-1">
                                        <span x-text="s.autor"></span> <span x-show="s.anio" x-text="'(' + s.anio + ')'"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                    <div class="flex flex-col mb-8">
                        <label class="text-[10px] font-black text-slate-500 uppercase mb-3">Resumen de la especie:</label>
                        <textarea id="resumenEspecie_editor" class="w-full rounded-2xl border border-slate-200 p-4 text-sm"></textarea>
                    </div>

                    <div class="flex flex-col mb-8">
                        <label class="text-[10px] font-black text-slate-500 uppercase mb-3">Descripción de la especie:</label>
                        <textarea id="descripcionEspecie_editor" class="w-full rounded-2xl border border-slate-200 p-4 text-sm"></textarea>
                    </div>

                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center">
                        <div class="h-px bg-slate-100 flex-grow"></div>
                    </h3>

                    <div class="space-y-4">
                        <template x-for="m in [{l:'a) Largo total para hembras:', i:'largoinicialhembras', f:'largofinalhembras', p:'promedioLargoHembras', u:'unidadLargoHembras', opts:['mm', 'cm', 'm']},{l:'b) Largo total para machos:', i:'largoinicialmachos', f:'largofinalmachos', p:'promedioLargoMachos', u:'unidadLargoMachos', opts:['mm', 'cm', 'm']}, {l:'c) Peso para hembras:', i:'pesoinicialhembras', f:'pesofinalhembras', p:'promedioPesoHembras', u:'unidadPesoHembras', opts:['g', 'kg', 't']},{l:'d) Peso para machos:', i:'pesoinicialmachos', f:'pesofinalmachos', p:'promedioPesoMachos', u:'unidadPesoMachos', opts:['g', 'kg', 't']}]">
                            <div class="flex flex-col md:flex-row md:items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-[10px] font-black text-slate-500 uppercase mb-2 md:mb-0" x-text="m.l"></span>
                                <div class="flex items-center space-x-2 text-[10px] font-bold text-slate-400">de
                                    <input type="number" min="0" x-model="form[m.i]" class="w-16 px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-center">a
                                    <input type="number" min="0" x-model="form[m.f]" @change="validarRango(m.i, m.f, m.l)" class="w-16 px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-center">prom.
                                    <input type="number" min="0" :value="form[m.p] = calcularPromedio(form[m.i], form[m.f])" class="w-16 px-1 py-1.5 rounded-lg bg-indigo-50 border-none text-[11px] font-black text-indigo-600 text-center">
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                    <div class="flex items-center space-x-6 mb-8 pb-6 border-b border-slate-100">
                        <label class="text-[11px] font-black text-slate-500  uppercase tracking-widest">e) Toxicidad:</label>
                        <div class="flex bg-slate-100 p-1 rounded-xl border border-slate-200">
                            <label class="flex items-center px-6 py-2 rounded-lg cursor-pointer transition-all" :class="form.siNoToxicidad == '1' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400'">
                                <input type="radio" x-model="form.siNoToxicidad" value="1" class="hidden">
                                <span class="text-[10px] font-black">SÍ</span>
                            </label>
                            <label class="flex items-center px-6 py-2 rounded-lg cursor-pointer transition-all" :class="form.siNoToxicidad == '0' ? 'bg-white text-rose-500 shadow-sm' : 'text-slate-400'">
                                <input type="radio" x-model="form.siNoToxicidad" value="0" class="hidden">
                                <span class="text-[10px] font-black">NO</span>
                            </label>
                        </div>
                    </div>
                    <div x-show="form.siNoToxicidad === '1'" class="mb-6 animate-pulse-slow">
                        <label class="text-[10px] font-black text-slate-500 uppercase mb-2 block">Información adicional sobre toxicidad:</label>
                        <textarea id="toxicidad_editor"></textarea>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-[10px] font-black text-slate-500 uppercase mb-3">Especies similares:</label>
                        <textarea id="especiesSimilares_editor"></textarea>
                    </div>
                </div>

                <div class="bg-indigo-900 p-8 rounded-[2.5rem] shadow-xl text-white relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-800 rounded-full opacity-30 blur-3xl"></div>
                    <label class="relative text-[11px] font-black uppercase tracking-[0.2em] text-indigo-300 mb-8 block">Categoría NOM-059-SEMARNAT</label>
                   <template x-if="form.Nom">
            <div class="flex flex-col items-end max-w-[70%]">
                <div class="px-3 py-1.5 rounded-xl bg-emerald-500 text-[10px] font-black text-white uppercase shadow-lg text-right leading-tight whitespace-normal"
                     x-text="form.Nom">
                </div>
            </div>
        </template>
                    <div class="relative space-y-4" x-data="{ openYear: null }">
                        <template x-for="(data, index) in [{id:1, v:'2001', k:'2001', icon:'A'}, {id:2, v:'2010', k:'2010', icon:'B'}, {id:3, v:'2010 (Act. 2019)', k:'2019', icon:'C'}]">
                            <div class="border border-white/10 rounded-2xl bg-white/5 backdrop-blur-md overflow-hidden transition-all duration-300" :class="openYear === data.id ? 'bg-white/10 ring-1 ring-white/20' : ''">
                                <button type="button" @click="openYear = (openYear === data.id ? null : data.id)" class="w-full flex items-center justify-between p-4 focus:outline-none">
                                    <div class="flex items-center space-x-4">
                                        <span class="w-8 h-8 rounded-xl bg-white text-indigo-900 flex items-center justify-center font-black text-xs shadow-lg" x-text="data.icon"></span>
                                        <span class="text-xs font-bold" x-text="'Versión ' + data.v"></span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span x-show="form.nom059[data.k].categoria" class="px-2 py-1 rounded bg-emerald-500 text-[9px] font-black uppercase" x-text="form.nom059[data.k].categoria"></span>
                                        <svg class="w-4 h-4 text-white/50 transition-transform duration-300" :class="openYear === data.id ? 'rotate-180 text-white' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3"/></svg>
                                    </div>
                                </button>
                                <div x-show="openYear === data.id" x-collapse class="p-4 pt-0 border-t border-white/5 space-y-4">
                                    <div>
                                        <label class="text-[9px] font-black text-indigo-200 uppercase mb-2 block">Categoría:</label>
                                        <select x-model="form.nom059[data.k].categoria" class="w-full px-4 py-2.5 rounded-xl bg-white/10 border border-white/20 text-white text-xs outline-none">
                                            <option value="" class="text-slate-800">Seleccionar...</option>
                                            <option value="Probablemente extinta en el medio silvestre (E)" class="text-slate-800">Probablemente extinta (E)</option>
                                            <option value="En peligro de extinción (P)" class="text-slate-800">En peligro de extinción (P)</option>
                                            <option value="Amenazadas (A)" class="text-slate-800">Amenazada (A)</option>
                                            <option value="Sujetas a protección especial (Pr)" class="text-slate-800">Protección especial (Pr)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[9px] font-black text-indigo-200 uppercase mb-2 block">Info. Adicional:</label>
                                        <textarea x-model="form.nom059[data.k].info" class="w-full px-4 py-2.5 rounded-xl bg-white/10 border border-white/20 text-white text-xs min-h-[60px]" placeholder="Detalles..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-8">
                    <div class="flex flex-col">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4 leading-tight">Categoría de riesgo según la UICN:</label>
                        <div class="flex space-x-3 mb-4">
                        <input x-model="form.riesgoUICN" type="text" class="flex-1 px-4 py-3 rounded-xl border border-slate-100 bg-slate-50 text-xs font-bold text-slate-500 shadow-inner" disabled>
                        </div>
                        <textarea id="infoUICN_editor" placeholder="Información adicional sobre el riesgo UICN..."></textarea>
                    </div>
                    <div class="h-px bg-slate-100"></div>
                    <div class="flex flex-col">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4 leading-tight">Regulación del comercio internacional (CITES):</label>
                        <div class="flex space-x-3 mb-4">
                        <input x-model="form.cites" type="text" class="flex-1 px-4 py-3 rounded-xl border border-slate-100 bg-slate-50 text-xs font-bold text-slate-500 shadow-inner" disabled>
                        </div>
                        <textarea id="infoCITES_editor" placeholder="Información adicional sobre CITES..."></textarea>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100" x-data="{open: false,filter: '', options: ['Exótica/No nativa', 'Nativa', 'Criptogénica'],toggle(option) {if (this.form.origen.includes(option)) {this.form.origen = this.form.origen.filter(i => i !== option);} else {this.form.origen.push(option); } }, get filteredOptions() { return this.options.filter(i => i.toLowerCase().includes(this.filter.toLowerCase())); } }">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4 block">Origen en relación con México:</label>
                    <div class="relative" @click.away="open = false">
                        <div @click="open = !open" class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 cursor-pointer hover:border-indigo-300 transition-all">
                            <template x-if="form.origen.length === 0"><span class="text-xs text-slate-400 p-2">Seleccionar opciones...</span></template>
                            <template x-for="sel in form.origen" :key="sel">
                                <div class="bg-indigo-600 text-white text-[10px] font-black px-4 py-1.5 rounded-xl flex items-center shadow-md">
                                    <span x-text="sel"></span>
                                    <button type="button" @click.stop="toggle(sel)" class="ml-2 hover:text-rose-300 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                        <div x-show="open" x-transition class="absolute z-[110] w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden" x-cloak>
                            <div class="p-2 border-b border-slate-100 bg-slate-50">
                                <input type="text" x-model="filter" placeholder="Buscar..." class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-indigo-400" @click.stop>
                            </div>
                            <div class="max-h-40 overflow-y-auto">
                                <template x-for="option in filteredOptions" :key="option">
                                    <div @click="toggle(option)" class="px-5 py-3 text-xs flex items-center justify-between cursor-pointer hover:bg-indigo-50 transition-colors" :class="form.origen.includes(option) ? 'text-indigo-600 font-black' : 'text-slate-600 font-bold'">
                                        <span x-text="option"></span>
                                        <svg x-show="form.origen.includes(option)" class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <textarea id="descripcionOrigen_editor" placeholder="Descripción adicional del origen..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-16 flex justify-end pb-24">
            <button type="button" @click="avanzarSeccion()" class="group flex items-center px-12 py-5 bg-indigo-600 text-white rounded-full font-black text-xs uppercase tracking-[0.3em] shadow-2xl shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-1 transition-all">
                Siguiente Sección
                <svg class="w-5 h-5 ml-4 group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
            </button>
        </div>
    </div>
    <x-modal />
</div>
