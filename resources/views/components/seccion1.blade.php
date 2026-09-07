    <div x-show="step === 1" x-cloak x-transition:enter="transition ease-out duration-300" class="space-y-8 pt-10">
        <div class="max-w-3xl mx-auto space-y-12">
            <h2 class="text-[30px] font-black text-slate-800 tracking-tight" style="mar">I.Clasificación y descripcion
                de la especie</h2>
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-6">
                    <label class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">1. Nombres
                        comunes:</label>
                    <div class="flex space-x-2">
                        <button type="button" @click="abrirModalNombre()"
                            class="flex items-center px-3 py-1.5 bg-indigo-600 text-white text-[16px] font-black rounded-xl hover:bg-indigo-700 transition-all shadow-sm">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 4v16m8-8H4" stroke-width="3" />
                            </svg>Ingresar
                        </button>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 p-4 border-2 border-dashed border-slate-100 rounded-2xl mb-5">
                    <template x-for="(n, index) in form.nombres_comunes" :key="index">
                        <div @click="n.editable ? editarNombre(index) : null"
                            :class="n.editable ? 'bg-amber-50 boArder-amber-200 cursor-pointer hover:bg-amber-100' :
                                'bg-indigo-50 border-indigo-200 cursor-default'"
                            class="inline-flex items-center w-fit px-2.5 py-1 border-2 rounded-full transition-all group shadow-sm">
                            <span class="text-[13px] font-bold text-slate-700 whitespace-nowrap"
                                x-text="n.nombre"></span>
                            <span :class="n.editable ? 'bg-amber-500' : 'bg-indigo-500'"
                                class="ml-2 px-1.5 py-0.5 text-[14px] font-black text-white rounded-md  leading-none"
                                x-text="n.lengua || 'Sin lengua'"></span>
                            <template x-if="n.editable">
                                <div class="flex items-center ml-2 border-l border-amber-200 pl-1 gap-1">
                                    <button type="button" @click.stop="editarNombre(index)"
                                        class="p-0.5 hover:bg-indigo-600 hover:text-white rounded-full text-amber-600 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>

                                    <button type="button" @click.stop="eliminarNombre(index)"
                                        class="p-0.5 hover:bg-rose-500 hover:text-white rounded-full text-amber-600 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
                <h3 class="text-xs font-black text-slate-400  tracking-[0.2em] mb-6 flex items-center">
                    <label class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">2.
                        Taxonomía:</label>
                    <div class="h-px bg-slate-100 flex-grow"></div>
                </h3>

                <input type="hidden" x-model="form.IdCAT">

                <div class="space-y-4">
                    <template
                        x-for="f in [{l:'a) Reino', k:'Reino'}, {l:'b) Phylum', k:'Divisionphylum'}, {l:'c) Clase', k:'Clase'}, {l:'d) Orden', k:'Orden'}, {l:'e) Familia', k:'Familia'}]">
                        <div class="grid grid-cols-3 items-center gap-4">
                            <label class="text-[16px] font-bold text-slate-800" x-text="f.l"></label>
                            <input type="text" x-model="form[f.k]"
                                class="col-span-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-semibold text-slate-700 outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/5 transition-all"
                                disabled>
                        </div>
                    </template>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center"
                    style="margin-bottom: 13px">
                    <span class="mr-4">f) Nombre Científico</span>
                    <div class="h-px bg-slate-100 flex-grow"></div>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" style="margin-bottom: 20px">
                    <div class="flex flex-col">
                        <label class="text-[16px] font-bold text-slate-800">I. Género:</label>
                        <input type="text" x-model="form.Genero"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-bold text-indigo-900 shadow-inner"
                            disabled>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-[16px] font-bold text-slate-800">II. Epíteto
                            específico:</label>
                        <input type="text" x-model="form.Especie_epiteto"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-bold text-indigo-900 shadow-inner"
                            disabled>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-[16px] font-bold text-slate-800">III. Epíteto
                            infraespecífico:</label>
                        <input type="text" x-model="form.Nombreinfra"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-bold text-indigo-900 shadow-inner"
                            disabled>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-[16px] font-bold text-slate-800">IV. Categoría
                            infraespecífica:</label>
                        <input type="text" x-model="form.Categinfra"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-bold text-indigo-900 shadow-inner"
                            disabled>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-[16px] font-bold text-slate-800">V. Estatus:</label>
                        <input type="text" x-model="form.EstatusTaxon"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-bold text-indigo-900 shadow-inner"
                            disabled>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-[16px] font-bold text-slate-800">VI. Autor y año:</label>
                        <input type="text" x-model="form.AutorTaxon"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-500"
                            disabled>
                    </div>

                </div>

                <div
                    class="absolute right-0 -top-4 z-50 w-80 pointer-events-none opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300 ease-out">
                    <div
                        class="bg-slate-900/95 backdrop-blur-sm text-white p-4 rounded-2xl shadow-2xl border border-white/10 shadow-indigo-500/10">
                        <div
                            class="absolute -bottom-1.5 right-12 w-3 h-3 bg-slate-900 rotate-45 border-r border-b border-white/10">
                        </div>
                    </div>
                </div>
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center"
                    style="margin-bottom: 10px">Información adicional</label>
                <textarea id="infoAddNombreCientifico" placeholder="Información adicional sobre nombre científico"
                    class="w-full rounded-2xl border border-slate-200 p-4 text-sm"></textarea>

                <div class="mt-8 pt-6 border-t border-slate-100">
                    <div class="flex items-center justify-between mb-4">
                        <label class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">3.
                            Sinónimos:</label>
                        <button type="button" @click="abrirModalSinonimo()"
                            class="flex items-center px-3 py-1.5 bg-indigo-600 text-white text-[13px] font-black rounded-xl hover:bg-indigo-700 transition-all shadow-sm">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round" />
                            </svg>Ingresar
                        </button>



                    </div>
                    <div class="min-h-[50px] p-4 rounded-2xl bg-slate-50 border border-slate-200 flex flex-wrap gap-2">
                        <template x-for="(s, index) in form.sinonimos" :key="index">
                            <div @click="s.editable ? editarSinonimo(index) : null"
                                :class="s.editable ? 'bg-amber-50 border-amber-200 cursor-pointer hover:bg-amber-100' :
                                    'bg-indigo-50 border-indigo-200 cursor-default'"
                                class="flex flex-col p-3 border-2 rounded-2xl transition-all group">
                                <div class="flex items-center justify-between">
                                    <span class="text-[16px] font-bold italic text-slate-700"
                                        x-text="s.sinonimo"></span>
                                    <template x-if="s.editable">
                                        <svg class="w-3 h-3 text-amber-500" ...></svg>
                                    </template>
                                    <template x-if="s.editable">
                                        <div class="flex items-center ml-2 border-l border-amber-200 pl-1 gap-0.5">
                                            <button type="button" @click.stop="editarSinonimo(index)"
                                                class="p-0.5 hover:bg-indigo-600 hover:text-white rounded-full text-amber-600 transition-all"
                                                title="Editar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" stroke-width="2.5">
                                                    <path
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>

                                            <button type="button" @click.stop="eliminarSinonimo(index)"
                                                class="p-0.5 hover:bg-rose-500 hover:text-white rounded-full text-amber-600 transition-all"
                                                title="Eliminar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" stroke-width="3">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </template>

                                </div>
                                <div :class="s.editable ? 'text-slate-500' : 'text-indigo-600'"
                                    class="text-[14px] mt-1">
                                    <span x-text="s.autor"></span> <span x-show="s.anio"
                                        x-text="'(' + s.anio + ')'"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">

                <div class="flex flex-col mb-8 relative group">

                    <div class="flex flex-col mb-8 relative group">
                        <div class="flex items-center gap-2 mb-6">
                            <label class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">4.
                                Resumen de la especie:</label>
                            <div class="cursor-help text-slate-300 hover:text-indigo-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div
                            class="absolute right-0 -top-4 z-50 w-80 pointer-events-none opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300 ease-out">
                            <div
                                class="bg-slate-900/95 backdrop-blur-sm text-white p-4 rounded-2xl shadow-2xl border border-white/10 shadow-indigo-500/10">
                                <p class="text-[14px] font-bold leading-relaxed text-slate-200 tracking-wider">
                                    Descripción coloquial <span class="text-indigo-300">(evitar términos
                                        técnicos)</span> que mencione características distintivas: origen,
                                    morfología, distribución, biología, ecología, importancia y conservación.</p>
                                <div
                                    class="absolute -bottom-1.5 right-12 w-3 h-3 bg-slate-900 rotate-45 border-r border-b border-white/10">
                                </div>
                            </div>
                        </div>
                        <textarea id="resumenEspecie_editor" class="w-full rounded-2xl border border-slate-200 p-4 text-sm"></textarea>
                    </div>
                </div>

                <div class="flex flex-col mb-8">
                    <label class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center"
                        style="margin-bottom: 20px">5. Descripción de la especie:</label>
                    <textarea id="descripcionEspecie_editor" class="w-full rounded-2xl border border-slate-200 p-4 text-sm"></textarea>
                </div>

                <div x-show="form.Reino === 'Animalia'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-y-2">

                    <h3 class="text-xs font-black text-slate-400 tracking-[0.2em] mb-6 flex items-center">
                        <div class="h-px bg-slate-100 flex-grow"></div>
                    </h3>

                    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                        <div
                            class="hidden md:grid grid-cols-10 gap-4 bg-slate-50/50 px-8 py-4 border-b border-slate-100">
                            <div class="col-span-4 text-[11px] font-black text-slate-400  tracking-widest">
                                Atributo</div>
                            <div
                                class="col-span-4 text-[11px] font-black text-slate-400  tracking-widest text-center">
                                De - A</div>
                            <div
                                class="col-span-2 text-[11px] font-black text-slate-400  tracking-widest text-center">
                                Promedio</div>
                            <div
                                class="col-span-2 text-[11px] font-black text-slate-400  tracking-widest text-right">
                                Unidad</div>
                        </div>

                        <div class="divide-y divide-slate-100">
                            <template
                                x-for="m in [
                                {l:'Largo Hembras', i:'largoinicialhembras', f:'largofinalhembras', p:'promedioLargoHembras', u:'unidadLargoHembras', opts:['mm', 'cm', 'm'], icon:'📏'},
                                {l:'Largo Machos', i:'largoinicialmachos', f:'largofinalmachos', p:'promedioLargoMachos', u:'unidadLargoMachos', opts:['mm', 'cm', 'm'], icon:'📏'},
                                {l:'Peso Hembras', i:'pesoinicialhembras', f:'pesofinalhembras', p:'promedioPesoHembras', u:'unidadPesoHembras', opts:['g', 'kg', 't'], icon:'⚖️'},
                                {l:'Peso Machos', i:'pesoinicialmachos', f:'pesofinalmachos', p:'promedioPesoMachos', u:'unidadPesoMachos', opts:['g', 'kg', 't'], icon:'⚖️'}
                            ]">
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center px-8 py-5 hover:bg-slate-50/30 transition-colors">
                                    <div class="col-span-4 flex items-center gap-3">
                                        <span
                                            class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-sm"
                                            x-text="m.icon"></span>
                                        <span class="text-[15px] font-bold text-slate-700" x-text="m.l"></span>
                                    </div>
                                    <div class="col-span-4 flex items-center justify-center gap-2">
                                        <input type="number"
                                            placeholder="Mín"
                                            x-model="form[m.i]"
                                            min="0.01"
                                            @input="actualizarPromedio(m.i, m.f, m.p)"
                                            @change="validarRango(m.i, m.f, m.l)"
                                            class="w-full max-w-[100px] px-3 py-2 rounded-xl border border-slate-200 text-center font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all">

                                        <span class="text-slate-300">—</span>

                                        <input type="number"
                                            placeholder="Máx"
                                            x-model="form[m.f]"
                                            min="0.01"
                                            @input="actualizarPromedio(m.i, m.f, m.p)"
                                            @change="validarRango(m.i, m.f, m.l)"
                                            class="w-full max-w-[100px] px-3 py-2 rounded-xl border border-slate-200 text-center font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all">
                                                                            </div>
                                    <div class="col-span-2 flex justify-center">
                                        <input type="number"
                                        step="0.01"
                                        x-model="form[m.p]"
                                        placeholder="---"
                                        class="w-full max-w-[90px] px-3 py-2 rounded-xl bg-indigo-600 border-none text-center font-black text-white shadow-sm shadow-indigo-700 focus:ring-2 focus:ring-indigo-400 outline-none transition-all opacity-90">
                                    </div>
                                    <div class="col-span-2 flex justify-end">
                                        <select x-model="form[m.u]"
                                            class="px-3 py-2 rounded-xl bg-slate-100 border-none text-[12px] font-black text-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition-all cursor-pointer">
                                            <template x-for="opt in m.opts" :key="opt">
                                                <option :value="opt" x-text="opt"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-6 mb-8 pb-6  " style="margin-top: 20px">
                    <label
                        class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">e)Toxicidad:</label>
                    <div class="flex bg-slate-100 p-1 rounded-xl border border-slate-200">
                        <label class="flex items-center px-6 py-2 rounded-lg cursor-pointer transition-all"
                            :class="form.siNoToxicidad == '1' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400'">
                            <input type="radio" x-model="form.siNoToxicidad" value="1" class="hidden">
                            <span class="text-[10px] font-black">SÍ</span>
                        </label>
                        <label class="flex items-center px-6 py-2 rounded-lg cursor-pointer transition-all"
                            :class="form.siNoToxicidad == '0' ? 'bg-white text-rose-500 shadow-sm' : 'text-slate-400'">
                            <input type="radio" x-model="form.siNoToxicidad" value="0" class="hidden">
                            <span class="text-[10px] font-black">NO</span>
                        </label>
                    </div>
                </div>
                <div style="margin-top: -30px" x-show="form.siNoToxicidad == '1'" x-cloak x-transition>
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center"
                        style="margin-bottom: 10px">
                        Información adicional
                    </label>
                    <textarea id="toxicidad_editor" placeholder="Información adicional sobre toxicidad"></textarea>
                </div>
            </div>

            <div class="space-y-8">
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                    <div class="flex flex-col">
                        <label class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center"
                            style="margin-bottom: 20px">6. Especies similares:</label>
                        <textarea id="especiesSimilares_editor"></textarea>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl text-white relative overflow-hidden">
                    <label class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">7. Categoría
                        de riesgo en la NOM-059-SEMARNAT: </label>
                    <template x-if="form.Nom">
                        <div class="flex flex-col items-end max-w-[70%]">
                            <div class="px-3 py-1.5 rounded-xl bg-emerald-500 text-[13px] font-black text-white  shadow-lg text-right leading-tight whitespace-normal"
                                x-text="form.Nom">
                            </div>
                        </div>
                    </template>
                    <div class="relative space-y-4" x-data="{ openYear: null }">
                        <template
                            x-for="(data, index) in [{id:1, v:'2001', k:'2001', icon:'A'}, {id:2, v:'2010', k:'2010', icon:'B'}, {id:3, v:'2010 - Act. 2019', k:'2019', icon:'C'}]">
                            <div class="border border-black/10 rounded-2xl bg-black/5 backdrop-blur-md overflow-hidden transition-all duration-300"
                                :class="openYear === data.id ? 'bg-black/10 ring-1 ring-black/20' : ''">
                                <button type="button" @click="openYear = (openYear === data.id ? null : data.id)"
                                    class="w-full flex items-center justify-between p-4 focus:outline-none">
                                    <div class="flex items-center space-x-4">
                                        <span
                                            class="w-8 h-8 rounded-xl bg-white text-slate-800 flex items-center justify-center font-black text-xs shadow-lg"
                                            x-text="data.icon"></span>
                                        <span class="text-[16px] font-black text-slate-700" x-text="data.v"></span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span x-show="form.nom059[data.k].categoria"
                                            class="px-2 py-1 rounded bg-emerald-500 text-[13px] font-black"
                                            x-text="form.nom059[data.k].categoria"></span>
                                        <svg class="w-4 h-4 text-white/50 transition-transform duration-300"
                                            :class="openYear === data.id ? 'rotate-180 text-white' : ''"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 9l-7 7-7-7" stroke-width="3" />
                                        </svg>
                                    </div>
                                </button>
                                <div x-show="openYear === data.id" x-collapse
                                    class="p-5 pt-2 border-t border-slate-200 bg-slate-100/50 space-y-5">

                                    <div>
                                        <label
                                           class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">
                                            Categoría:
                                        </label>
                                        <select x-model="form.nom059[data.k].categoria"
                                            class="w-full px-4 py-3 rounded-xl bg-white border border-slate-200 text-slate-700 text-sm font-medium outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/5 transition-all shadow-sm">
                                            <option value="" class="text-slate-400">Seleccionar categoría...
                                            </option>
                                            <option value="Probablemente extinta en el medio silvestre (E)"
                                                class="text-slate-800">Probablemente extinta en el medio silvestre (E)
                                            </option>
                                            <option value="En peligro de extinción (P)" class="text-slate-800">En
                                                peligro de extinción (P)</option>
                                            <option value="Amenazadas (A)" class="text-slate-800">Amenazada (A)
                                            </option>
                                            <option value="Sujetas a protección especial (Pr)" class="text-slate-800">
                                                Sujeta a protección especial (Pr)</option>
                                            <option value="No evaluada (NE)" class="text-slate-800">
                                                No evaluada (NE)</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label
                                            class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">
                                            Información Adicional:
                                        </label>
                                        <textarea :id="'nom059_info_' + data.k"
                                            class="w-full px-4 py-3 rounded-xl bg-white border border-slate-200 text-slate-700 text-sm font-medium min-h-[100px] outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/5 transition-all shadow-sm"
                                            placeholder="Escribe detalles adicionales aquí..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-8">
                    <div class="flex flex-col">
                        <label class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center"
                            style="margin-bottom: 20px">8. Categoría de riesgo en la UICN: </label>
                        <div class="flex space-x-3 mb-4">
                            <input x-model="form.riesgoUICN" type="text"
                                class="flex-1 px-4 py-3 rounded-xl border border-slate-100 bg-slate-50 text-xs font-bold text-slate-500 shadow-inner"
                                disabled>
                        </div>
                        <label class="text-[15px] font-bold text-slate-700 tracking-tight flex items-center"
                            style="margin-bottom: 10px">Información adicional</label>
                        <textarea id="infoUICN_editor" placeholder="Información adicional sobre categoría de riesgo según la UICN"></textarea>
                    </div>
                    <div class="h-px bg-slate-100"></div>
                    <div class="flex flex-col">
                        <label class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center"
                            style="margin-bottom: 20px">9. Regulación del comercio internacional de la especie (CITES):
                        </label>
                        <div class="flex space-x-3 mb-4">
                            <input x-model="form.cites" type="text"
                                class="flex-1 px-4 py-3 rounded-xl border border-slate-100 bg-slate-50 text-xs font-bold text-slate-500 shadow-inner"
                                disabled>
                        </div>
                        <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center"
                            style="margin-bottom: 10px">Información adicional</label>
                        <textarea id="infoCITES_editor"
                            placeholder="Información adicional regulación del comercio internacional de la especie (CITES)"></textarea>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                    <label class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center" style="margin-bottom: 20px">
                        10. Origen en relación con México:
                    </label>

                    @include('components.multi-select-2', [
                        'model' => 'form.origen',
                        'options' => "['Exótica/No nativa', 'Nativa', 'Criptogénica']",
                        'placeholder' => 'Selecciona el origen...'
                    ])

                    <div class="mt-6">
                        <label class="text-[15px] font-bold text-slate-700 tracking-tight flex items-center" style="margin-bottom: 10px">
                            Información adicional
                        </label>
                        <textarea id="descripcionOrigen_editor"></textarea>
                    </div>
                </div>
            </div>
        </div>



        <div class="mt-16 flex justify-end pb-24">

        </div>
    </div>
    <x-modal />
