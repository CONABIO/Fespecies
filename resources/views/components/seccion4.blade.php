<div x-show="step === 4" x-cloak x-transition:enter="transition ease-out duration-300" class="space-y-10 pt-8">

    <div class="max-w-3xl mx-auto space-y-10 px-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight mt-1">IV. Biología de la especie</h2>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200/80 space-y-6">
            <div>
                <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">1. Ciclo biológico</h3>
            </div>

            <div class="space-y-3">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Tipo de ciclo</label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <template x-for="opcion in ['Haploide', 'Diploide', 'Haplo-diploide']" :key="opcion">
                        <label
                            @click="form.tipoCiclo = opcion"
                            class="flex items-center gap-3.5 p-4 rounded-2xl cursor-pointer border-2 transition-all select-none"
                            :class="form.tipoCiclo === opcion
                                ? 'border-indigo-600 bg-indigo-50/50 shadow-sm shadow-indigo-100'
                                : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50/60'">
                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all shrink-0"
                                :class="form.tipoCiclo === opcion ? 'border-indigo-600 bg-white' : 'border-slate-300 bg-white'">
                                <div class="w-2.5 h-2.5 rounded-full bg-indigo-600 transition-transform"
                                    :class="form.tipoCiclo === opcion ? 'scale-100' : 'scale-0'"></div>
                            </div>
                            <input type="radio" x-model="form.tipoCiclo" :value="opcion" class="sr-only">
                            <span class="text-sm font-bold"
                                :class="form.tipoCiclo === opcion ? 'text-indigo-950 font-black' : 'text-slate-700'"
                                x-text="opcion"></span>
                        </label>
                    </template>
                </div>
            </div>

            <div class="space-y-2 pt-2">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">
                    <span>Aspectos relevantes del ciclo de vida</span>
                </label>
                <textarea id="tiny-aspectos" x-model="form.aspectos" class="w-full rounded-2xl border border-slate-200 p-4 text-sm bg-slate-50/50" placeholder="Entra cuadro de texto..."></textarea>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200/80 space-y-6">
            <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">2. Uso del hábitat</h3>

            <div x-show="form.Reino === 'Animalia'" class="space-y-2">
                <label class="text-sm font-bold text-slate-700">Uso del hábitat</label>
                <textarea id="tiny-uso-habitat" x-model="form.uso_habitat" class="w-full rounded-2xl border border-slate-200 p-4 text-sm bg-slate-50/50" placeholder="Uso del hábitat"></textarea>
            </div>

            <div x-show="form.Reino === 'Plantae'" class="grid grid-cols-1 gap-6">
                <div>
                    <label class="text-sm font-bold text-slate-700 block mb-2">a) Hábito</label>
                    @include('components.multi-select', [
                        'model' => 'form.habito_planta',
                        'options' => 'habitoOptions',
                        'placeholder' => 'Selecciona hábito(s)...'
                    ])
                </div>
                <div>
                    <label class="text-sm font-bold text-slate-700 block mb-2">b) Forma de vida</label>
                    @include('components.multi-select', [
                        'model' => 'form.forma_vida_planta',
                        'options' => 'formaVidaOptions',
                        'placeholder' => 'Selecciona forma(s) de vida...'
                    ])
                </div>
            </div>

            <div x-show="form.Reino !== 'Animalia' && form.Reino !== 'Plantae'">
                <label class="text-sm font-bold text-slate-700 block mb-2">Forma de vida</label>
                @include('components.multi-select', [
                    'model' => 'form.forma_vida_otros',
                    'options' => 'formaVidaOptions',
                    'placeholder' => 'Selecciona forma(s) de vida...'
                ])
            </div>

            <div class="space-y-2 pt-2 border-t border-slate-100">
                <label class="text-sm font-bold text-slate-700 flex items-center justify-between">
                    <span>Información adicional</span>
                </label>
                <textarea id="tiny-forma-vida-ia" x-model="form.forma_vida_ia" class="w-full rounded-2xl border border-slate-200 p-4 text-sm bg-slate-50/50" placeholder="Información adicional"></textarea>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200/80 space-y-6">
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center mb-2">3. Alimentación</label>
                    @include('components.multi-select', [
                        'model' => 'form.alimentacion',
                        'options' => 'alimentacionOptions',
                        'placeholder' => 'Selecciona alimentación...'
                    ])
                </div>
                <div>
                    <label class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center mb-2">4. Estrategia trófica</label>
                    @include('components.multi-select', [
                        'model' => 'form.estrategia_trofica',
                        'options' => 'estrategiaTroficaOptions',
                        'placeholder' => 'Selecciona estrategia trófica...'
                    ])
                </div>
            </div>
        </div>

        <div x-show="form.Reino === 'Animalia'" class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200/80 space-y-8">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">5. Conducta</h3>
            </div>

            <div class="space-y-2">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">a) Características conductuales</label>
                <textarea id="tiny-caracteristicas-conductuales" x-model="form.caracteristicas_conductuales" class="w-full rounded-2xl border border-slate-200 p-4 text-sm bg-slate-50/50" placeholder="Características conductuales"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">b) Estatus migratorio</label>
                    <textarea id="tiny-estatus-migratorio" x-model="form.estatus_migratorio" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium"></textarea>
                </div>
                <div>
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">c) Tipo de migración</label>
                    <textarea id="tiny-tipo-migracion" x-model="form.tipo_migracion" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium"></textarea>
                </div>
            </div>

            <div class="bg-slate-50/70 p-6 rounded-2xl border border-slate-200/80 space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <label class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">Localidades y meses de migración</label>
                    <button type="button" @click="agregarLocalidad()"
                        x-show="(form.localidades_migracion || []).length < 10"
                        class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-[16px] font-bold transition-all flex items-center gap-1.5 shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Ingresar
                    </button>
                </div>

                <div class="space-y-3">
                    <template x-for="(loc, index) in form.localidades_migracion" :key="index">
                        <div class="bg-white p-4 rounded-xl border border-slate-200 flex flex-wrap md:flex-nowrap items-center gap-3">
                            <span class="text-xs font-black text-slate-400 w-6" x-text="(index + 1) + ')'"></span>
                            <input type="text" x-model="loc.localidad" placeholder="Localidad" class="flex-1 min-w-[200px] px-3 py-2 text-sm border border-slate-200 rounded-lg">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                                <span>De:</span>
                                <input type="text" x-model="loc.mes_inicio" placeholder="mes" class="w-36 px-2.5 py-2 text-xs border border-slate-200 rounded-lg">
                                <span>a:</span>
                                <input type="text" x-model="loc.mes_fin" placeholder="mes" class="w-36 px-2.5 py-2 text-xs border border-slate-200 rounded-lg">
                            </div>
                            <button type="button" @click="eliminarLocalidad(index)" x-show="form.localidades_migracion.length > 1" class="text-rose-500 hover:text-rose-700 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </template>
                </div>

                <div class="pt-2">
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Información adicional de estatus migratorio y tipo de migración</label>
                    <textarea id="tiny-migracion-ia" x-model="form.migracion_ia" class="w-full rounded-xl border border-slate-200 p-3 text-sm bg-white" placeholder="Información adicional"></textarea>
                </div>
            </div>

            <div>
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">d) Periodo de actividad</label>
                <textarea id="tiny-periodo-actividad" x-model="form.periodo_actividad" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium" placeholder="Periodo de actividad"></textarea>
            </div>

            <div class="p-6 bg-slate-50/70 rounded-2xl border border-slate-200/80 space-y-4">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">e) Hibernación y Torpor</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-lg">
                    <div class="flex items-center justify-between bg-white p-3 rounded-xl border border-slate-200">
                        <span class="text-sm font-bold text-slate-700">Hibernación</span>
                        <div class="flex items-center gap-3">
                            <label class="flex items-center gap-1.5 cursor-pointer text-xs font-bold">
                                <input type="radio" x-model="form.hibernacion" value="si" class="text-indigo-600 focus:ring-indigo-500"> Sí
                            </label>
                            <label class="flex items-center gap-1.5 cursor-pointer text-xs font-bold">
                                <input type="radio" x-model="form.hibernacion" value="no" class="text-indigo-600 focus:ring-indigo-500"> No
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-between bg-white p-3 rounded-xl border border-slate-200">
                        <span class="text-sm font-bold text-slate-700">Torpor</span>
                        <div class="flex items-center gap-3">
                            <label class="flex items-center gap-1.5 cursor-pointer text-xs font-bold">
                                <input type="radio" x-model="form.torpor" value="si" class="text-indigo-600 focus:ring-indigo-500"> Sí
                            </label>
                            <label class="flex items-center gap-1.5 cursor-pointer text-xs font-bold">
                                <input type="radio" x-model="form.torpor" value="no" class="text-indigo-600 focus:ring-indigo-500"> No
                            </label>
                        </div>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Información adicional</label>
                    <textarea id="tiny-hibernacion-torpor-ia" x-model="form.hibernacion_torpor_ia" class="w-full rounded-xl border border-slate-200 p-3 text-sm bg-white" placeholder="Entra cuadro de texto..."></textarea>
                </div>
            </div>

            <div>
                @include('components.input-rangos', [
                    'label' => 'g) Ámbito hogareño',
                    'modelMin' => 'form.ambito_hogareno_min',
                    'modelMax' => 'form.ambito_hogareno_max',
                    'modelPromedio' => 'form.ambito_hogareno_promedio'
                ])
                <div class="mt-3">
                    <span class="text-xs font-bold text-slate-500 block mb-1">Unidades</span>
                    <select x-model="form.ambito_hogareno_unidad" class="w-full sm:w-1/3 px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-700">
                        <option value="">Seleccionar...</option>
                        <option value="km2">Kilómetros cuadrados</option>
                        <option value="ha">Hectáreas</option>
                        <option value="m2">Metros cuadrados</option>
                        <option value="acres">Acres</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                <div>
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">h) Mecanismos de defensa</label>
                    <textarea id="tiny-mecanismos-defensa" x-model="form.mecanismos_defensa" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium"></textarea>
                </div>
                <div>
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">i) Organización social</label>
                    <textarea id="tiny-organizacion-social" x-model="form.organizacion_social" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium"></textarea>
                </div>
            </div>
        </div>

        <div x-show="form.Reino === 'Plantae'" class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200/80 space-y-10">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-xl font-bold text-slate-800 tracking-tight">5. Reproducción vegetal</h3>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700">a) Generalidades</label>
                <textarea id="tiny-descripcion-reproduccion" x-model="form.descripcion_reproduccion" class="w-full rounded-2xl border border-slate-200 p-4 text-sm bg-slate-50/50 min-h-[80px]" placeholder="Entra cuadro de texto..."></textarea>
            </div>

            <div class="space-y-4">
                <label class="text-sm font-bold text-slate-800 block">b) Tipos de expresión sexual</label>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <span class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">i. Flores</span>
                        @include('components.multi-select', [
                            'model' => 'form.expresion_flores',
                            'options' => 'expresionFloresOptions',
                            'placeholder' => 'Selecciona flores...'
                        ])
                    </div>
                    <div>
                        <span class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">ii. Individuos</span>
                        @include('components.multi-select', [
                            'model' => 'form.expresion_individuos',
                            'options' => 'expresionIndividuosOptions',
                            'placeholder' => 'Selecciona individuos...'
                        ])
                    </div>
                    <div>
                        <span class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">iii. Poblaciones</span>
                        @include('components.multi-select', [
                            'model' => 'form.expresion_poblaciones',
                            'options' => 'expresionPoblacionesOptions',
                            'placeholder' => 'Selecciona poblaciones...'
                        ])
                    </div>
                </div>
            </div>


            <div class="grid grid-cols-1 gap-6">
                <div>
                    <div class="flex items-center gap-4 mb-2">
                        <label class="text-sm font-bold text-slate-700 whitespace-nowrap">c) Aislamiento temporal de órganos reproductores</label>
                        <select x-model="form.aislamiento_temporal" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium">
                            <option value="">Seleccionar aislamiento...</option>
                            <option value="Dicogamia">Dicogamia</option>
                            <option value="Protandria">Protandria</option>
                            <option value="Protoginia">Protoginia</option>
                            <option value="Hercogamia">Hercogamia</option>
                        </select>
                    </div>
                    <textarea id="tiny-aislamiento-temporal-ia" x-model="form.aislamiento_temporal_ia" placeholder="Información adicional de aislamiento..." class="w-full rounded-xl border border-slate-200 p-2.5 text-xs bg-indigo-50/20"></textarea>
                </div>

                <div>
                    <label class="text-sm font-bold text-slate-700 block mb-2">d) Sistemas reproductivos asexuales</label>
                    @include('components.multi-select', [
                        'model' => 'form.sistemas_reproductivos_asexuales',
                        'options' => 'sistemasAsexualesOptions',
                        'placeholder' => 'Selecciona sistema(s)...'
                    ])
                </div>

                <div>
                    <label class="text-sm font-bold text-slate-700 block mb-2">e) Tipo de fecundación</label>
                    @include('components.multi-select', [
                        'model' => 'form.tipo_fecundacion_plantae',
                        'options' => 'fecundacionPlantaeOptions',
                        'placeholder' => 'Selecciona fecundación...'
                    ])
                </div>

                <div>
                    <label class="text-sm font-bold text-slate-700 block mb-2">f) Tipo de polinización</label>
                    @include('components.multi-select', [
                        'model' => 'form.tipo_polinizacion',
                        'options' => 'polinizacionOptions',
                        'placeholder' => 'Selecciona polinización...'
                    ])
                </div>
            </div>

            <div class="p-6 bg-slate-50/70 rounded-2xl border border-slate-200 space-y-6">
                <h4 class="text-base font-bold text-slate-800">g) Floración:</h4>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <div class="flex items-center gap-4 mb-1">
                            <span class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">i. Horario de apertura de la flor</span>
                            <select x-model="form.flor_horario_apertura"
                                    class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-medium focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                <option value="">Seleccionar horario...</option>
                                <option value="Diurno">Diurno</option>
                                <option value="Crepuscular">Crepuscular</option>
                                <option value="Nocturno">Nocturno</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <span class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">ii. Longevidad de la flor</span>
                        <textarea id="tiny-flor-longevidad" x-model="form.flor_longevidad" placeholder="Sin cambios..." class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm"></textarea>
                    </div>
                </div>

                <div class="space-y-3">
                    <span class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">iii) Época de floración</span>

                    <div class="overflow-x-auto w-fit shadow-sm rounded-lg border border-slate-300">
                        <table class="border-collapse bg-white text-xs select-none">
                            <thead>
                                <tr class="bg-slate-100 text-slate-700">
                                    <th class="border border-slate-300 w-14 h-8"></th>
                                    <template x-for="m in meses" :key="'h-flor-'+m">
                                        <th class="border border-slate-300 px-2.5 py-1 text-center font-bold text-[11px] w-9" x-text="m"></th>
                                    </template>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-slate-300 px-2.5 py-1.5 font-bold text-center bg-slate-50 text-slate-700">
                                        Flor
                                    </td>
                                    <template x-for="m in meses" :key="'td-flor-'+m">
                                        <td @click="toggleMes(form.floracion_meses, m)"
                                            class="border border-slate-300 h-8 text-center font-black text-sm cursor-pointer hover:bg-slate-100 transition-colors"
                                            :class="form.floracion_meses && form.floracion_meses.includes(m) ? 'bg-indigo-50 text-indigo-700' : 'text-transparent'">
                                            <span x-text="(form.floracion_meses && form.floracion_meses.includes(m)) ? 'X' : '-'" :class="(form.floracion_meses && form.floracion_meses.includes(m)) ? 'opacity-100' : 'opacity-0'"></span>
                                        </td>
                                    </template>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="space-y-1 pt-1">
                        <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Información adicional</label>
                        <textarea id="tiny-floracion-ia" x-model="form.floracion_ia" class="w-full rounded-xl border border-slate-200 p-3 text-sm bg-white min-h-[70px]" placeholder="Entra cuadro de texto..."></textarea>
                    </div>
                </div>

                <div class="pt-2">
                    @include('components.input-rangos', [
                        'label' => 'iv. Cantidad de néctar',
                        'modelMin' => 'form.cantidad_nectar_min',
                        'modelMax' => 'form.cantidad_nectar_max',
                        'modelPromedio' => 'form.cantidad_nectar_promedio'
                    ])
                    <div class="space-y-1 pt-2">
                        <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Información adicional</label>
                        <textarea id="tiny-nectar-ia" x-model="form.cantidad_nectar_ia" class="w-full rounded-xl border border-slate-200 p-3 text-sm bg-white min-h-[90px]" placeholder="Información adicional sobre cantidad de néctar..."></textarea>
                    </div>
                </div>

                <div class="space-y-2 pt-4 border-t border-slate-200/60">
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">v. Cantidad de polen</label>
                    <textarea id="tiny-polen" x-model="form.cantidad_polen" class="w-full rounded-xl border border-slate-200 p-3 text-sm bg-white min-h-[110px]" placeholder="Indicar la cantidad de polen producido por cada antera..."></textarea>
                </div>
            </div>

            <div class="p-6 bg-slate-50/70 rounded-2xl border border-slate-200 space-y-6">
                <h4 class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">h) Fructificación</h4>

                <div class="space-y-3">
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">
                        i. Época de fructificación
                    </label>

                    <div class="overflow-x-auto w-fit shadow-sm rounded-lg border border-slate-300">
                        <table class="border-collapse bg-white text-xs select-none">
                            <thead>
                                <tr class="bg-slate-100 text-slate-700">
                                    <th class="border border-slate-300 w-14 h-8"></th>
                                    <template x-for="m in meses" :key="'h-fruto-'+m">
                                        <th class="border border-slate-300 px-2.5 py-1 text-center font-bold text-[11px] w-9" x-text="m"></th>
                                    </template>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-slate-300 px-2.5 py-1.5 font-bold text-center bg-slate-50 text-slate-700">
                                        Fruto
                                    </td>
                                    <template x-for="m in meses" :key="'td-fruto-'+m">
                                        <td @click="toggleMes(form.fructificacion_meses, m)"
                                            class="border border-slate-300 h-8 text-center font-black text-sm cursor-pointer hover:bg-slate-100 transition-colors"
                                            :class="form.fructificacion_meses && form.fructificacion_meses.includes(m) ? 'bg-indigo-50 text-indigo-700' : 'text-transparent'">
                                            <span x-text="(form.fructificacion_meses && form.fructificacion_meses.includes(m)) ? 'X' : '-'" :class="(form.fructificacion_meses && form.fructificacion_meses.includes(m)) ? 'opacity-100' : 'opacity-0'"></span>
                                        </td>
                                    </template>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="space-y-1 pt-1">
                        <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Información adicional</label>
                        <textarea id="tiny-fructificacion-ia" x-model="form.fructificacion_ia" class="w-full rounded-xl border border-slate-200 p-3 text-sm bg-white min-h-[70px]" placeholder="Entra cuadro de texto..."></textarea>
                    </div>
                </div>

                <div>
                    @include('components.input-rangos', [
                        'label' => 'ii. Número de frutos',
                        'modelMin' => 'form.frutos_min',
                        'modelMax' => 'form.frutos_max',
                        'modelPromedio' => 'form.frutos_promedio'
                    ])
                </div>

                <div class="space-y-3 pt-2">
                    <div class="flex items-center gap-4">
                        <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">iii. Características del fruto</label>
                        <select x-model="form.fruto_caracteristicas"
                                class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-medium focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                            <option value="">Seleccionar característica del fruto...</option>
                            <template x-for="item in caracFrutoOptions" :key="item.v">
                                <option :value="item.v" x-text="item.t" :selected="form.fruto_caracteristicas == item.v"></option>
                            </template>
                        </select>
                    </div>

                    <div class="space-y-1 pt-1">
                        <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Información adicional</label>
                        <textarea id="tiny-fruto-ia" x-model="form.frutos_ia"
                            class="w-full rounded-xl border border-slate-200 p-3 text-sm bg-white min-h-[90px]"
                            placeholder="Información adicional sobre frutos..."></textarea>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-slate-50/70 rounded-2xl border border-slate-200 space-y-4">
                <div class="space-y-2">
                    <div class="flex items-center gap-4">
                        <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">i) Número de eventos reproductivos</label>
                        <select x-model="form.estrategia_reproductiva_planta"
                                class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-medium focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                            <option value="">Seleccionar número de eventos...</option>
                            <option value="Iteróparo" :selected="form.estrategia_reproductiva_planta && form.estrategia_reproductiva_planta.startsWith('Ite')">Iteróparo (policárpica)</option>
                            <option value="Semélparo" :selected="form.estrategia_reproductiva_planta && form.estrategia_reproductiva_planta.startsWith('Sem')">Semélparo (monocárpica)</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1 pt-1">
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Información adicional</label>
                    <textarea id="tiny-eventos-ia" x-model="form.estrategia_reproductiva_planta_ia"
                              class="w-full rounded-xl border border-slate-200 p-3 text-sm bg-white min-h-[90px]"
                              placeholder="Información adicional sobre el modo de reproducción y tiempo entre eventos reproductivos..."></textarea>
                </div>
            </div>

            <div class="p-6 bg-slate-50/70 rounded-2xl border border-slate-200 space-y-6">
                <h4 class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">j) Semillas</h4>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-lg">
                        <div class="flex items-center justify-between bg-white p-3 rounded-xl border border-slate-200">
                            <span class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Latencia</span>
                            <div class="flex items-center gap-3 text-xs font-bold">
                                <label class="flex items-center gap-1.5 cursor-pointer"><input type="radio" x-model="form.semillas_latencia" value="si" class="text-indigo-600"> Sí</label>
                                <label class="flex items-center gap-1.5 cursor-pointer"><input type="radio" x-model="form.semillas_latencia" value="no" class="text-indigo-600"> No</label>
                            </div>
                        </div>
                        <div class="flex items-center justify-between bg-white p-3 rounded-xl border border-slate-200">
                            <span class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Toxicidad</span>
                            <div class="flex items-center gap-3 text-xs font-bold">
                                <label class="flex items-center gap-1.5 cursor-pointer"><input type="radio" x-model="form.semillas_toxicidad" value="si" class="text-indigo-600"> Sí</label>
                                <label class="flex items-center gap-1.5 cursor-pointer"><input type="radio" x-model="form.semillas_toxicidad" value="no" class="text-indigo-600"> No</label>
                            </div>
                        </div>
                    </div>
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Información adicional</label>
                    <textarea id="tiny-semillas-ia" x-model="form.semillas_caracteristicas_ia" placeholder="Información adicional" class="w-full rounded-xl border border-slate-200 p-3 text-sm bg-white"></textarea>
                </div>

                <div>
                    @include('components.input-rangos', [
                        'label' => 'ii. Número de semillas por fruto',
                        'modelMin' => 'form.semillas_num_min',
                        'modelMax' => 'form.semillas_num_max',
                        'modelPromedio' => 'form.semillas_num_promedio'
                    ])
                </div>

                <div>
                    @include('components.input-rangos', [
                        'label' => 'iii. Tamaño de las semillas',
                        'modelMin' => 'form.semillas_tam_min',
                        'modelMax' => 'form.semillas_tam_max',
                        'modelPromedio' => 'form.semillas_tam_promedio'
                    ])
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Información adicional</label>
                    <textarea id="tiny-semillas-tam-ia" x-model="form.semillas_tam_ia" placeholder="Información adicional" class="w-full rounded-xl border border-slate-200 p-3 text-sm bg-white mt-2"></textarea>
                </div>

                <div>
                    @include('components.input-rangos', [
                        'label' => 'iv. Porcentaje de germinación',
                        'modelMin' => 'form.germinacion_min',
                        'modelMax' => 'form.germinacion_max',
                        'modelPromedio' => 'form.germinacion_promedio'
                    ])
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Información adicional</label>
                    <textarea id="tiny-germinacion-ia" x-model="form.germinacion_ia" placeholder="Mantener su cuadro de texto para IA..." class="w-full rounded-xl border border-slate-200 p-3 text-sm bg-white mt-2"></textarea>
                </div>

                <div>
                    @include('components.input-rangos', [
                        'label' => 'v. Porcentaje de supervivencia de plántulas',
                        'modelMin' => 'form.supervivencia_min',
                        'modelMax' => 'form.supervivencia_max',
                        'modelPromedio' => 'form.supervivencia_promedio'
                    ])
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Información adicional</label>
                    <textarea id="tiny-supervivencia-ia" x-model="form.supervivencia_ia" placeholder="Mantener su cuadro de texto para IA..." class="w-full rounded-xl border border-slate-200 p-3 text-sm bg-white mt-2"></textarea>
                </div>
            </div>
        </div>

        <div x-show="form.Reino === 'Animalia'" class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200/80 space-y-10">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">6. Reproducción animal</h3>
            </div>

            <div class="space-y-2">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">a) Generalidades</label>
                <textarea id="tiny-repro-animal-gen" x-model="form.descripcion_reproduccion" class="w-full rounded-2xl border border-slate-200 p-4 text-sm bg-slate-50/50 min-h-[80px]" placeholder="Entra cuadro de texto..."></textarea>
            </div>

            <div class="p-6 bg-slate-50/70 rounded-2xl border border-slate-200 space-y-6">
                <div>
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">b) Sistema de reproducción animal</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl border bg-white cursor-pointer shadow-sm text-sm font-bold"
                            :class="form.repro_sexual ? 'border-indigo-600 text-indigo-700' : 'border-slate-200 text-slate-600'">
                            <input type="checkbox" x-model="form.repro_sexual" class="rounded text-indigo-600 w-4 h-4">
                            <span>Sexual</span>
                        </label>
                        <label class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl border bg-white cursor-pointer shadow-sm text-sm font-bold"
                            :class="form.repro_asexual ? 'border-indigo-600 text-indigo-700' : 'border-slate-200 text-slate-600'">
                            <input type="checkbox" x-model="form.repro_asexual" class="rounded text-indigo-600 w-4 h-4">
                            <span>Asexual</span>
                        </label>
                    </div>
                </div>

                <div x-show="form.repro_sexual" class="p-4 bg-white rounded-xl border border-slate-200 space-y-2">
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">i. Tipo de fecundación</label>
                    <textarea id="tiny-tipo-fecundacion-animal" x-model="form.tipo_fecundacion_animal" placeholder="Tipo de fecundación" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm"></textarea>
                </div>

                <div x-show="form.repro_asexual" class="p-4 bg-white rounded-xl border border-slate-200 space-y-2">
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">ii. Tipo de reproducción asexual</label>
                    <textarea id="tiny-tipo-repro-asexual-animal" x-model="form.tipo_reproduccion_asexual_animal" placeholder="Tipo de reproducción asexual" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm"></textarea>
                </div>

                <div>
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Información adicional</label>
                    <textarea id="tiny-sistema-repro-animal-ia" x-model="form.sistema_repro_animal_ia" placeholder="Información adicional" class="w-full rounded-xl border border-slate-200 p-3 text-sm bg-white"></textarea>
                </div>
            </div>

            <div class="p-6 bg-slate-50/70 rounded-2xl border border-slate-200 space-y-4">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">c) Dimorfismo sexual</label>
                <div class="flex items-center gap-6 bg-white p-3 rounded-xl border border-slate-200 w-fit">
                    <span class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">i. ¿Hay dimorfismo sexual?</span>
                    <div class="flex items-center gap-3 text-xs font-bold">
                        <label class="flex items-center gap-1 cursor-pointer"><input type="radio" x-model="form.hay_dimorfismo" value="si" class="text-indigo-600"> Sí</label>
                        <label class="flex items-center gap-1 cursor-pointer"><input type="radio" x-model="form.hay_dimorfismo" value="no" class="text-indigo-600"> No</label>
                    </div>
                </div>

                <div x-show="form.hay_dimorfismo === 'si'" class="space-y-2 pt-2">
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">ii. Tipo de dimorfismo</label>
                    <textarea id="tiny-tipo-dimorfismo" x-model="form.tipo_dimorfismo" placeholder="VC (varias opciones)..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm"></textarea>
                </div>

                <div class="pt-2">
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Información adicional</label>
                    <textarea id="tiny-dimorfismo-ia" x-model="form.dimorfismo_ia" placeholder="Información adicional" class="w-full rounded-xl border border-slate-200 p-3 text-sm bg-white"></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">d) Sistemas de apareamiento</label>
                    <textarea id="tiny-sistemas-apareamiento" x-model="form.sistemas_apareamiento" placeholder="Sistemas de apareamiento" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium"></textarea>
                </div>
                <div>
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">e) Estrategia reproductiva</label>
                    <textarea id="tiny-estrategia-reproductiva-animal" x-model="form.estrategia_reproductiva_animal" placeholder="Estrategia reproductiva" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium"></textarea>
                </div>
            </div>

            <div>
                @include('components.input-rangos', [
                    'label' => 'f) Tiempo entre eventos reproductivos',
                    'modelMin' => 'form.tiempo_eventos_min',
                    'modelMax' => 'form.tiempo_eventos_max',
                    'modelPromedio' => 'form.tiempo_eventos_promedio'
                ])
                <div class="mt-2">
                    <span class="text-xs font-bold text-slate-500 block mb-1">Unidad de tiempo</span>
                    <input type="text" x-model="form.tiempo_eventos_unidad" placeholder="ej. meses / años" class="w-full sm:w-1/3 px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-semibold">
                </div>
                <textarea id="tiny-tiempo-eventos-ia" x-model="form.tiempo_eventos_ia" placeholder="Agregar casilla de texto libre para IA..." class="w-full rounded-xl border border-slate-200 p-3 text-sm bg-slate-50/50 mt-2"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">g) Edad o talla a la primera reproducción</label>
                    <textarea id="tiny-edad-primera-reproduccion" x-model="form.edad_primera_reproduccion" placeholder="Transformar la casilla actual en casilla de texto libre..." class="w-full rounded-xl border border-slate-200 p-3 text-sm bg-slate-50/50 min-h-[80px]"></textarea>
                </div>
                <div>
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">h) Duración de la vida reproductiva</label>
                    <textarea id="tiny-duracion-vida-reproductiva" x-model="form.duracion_vida_reproductiva" placeholder="Transformar la casilla actual en casilla de texto libre..." class="w-full rounded-xl border border-slate-200 p-3 text-sm bg-slate-50/50 min-h-[80px]"></textarea>
                </div>
            </div>

            <div class="p-6 bg-slate-50/70 rounded-2xl border border-slate-200 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">j) Sitios de anidación o crianza</label>
                        <textarea id="tiny-sitios-anidacion" x-model="form.sitios_anidacion" placeholder="Cambiar los VC..." class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm bg-white"></textarea>
                    </div>
                    <div>
                        <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">k) Tipo de estructura de anidación o crianza</label>
                        <textarea id="tiny-tipo-estructura-anidacion" x-model="form.tipo_estructura_anidacion" placeholder="Incluir VC..." class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm bg-white"></textarea>
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Información adicional</label>
                    <textarea id="tiny-anidacion-ia" x-model="form.anidacion_ia" placeholder="Entra cuadro de texto..." class="w-full rounded-xl border border-slate-200 p-3 text-sm bg-white"></textarea>
                </div>
            </div>

            <div>
                @include('components.input-rangos', [
                    'label' => 'l) Número de huevos o crías',
                    'modelMin' => 'form.crias_min',
                    'modelMax' => 'form.crias_max',
                    'modelPromedio' => 'form.crias_promedio'
                ])
            </div>

            <div class="p-6 bg-slate-50/70 rounded-2xl border border-slate-200 space-y-4">
                <div class="flex flex-wrap items-center gap-6">
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">m) Cuidado parental</label>
                    <div class="flex items-center gap-3 text-xs font-bold bg-white px-3 py-1.5 rounded-lg border border-slate-200">
                        <label class="flex items-center gap-1 cursor-pointer"><input type="radio" x-model="form.cuidado_parental" value="si" class="text-indigo-600"> Sí</label>
                        <label class="flex items-center gap-1 cursor-pointer"><input type="radio" x-model="form.cuidado_parental" value="no" class="text-indigo-600"> No</label>
                    </div>
                </div>

                <div x-show="form.cuidado_parental === 'si'" class="space-y-1 pt-1">
                    <textarea id="tiny-cuidado-parental-vc" x-model="form.cuidado_parental_vc" placeholder="Selección de VC correspondientes..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm"></textarea>
                </div>

                <div class="pt-2">
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">n) Tiempo de cuidado parental</label>
                    <textarea id="tiny-tiempo-cuidado-parental" x-model="form.tiempo_cuidado_parental" placeholder="Tiempo de cuidado parental..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm"></textarea>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200/80 space-y-6">
            <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">7. Dispersión</h3>

            <div>
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center mb-2">a) Tipo de dispersión</label>
                @include('components.multi-select', [
                    'model' => 'form.dispersion_tipo',
                    'options' => 'tipoDispersionOptions',
                    'placeholder' => 'Selecciona tipo de dispersión...'
                ])
            </div>
            <div>
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center mb-2">b) Estructura o individuo dispersado</label>
                @include('components.multi-select', [
                    'model' => 'form.dispersion_estructura',
                    'options' => 'estructuraDispersionOptions',
                    'placeholder' => 'Selecciona estructura...'
                ])
            </div>

            <div>
                @include('components.input-rangos', [
                    'label' => 'c) Distancia de dispersión',
                    'modelMin' => 'form.dispersion_dist_min',
                    'modelMax' => 'form.dispersion_dist_max',
                    'modelPromedio' => 'form.dispersion_dist_promedio',
                    'modelUnidad' => 'form.dispersion_unidad',
                    'optionsUnidad' => [
                        'm' => 'Metros (m)',
                        'km' => 'Kilómetros (km)',
                        'cm' => 'Centímetros (cm)'
                    ]
                ])
            </div>
        </div>

        <div class="mt-16 flex justify-end pb-24"></div>
    </div>
</div>
