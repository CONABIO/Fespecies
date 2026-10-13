<div x-show="step === 7" x-cloak x-transition:enter="transition ease-out duration-300" class="space-y-8 pt-10">
    <div class="max-w-3xl mx-auto space-y-12">
        <h2 class="text-[30px] font-black text-slate-800 tracking-tight">
            VII. Importancia de la especie
        </h2>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <h3 class="text-[22px] font-bold text-slate-700 tracking-tight mb-2">1. Importancia biológica</h3>

            <div class="flex flex-col">
                <label class="text-[16px] font-bold text-slate-800 mb-2">a) Importancia biológica:</label>
                <textarea id="addinfofuneco_editor" x-model="form.addinfofuneco" class="w-full rounded-2xl border border-slate-200 p-4 text-sm" rows="4" placeholder="Describa la importancia biológica..."></textarea>
            </div>

            <div class="h-px bg-slate-100"></div>

            <div class="flex flex-col space-y-3">
                <label class="text-[16px] font-bold text-slate-800">b) Función ecológica:</label>
                @include('components.multi-select', [
                    'model' => 'form.funcionecologica',
                    'options' => 'funcionEcologicaOptions',
                    'placeholder' => 'Selecciona función(es) ecológica(s)...'
                ])
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <h3 class="text-[22px] font-bold text-slate-700 tracking-tight mb-2">2. Importancia económica</h3>
            <div class="flex flex-col">
                <textarea id="impoeco_editor" x-model="form.impoeco" class="w-full rounded-2xl border border-slate-200 p-4 text-sm" rows="4" placeholder="Describa la importancia económica..."></textarea>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <h3 class="text-[22px] font-bold text-slate-700 tracking-tight mb-2">3. Comercio</h3>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-[18px] font-bold text-slate-700">a) Comercio nacional</h4>
                    <button type="button" @click.prevent="form.comercio_nacional.push({tipo: '', unidad: '', cantidad: ''})"  class="flex items-center px-3 py-1.5 bg-indigo-600 text-white text-[16px] font-black rounded-xl hover:bg-indigo-700 transition-all shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Ingresar
                </button>
                </div>

                <template x-for="(prod, index) in form.comercio_nacional" :key="'nal-'+index">
                    <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-3 relative">
                        <button type="button" @click.prevent="form.comercio_nacional.splice(index, 1)" class="absolute top-4 right-4 px-3 py-1.5 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold rounded-lg transition-all shadow-sm cursor-pointer flex items-center gap-1.5 z-10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Eliminar
                        </button>

                        <div class="flex flex-col">
                            <label class="text-xs font-bold text-slate-700 mb-1">Tipo de producto:</label>
                            <select x-model="prod.tipo" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm font-bold text-indigo-900">
                                <option value="">Selecciona tipo de producto...</option>
                                <template x-for="item in productoComercioNalOptions" :key="item.v">
                                    <option :value="item.v" x-text="item.t"></option>
                                </template>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-700 mb-1">Unidad comerciada:</label>
                                <select x-model="prod.unidad" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm font-bold text-indigo-900">
                                    <option value="">Selecciona unidad...</option>
                                    <template x-for="item in unidadComercioOptions" :key="item.v">
                                        <option :value="item.v" x-text="item.t"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-700 mb-1">Cantidad comerciada:</label>
                                <input type="number" min="0" x-model="prod.cantidad" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm font-bold text-indigo-900" placeholder="Cantidad...">
                            </div>
                        </div>
                    </div>
                </template>

                <div class="flex flex-col space-y-3 pt-2">
                    <label class="text-[14px] font-bold text-slate-800">Origen de los especímenes (Nacional):</label>
                    @include('components.multi-select', [
                        'model' => 'form.comnalsel',
                        'options' => 'origenComercioNalOptions',
                        'placeholder' => 'Selecciona origen...'
                    ])
                </div>

                <div class="flex flex-col space-y-3 pt-2">
                    <label class="text-[14px] font-bold text-slate-800">Propósito del comercio (Nacional):</label>
                    @include('components.multi-select', [
                        'model' => 'form.comprodnal',
                        'options' => 'propositoComercioNalOptions',
                        'placeholder' => 'Selecciona propósito...'
                    ])
                </div>
            </div>

            <div class="h-px bg-slate-100 my-6"></div>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-[18px] font-bold text-slate-700">b) Comercio internacional</h4>
                    <button type="button" @click.prevent="form.comercio_internacional.push({tipo: '', unidad: '', cantidad: ''})"   class="flex items-center px-3 py-1.5 bg-indigo-600 text-white text-[16px] font-black rounded-xl hover:bg-indigo-700 transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Ingresar
                    </button>
                </div>

                <template x-for="(prod, index) in form.comercio_internacional" :key="'inter-'+index">
                    <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-3 relative">
                            <button type="button" @click.prevent="form.comercio_internacional.splice(index, 1)" class="absolute top-4 right-4 px-3 py-1.5 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold rounded-lg transition-all shadow-sm cursor-pointer flex items-center gap-1.5 z-10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Eliminar
                            </button>

                        <div class="flex flex-col">
                            <label class="text-xs font-bold text-slate-700 mb-1">Tipo de producto:</label>
                            <select x-model="prod.tipo" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm font-bold text-indigo-900">
                                <option value="">Selecciona tipo de producto...</option>
                                <template x-for="item in productoComercioInterOptions" :key="item.v">
                                    <option :value="item.v" x-text="item.t"></option>
                                </template>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-700 mb-1">Unidad comerciada:</label>
                                <select x-model="prod.unidad" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm font-bold text-indigo-900">
                                    <option value="">Selecciona unidad...</option>
                                    <template x-for="item in unidadComercioInterOptions" :key="item.v">
                                        <option :value="item.v" x-text="item.t"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-700 mb-1">Cantidad comerciada:</label>
                                <input type="number" min="0" x-model="prod.cantidad" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm font-bold text-indigo-900" placeholder="Cantidad...">
                            </div>
                        </div>
                    </div>
                </template>

                <div class="flex flex-col space-y-3 pt-2">
                    <label class="text-[14px] font-bold text-slate-800">Origen de los especímenes (Internacional):</label>
                    @include('components.multi-select', [
                        'model' => 'form.comintersel',
                        'options' => 'origenComercioInterOptions',
                        'placeholder' => 'Selecciona origen...'
                    ])
                </div>

                <div class="flex flex-col space-y-3 pt-2">
                    <label class="text-[14px] font-bold text-slate-800">Propósito del comercio (Internacional):</label>
                    @include('components.multi-select', [
                        'model' => 'form.comprodinter',
                        'options' => 'propositoComercioInterOptions',
                        'placeholder' => 'Selecciona propósito...'
                    ])
                </div>

                <div class="flex flex-col space-y-3 pt-2">
                    <label class="text-[14px] font-bold text-slate-800">País de importación:</label>
                    @include('components.multi-select', [
                        'model' => 'form.paiscom',
                        'options' => 'paisesOptions',
                        'placeholder' => 'Selecciona país(es)...'
                    ])
                </div>
            </div>

            <div class="h-px bg-slate-100 my-6"></div>

            <div class="space-y-4">
                <h4 class="text-[18px] font-bold text-slate-700">c) Afectaciones de la actividad comercial</h4>

                <div class="flex items-center justify-between bg-slate-50 p-3 rounded-xl border border-slate-200">
                    <span class="text-sm font-bold text-slate-700">i. ¿La especie se ve afectada por el comercio ilícito nacional?</span>
                    <div class="flex items-center gap-4 text-xs font-bold">
                        <label class="flex items-center gap-1 cursor-pointer"><input type="radio" x-model="form.sncomnal" value="Sí" class="text-indigo-600"> Sí</label>
                        <label class="flex items-center gap-1 cursor-pointer"><input type="radio" x-model="form.sncomnal" value="no" class="text-indigo-600"> No</label>
                        <label class="flex items-center gap-1 cursor-pointer"><input type="radio" x-model="form.sncomnal" value="ND" class="text-indigo-600"> ND</label>
                    </div>
                </div>

                <div class="flex items-center justify-between bg-slate-50 p-3 rounded-xl border border-slate-200">
                    <span class="text-sm font-bold text-slate-700">ii. ¿La especie se ve afectada por el comercio ilícito internacional?</span>
                    <div class="flex items-center gap-4 text-xs font-bold">
                        <label class="flex items-center gap-1 cursor-pointer"><input type="radio" x-model="form.sncominter" value="Sí" class="text-indigo-600"> Sí</label>
                        <label class="flex items-center gap-1 cursor-pointer"><input type="radio" x-model="form.sncominter" value="no" class="text-indigo-600"> No</label>
                        <label class="flex items-center gap-1 cursor-pointer"><input type="radio" x-model="form.sncominter" value="ND" class="text-indigo-600"> ND</label>
                    </div>
                </div>

                <div class="flex flex-col pt-2">
                    <label class="text-xs font-bold text-slate-700 mb-1">iii. Efectos reales o potenciales del comercio en el manejo y conservación:</label>
                    <textarea id="addinfocom_editor" x-model="form.addinfocom" class="w-full rounded-2xl border border-slate-200 p-4 text-sm" rows="4" placeholder="Describa los efectos..."></textarea>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <h3 class="text-[22px] font-bold text-slate-700 tracking-tight mb-2">4. Importancia cultural y usos</h3>

            <div class="flex flex-col space-y-3">
                <label class="text-[14px] font-bold text-slate-800">Usos (Cultura y usos):</label>
                @include('components.multi-select', [
                    'model' => 'form.usos',
                    'options' => 'usosCulturaOptions',
                    'placeholder' => 'Selecciona uso(s)...'
                ])
            </div>

            <div class="flex flex-col pt-4">
                <label class="text-[14px] font-bold text-slate-800 mb-2">Información adicional / Descripción de usos:</label>
                <textarea id="culturausos_editor" x-model="form.culturausos" class="w-full rounded-2xl border border-slate-200 p-4 text-sm" rows="4" placeholder="Describa los usos..."></textarea>
            </div>
        </div>

        <div class="mt-16 flex justify-end pb-24"></div>
    </div>
</div>
