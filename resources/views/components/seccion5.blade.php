<div x-show="step === 5" x-cloak x-transition:enter="transition ease-out duration-300" class="space-y-8 pt-10">

    <div class="max-w-3xl mx-auto space-y-12">
        <h2 class="text-[30px] font-black text-slate-800 tracking-tight">
            V. Ecología y demografía de la especie
        </h2>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <h3 class="text-[22px] font-bold text-slate-700 tracking-tight mb-2">1. Tamaño poblacional</h3>
            <div class="flex flex-col">
                <label class="text-[16px] font-bold text-slate-800 mb-2">a) Descripción del tamaño poblacional reportado en distintos lugares:</label>
                <textarea id="tamano_poblacional_lugares_editor" x-model="form.tamano_poblacional_lugares" class="w-full rounded-2xl border border-slate-200 p-4 text-sm" rows="4" placeholder="Descripción del tamaño poblacional..."></textarea>
            </div>
            <div class="h-px bg-slate-100"></div>
            <div class="flex flex-col">
                <label class="text-[16px] font-bold text-slate-800 mb-2">b) Abundancia de individuos por región:</label>
                <textarea id="abundancia_region_editor" x-model="form.abundancia_region" class="w-full rounded-2xl border border-slate-200 p-4 text-sm" rows="4" placeholder="Abundancia por región..."></textarea>
            </div>
            <div class="h-px bg-slate-100"></div>
            <div class="flex flex-col">
                <label class="text-[16px] font-bold text-slate-800 mb-2">c) Densidad (número de individuos por área):</label>
                <textarea id="densidad_area_editor" x-model="form.densidad_area" class="w-full rounded-2xl border border-slate-200 p-4 text-sm" rows="4" placeholder="Densidad poblacional..."></textarea>
            </div>
            <div class="h-px bg-slate-100"></div>
            <div class="flex flex-col space-y-4">
                <label class="text-[16px] font-bold text-slate-800">d) Descripción del patrón de ocupación:</label>
                <select x-model="form.patronocupacion" class="px-5 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm font-bold text-indigo-900 outline-none focus:border-indigo-400 transition-all">
                    <option value="">Seleccionar descripción del patrón de ocupación</option>
                    <option value="Agregada">Agregada</option>
                    <option value="Uniforme">Uniforme</option>
                    <option value="Al azar">Al azar</option>
                </select>
                <label class="text-[16px] font-bold text-slate-700 tracking-tight mt-2 mb-2">Información adicional</label>
                <textarea id="info_adicional_patron_editor" x-model="form.info_adicional_patron" class="w-full rounded-2xl border border-slate-200 p-4 text-sm" rows="3" placeholder="Información adicional..."></textarea>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <h3 class="text-[22px] font-bold text-slate-700 tracking-tight mb-2">2. Parámetros poblacionales</h3>
            <div class="flex flex-col">
                <label class="text-[16px] font-bold text-slate-800 mb-2">a) Parámetros poblacionales:</label>
                <textarea id="parametros_poblacionales_editor" x-model="form.parametros_poblacionales" class="w-full rounded-2xl border border-slate-200 p-4 text-sm" rows="4" placeholder="Detalles de parámetros poblacionales..."></textarea>
            </div>
            <div class="h-px bg-slate-100"></div>
            <div class="flex flex-col space-y-3">
                @include('components.input-rangos', [
                    'label' => 'b) Población mínima viable:',
                    'modelMin' => 'form.pmv_de',
                    'modelMax' => 'form.pmv_a',
                    'modelPromedio' => 'form.pmv_promedio',
                    'showPromedio' => false
                ])
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-[22px] font-bold text-slate-700 tracking-tight">3. Interacciones</h3>
                <button type="button" @click.prevent="agregarInteraccion()" class="flex items-center px-3 py-1.5 bg-indigo-600 text-white text-[16px] font-black rounded-xl hover:bg-indigo-700 transition-all shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Ingresar
                </button>
            </div>

            <div class="h-px bg-slate-100"></div>

            <template x-for="(interaccion, index) in form.interacciones" :key="index">
                <div class="p-6 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-4 relative mb-6"
                    x-data="{
                        openDropdown: false,
                        query: interaccion.especie || '',
                        resultadosLocal: [],

                        async buscarTaxonInteraccion() {
                            if (this.query.length < 2) {
                                this.resultadosLocal = [];
                                this.openDropdown = false;
                                return;
                            }
                            let res = await fetch(`/buscar-especie?q=${encodeURIComponent(this.query)}`);
                            this.resultadosLocal = await res.json();
                            this.openDropdown = true;
                        },

                        async seleccionarEspecieInteraccion(item) {
                            interaccion.especie = item.taxon;
                            this.query = item.taxon;
                            this.openDropdown = false;

                            interaccion.orden = item.Orden || '';
                            interaccion.familia = item.Familia || '';

                            if (!interaccion.orden || !interaccion.familia) {
                                try {
                                    let response = await fetch(`/obtener-taxon-interaccion?taxon=${encodeURIComponent(item.taxon)}`);
                                    let data = await response.json();
                                    interaccion.orden = data.orden || '';
                                    interaccion.familia = data.familia || '';
                                } catch (e) {
                                    console.error('Error al obtener orden y familia:', e);
                                }
                            }
                        }
                    }">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-black uppercase tracking-wider text-indigo-600" x-text="'Interacción ' + (index + 1)"></span>
                        <button type="button" @click.prevent="eliminarInteraccion(index)" class="px-3 py-1.5 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold rounded-lg transition-all shadow-sm cursor-pointer flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Eliminar
                        </button>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-[14px] font-bold text-slate-800 mb-1">i) Tipo de interacción:</label>
                        <select x-model="interaccion.tipo" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-bold text-indigo-900 outline-none focus:border-indigo-400">
                            <option value="">Seleccionar Tipo interacción</option>
                            <option value="depredacion">Depredación</option>
                            <option value="mutualismo">Mutualismo</option>
                            <option value="competencia">Competencia</option>
                            <option value="parasitismo">Parasitismo</option>
                            <option value="comensalismo">Comensalismo</option>
                        </select>
                    </div>

                    <div class="flex flex-col" x-init="
                        setTimeout(() => {
                            if (!window.tinymce.get('interaccion_desc_' + index)) {
                                window.tinymce.init({
                                    selector: '#interaccion_desc_' + index,
                                    plugins: 'lists link paste',
                                    toolbar: 'bold italic | link',
                                    height: 150,
                                    menubar: false,
                                    branding: false,
                                    statusbar: false,
                                    setup: (editor) => {
                                        editor.on('init', () => {
                                            editor.setContent(interaccion.descripcion || '');
                                        });
                                        editor.on('change input undo redo', () => {
                                            interaccion.descripcion = editor.getContent();
                                        });
                                    }
                                });
                            }
                        }, 150)
                    ">
                        <label class="text-[14px] font-bold text-slate-800 mb-1">ii) Descripción de la naturaleza de la interacción:</label>
                        <textarea :id="'interaccion_desc_' + index" class="w-full rounded-xl border border-slate-200 bg-white p-3 text-sm" rows="3"></textarea>
                    </div>

                    <div class="space-y-4">
                        <div class="flex flex-col relative w-full">
                            <label class="text-[14px] font-bold text-slate-800 mb-1">Especie con la que tiene la interacción:</label>
                            <input type="text"
                                x-model="query"
                                @input.debounce.300ms="buscarTaxonInteraccion()"
                                placeholder="Escribe para buscar especie..."
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-bold text-indigo-900 outline-none focus:border-indigo-400 shadow-sm">

                            <div x-show="openDropdown && resultadosLocal.length > 0"
                                @click.outside="openDropdown = false"
                                class="absolute z-50 left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl max-h-48 overflow-y-auto">
                                <template x-for="item in resultadosLocal" :key="item.IdNombre || item.taxon">
                                    <div @click="seleccionarEspecieInteraccion(item)"
                                        class="px-4 py-2.5 hover:bg-indigo-50 cursor-pointer text-xs font-bold text-slate-700 border-b border-slate-100 last:border-none">
                                        <span class="italic text-indigo-900" x-text="item.taxon"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="flex flex-col w-full">
                            <label class="text-[14px] font-bold text-slate-800 mb-1">Orden:</label>
                            <input type="text" x-model="interaccion.orden" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-bold text-indigo-900 outline-none focus:border-indigo-400 shadow-sm" placeholder="Orden" readonly>
                        </div>

                        <div class="flex flex-col w-full">
                            <label class="text-[14px] font-bold text-slate-800 mb-1">Familia:</label>
                            <input type="text" x-model="interaccion.familia" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-bold text-indigo-900 outline-none focus:border-indigo-400 shadow-sm" placeholder="Familia" readonly>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div class="mt-16 flex justify-end pb-24"></div>
    </div>
</div>
