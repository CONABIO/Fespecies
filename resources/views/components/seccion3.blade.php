<div x-show="step === 3" x-cloak x-transition:enter="transition ease-out duration-300" class="max-w-3xl mx-auto space-y-8">
    <h2 class="text-[30px] font-black text-slate-800 tracking-tight" style="margin-top: 35px"> III. Tipo de ambiente en donde se desarrolla la especie</h2>
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-8">
        <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">
            <span class="mr-4">1. Tipo de Ambiente</span>
            <div class="h-px bg-slate-100 flex-grow"></div>
        </h3>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 pb-6 border-b border-slate-50">
            <label class="flex items-center space-x-3 p-3 rounded-xl transition-colors"
                :class="form.bloquearAmbiente ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer hover:bg-slate-50 group'">
                <input type="radio" name="tipo_ambiente" value="Ambiente terrestre" x-model="form.tipoAmbiente"
                    @change="form.ecosistemas = []" :disabled="form.bloquearAmbiente"
                    class="w-5 h-5 text-indigo-600 border-slate-300 disabled:bg-slate-200">
                <span class="text-[16px] font-black text-slate-700">A. Ambiente terrestre</span>
            </label>

            <label class="flex items-center space-x-3 p-3 rounded-xl transition-colors"
                :class="form.bloquearAmbiente ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer hover:bg-slate-50 group'">
                <input type="radio" name="tipo_ambiente" value="Ambiente acuático" x-model="form.tipoAmbiente"
                    @change="form.ecosistemas = []" :disabled="form.bloquearAmbiente"
                    class="w-5 h-5 text-indigo-600 border-slate-300 disabled:bg-slate-200">
                <span class="text-[16px] font-black text-slate-700">B. Ambiente marino </span>
            </label>

            <label class="flex items-center space-x-3 p-3 rounded-xl transition-colors"
                :class="form.bloquearAmbiente ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer hover:bg-slate-50 group'">
                <input type="radio" name="tipo_ambiente" value="Ambiente terrestre-acuático" x-model="form.tipoAmbiente"
                    @change="form.ecosistemas = []" :disabled="form.bloquearAmbiente"
                    class="w-5 h-5 text-indigo-600 border-slate-300 disabled:bg-slate-200">
                <span class="text-[16px] font-black text-slate-700">C. Ambiente epicontinental</span>
            </label>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <div x-show="form.tipoAmbiente === 'Ambiente terrestre' || form.tipoAmbiente === 'Ambiente terrestre-acuático'"
                x-transition class="space-y-4">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center"> a) Ecorregiones terrestres </label>
                @include('components.multi-select', [
                    'model' => 'form.ecorregiones_terrestres',
                    'options' => 'ecorregionOptions.map(o => o.descripcion)',
                    'placeholder' => 'SELECCIONAR ECORREGIÓN TERRESTRE...'
                ])
            </div>

            <div x-show="form.tipoAmbiente === 'Ambiente acuático'" x-transition class="space-y-4">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center"> a) Ecorregiones marinas </label>
                @include('components.multi-select', [
                    'model' => 'form.ecorregiones_marinas_ids',
                    'options' => 'marinasOptions.map(o => o.descn1)',
                    'placeholder' => 'SELECCIONAR ECORREGIÓN MARINA...'
                ])
            </div>

            <div x-show="form.tipoAmbiente" x-transition class="space-y-4">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center"> b) Ecosistemas </label>
                @include('components.multi-select', [
                    'model' => 'form.ecosistemas',
                    'options' => "ecosistemaOptions.filter(o => {
                        const grupoA = ['Selvas húmedas', 'Selvas secas', 'Bosques mesófilos de montaña', 'Bosques templados de coníferas y latifoliadas', 'Matorrales xerófilos', 'Pastizales'];
                        const grupoB = ['Fondos blandos', 'Lechos de pastos marinos', 'Fondos duros o rocosos', 'Lechos de rodolitos', 'Arrecifes coralinos', 'Bosques de macroalgas', 'Regiones mesofóticas', 'Ambientes pelágicos', 'Fosas y llanuras abisales'];
                        const grupoC = ['Humedales', 'Manglares', 'Marismas', 'Estuarios y lagunas costeras', 'Playas arenosas y zonas rocosas intermareales'];
                        if (form.tipoAmbiente === 'Ambiente terrestre') return grupoA.includes(o.tipoecosistema);
                        if (form.tipoAmbiente === 'Ambiente acuático') return grupoB.includes(o.tipoecosistema);
                        if (form.tipoAmbiente === 'Ambiente terrestre-acuático') return grupoC.includes(o.tipoecosistema);
                        return false;
                    }).map(o => o.tipoecosistema)",
                    'placeholder' => 'SELECCIONAR ECOSISTEMA...'
                ])
            </div>

            <div x-show="form.tipoAmbiente" class="pt-2">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Información adicional</label>
                <div class="mt-2">
                    <textarea id="ecorregiones_info_adicional_editor"
                        class="w-full rounded-2xl border-2 border-slate-100 p-4 text-xs bg-slate-50 outline-none focus:border-indigo-400 min-h-[80px] shadow-inner"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div x-show="form.tipoAmbiente === 'Ambiente terrestre' || form.tipoAmbiente === 'Ambiente terrestre-acuático'"
        x-transition class="space-y-8">
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-10">
            <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">2. Tipo de Vegetación</h3>

            <div class="space-y-4">
                <div class="flex flex-col">
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center mb-4">a) Indicar el o los tipos de vegetación en los que se desarrolla la especie </label>
                    @include('components.multi-select', [
                        'model' => 'form.tipo_vegetacion_a',
                        'options' => 'vegetacionOptions.map(o => o.descripcionSubVegetacion)',
                        'placeholder' => 'SELECCIONAR TIPO DE VEGETACIÓN...'
                    ])
                    <div class="mt-4">
                        <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center mb-2">Información adicional</label>
                        <textarea id="vegetacion_info_adicional_a_editor"
                            class="w-full rounded-xl border-2 border-slate-100 p-3 text-xs bg-slate-50 outline-none focus:border-indigo-400 min-h-[60px] shadow-inner"></textarea>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">b) Mencionar las especies asociadas</label>
                <textarea id="especies_asociadas_info_editor"
                    class="w-full rounded-xl border-2 border-slate-100 p-3 text-xs bg-slate-50 outline-none focus:border-indigo-400 min-h-[80px] shadow-inner"></textarea>
            </div>

            <div class="space-y-6">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center mb-4">c) Hábitats antrópicos</label>
                @include('components.multi-select', [
                    'model' => 'form.habitats_antropicos',
                    'options' => 'habitatsAntropicosOptions.map(o => o.descn1)',
                    'placeholder' => 'INDICAR SI SE ENCUENTRA PRESENTE...'
                ])
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-slate-50 p-6 rounded-2xl border border-slate-100 mt-6">
                    <template x-for="(label, key) in { habitatAgropecuario: 'i. ¿Hábitat agropecuario?', zonaUrbana: 'ii. ¿Zonas urbanas?', VegetacionSecundaria: 'iii. ¿Vegetación secundaria?' }">
                        <div class="flex flex-col space-y-3">
                            <span class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center" x-text="label"></span>
                            <div class="flex space-x-4">
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" :name="key" value="si" x-model="form[key]" class="w-4 h-4 text-indigo-600 border-slate-300"><span class="text-[10px] font-bold">Sí</span></label>
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" :name="key" value="no" x-model="form[key]" class="w-4 h-4 text-rose-500 border-slate-300"><span class="text-[10px] font-bold">No</span></label>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="space-y-4">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center mb-4">d) Tipo de vegetación secundaria</label>
                @include('components.multi-select', [
                    'model' => 'form.vegetacion_secondary',
                    'options' => 'vegSecundariaOptions.map(o => o.descn1)',
                    'placeholder' => 'SELECCIONAR VEGETACIÓN SECUNDARIA...'
                ])
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex flex-col h-full hover:shadow-md transition-all duration-300">
                <h3 class="text-[22px] font-black text-slate-800 tracking-tight mb-6">3. Intervalo altitudinal</h3>
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="space-y-1">
                        <label class="text-[11px] font-black text-slate-400  tracking-wider ml-1">de (m)</label>
                        <input type="number" x-model="form.intervaloaltitudinalinicial"
                            @input="actualizarPromedio('intervaloaltitudinalinicial', 'intervaloaltitudinalfinal', 'altitud_prom')"
                            @change="validarRango('intervaloaltitudinalinicial', 'intervaloaltitudinalfinal', 'Altitud')"
                            class="w-full px-4 py-3 rounded-2xl border-2 border-slate-50 bg-slate-50 text-sm font-bold outline-none focus:border-indigo-400 transition-all shadow-inner">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-black text-slate-400 tracking-wider ml-1">a (m)</label>
                        <input type="number" x-model="form.intervaloaltitudinalfinal"
                            @input="actualizarPromedio('intervaloaltitudinalinicial', 'intervaloaltitudinalfinal', 'altitud_prom')"
                            @change="validarRango('intervaloaltitudinalinicial', 'intervaloaltitudinalfinal', 'Altitud')"
                            class="w-full px-4 py-3 rounded-2xl border-2 border-slate-50 bg-slate-50 text-sm font-bold outline-none focus:border-indigo-400 transition-all shadow-inner">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-black text-indigo-400 tracking-wider ml-1">Promedio</label>
                        <div class="w-full px-4 py-3 rounded-2xl bg-indigo-50 border-2 border-indigo-50 text-sm font-black text-indigo-600 flex items-center justify-center">
                            <span x-text="form.altitud_prom"></span>
                        </div>
                    </div>
                </div>
                <div class="flex-grow">
                    <label class="text-[11px] font-black text-slate-400  tracking-wider mb-2 block ml-1">Información adicional altitud</label>
                    <textarea id="tiny-altitud"
                        class="w-full rounded-xl border-2 border-slate-50 p-3 text-xs bg-slate-50 outline-none min-h-[80px] shadow-inner"></textarea>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex flex-col h-full hover:shadow-md transition-all duration-300">
                <h3 class="text-[22px] font-black text-slate-800 tracking-tight mb-6">4. Clima</h3>
                @include('components.multi-select', [
                    'model' => 'form.clima_tipo',
                    'options' => 'climaOptions.map(o => o.descn2)',
                    'placeholder' => 'SELECCIONAR CLIMA...'
                ])
                <div class="flex-grow mt-6">
                    <label class="text-[11px] font-black text-slate-400  tracking-wider mb-2 block ml-1">Información adicional clima</label>
                    <textarea id="tiny-clima"
                        class="w-full rounded-xl border-2 border-slate-50 p-3 text-xs bg-slate-50 outline-none min-h-[80px] shadow-inner"></textarea>
                </div>
            </div>

            <template x-for="param in [
                { id: 'temperatura', label: '5. Intervalo de temperatura', unit: '°C', tiny: 'tiny-temp', init: 'temperaturainicial', final: 'temperaturafinal', prom: 'temeperaturapromedio' },
                { id: 'precipitacion', label: '6. Intervalo de precipitación', unit: 'mm', tiny: 'tiny-precip', init: 'precipitacioninicial', final: 'precipitacionfinal', prom: 'precipitacionpromedio' },
                { id: 'humedad', label: '7. Intervalo de humedad relativa', unit: '%', tiny: 'tiny-humedad', init: 'humedadinicial', final: 'humedadfinal', prom: 'humedadpromedio' }
            ]">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex flex-col h-full hover:shadow-md transition-all duration-300">
                    <h3 class="text-[22px] font-black text-slate-800 tracking-tight mb-6" x-text="param.label"></h3>
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div class="relative">
                            <label class="text-[11px] font-black text-slate-400  mb-1 block ml-1">De</label>
                            <div class="relative">
                                <input type="number" x-model="form[param.init]"
                                    @input="actualizarPromedio(param.init, param.final, param.prom)"
                                    @change="validarRango(param.init, param.final, param.label)"
                                    class="w-full px-4 py-3 rounded-2xl border-2 border-slate-50 bg-slate-50 text-sm font-bold shadow-inner">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[12px] font-black text-slate-300"
                                    x-text="param.unit"></span>
                            </div>
                        </div>
                        <div class="relative">
                            <label class="text-[11px] font-black text-slate-400  mb-1 block ml-1">A</label>
                            <div class="relative">
                                <input type="number" x-model="form[param.final]"
                                    @input="actualizarPromedio(param.init, param.final, param.prom)"
                                    @change="validarRango(param.init, param.final, param.label)"
                                    class="w-full px-4 py-3 rounded-2xl border-2 border-slate-50 bg-slate-50 text-sm font-bold shadow-inner">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[12px] font-black text-slate-300"
                                    x-text="param.unit"></span>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow">
                        <textarea :id="param.tiny"
                            class="w-full rounded-xl border-2 border-slate-50 p-3 text-xs bg-slate-50 outline-none min-h-[80px] shadow-inner"></textarea>
                    </div>
                </div>
            </template>

            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex flex-col h-full hover:shadow-md transition-all duration-300">
                <h3 class="text-[22px] font-black text-slate-800 tracking-tight mb-6">8. Tipo de Suelo</h3>
                @include('components.multi-select', [
                    'model' => 'form.suelo_tipo',
                    'options' => 'suelosOptions.map(o => o.descn1)',
                    'placeholder' => 'SELECCIONAR TIPO DE SUELO...'
                ])
                <div class="flex-grow mt-6">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2 block ml-1">Información adicional suelo</label>
                    <textarea id="tiny-suelo"
                        class="w-full rounded-xl border-2 border-slate-50 p-3 text-xs bg-slate-50 outline-none min-h-[80px] shadow-inner"></textarea>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 space-y-6 col-span-1 lg:col-span-2">
                <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">9. Geoforma </h3>
                @include('components.multi-select', [
                    'model' => 'form.geoforma_tipo',
                    'options' => 'geoformaOptions.map(o => o.descn1)',
                    'placeholder' => 'SELECCIONAR GEOFORMA...'
                ])
                <textarea id="tiny-geoforma"
                    class="w-full rounded-xl border-2 border-slate-50 p-3 text-xs bg-slate-50 outline-none focus:border-indigo-400 min-h-[80px] shadow-inner"></textarea>
            </div>
        </div>
    </div>

    <div x-show="form.tipoAmbiente === 'Ambiente acuático'" x-transition class="space-y-8">
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-8">
            <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center"><span class="mr-4">2. Tipo de hábitat marino</span>
                <div class="h-px bg-slate-100 flex-grow"></div>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">a) Vertical:</label>
                    @include('components.multi-select', [
                        'model' => 'form.habitat_marino_vertical',
                        'options' => "['Bentónico', 'Demersal', 'Epipelágico', 'Mesopelágico', 'Bathipelágico']",
                        'placeholder' => 'SELECCIONAR...'
                    ])
                </div>
                <div class="space-y-2">
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">b) Horizontal:</label>
                    @include('components.multi-select', [
                        'model' => 'form.habitat_marino_horizontal',
                        'options' => "['Asociado a arrecifes', 'Costero', 'Plataforma continental', 'Talud continental', 'Oceánico']",
                        'placeholder' => 'SELECCIONAR...'
                    ])
                </div>
            </div>
            <div class="space-y-4">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Informacion adicional</label>
                <textarea id="tiny-marino-vh"
                    class="w-full rounded-2xl border-2 border-slate-100 p-4 text-xs bg-slate-50 outline-none focus:border-indigo-400 min-h-[100px] shadow-inner"></textarea>
            </div>
            <div class="space-y-4">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">c) Mencionar las especies asociadas</label>
                <textarea id="tiny-marino-especies"
                    class="w-full rounded-2xl border-2 border-slate-100 p-4 text-xs bg-slate-50 outline-none focus:border-indigo-400 min-h-[100px] shadow-inner"></textarea>
            </div>
            <div class="space-y-6">
                <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                        <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">d) Indicar si hay disturbios antrópicos</label>
                        <div class="flex bg-slate-200/50 p-1 rounded-xl border border-slate-200">
                            <label class="flex items-center px-6 py-2 rounded-lg cursor-pointer transition-all duration-300"
                                :class="form.habitat_marino_disturbiosAntropicos == 'SÍ' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-600'"><input
                                    type="radio" x-model="form.habitat_marino_disturbiosAntropicos" value="SÍ"
                                    class="hidden"><span class="text-[10px] font-black">SÍ</span></label>
                            <label class="flex items-center px-6 py-2 rounded-lg cursor-pointer transition-all duration-300"
                                :class="form.habitat_marino_disturbiosAntropicos == 'NO' ? 'bg-white text-rose-500 shadow-sm' : 'text-slate-400 hover:text-slate-600'"><input
                                    type="radio" x-model="form.habitat_marino_disturbiosAntropicos" value="NO"
                                    class="hidden"><span class="text-[10px] font-black">NO</span></label>
                        </div>
                    </div>
                    <div x-show="form.habitat_marino_disturbiosAntropicos == 'SÍ'" x-transition class="mt-4">
                        <textarea id="tiny-marino-disturbios"
                            class="w-full rounded-xl border-2 border-white p-4 text-xs bg-white outline-none focus:border-indigo-400 min-h-[100px] shadow-sm"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="form.tipoAmbiente === 'Ambiente acuático' || form.tipoAmbiente === 'Ambiente terrestre-acuático'"
        x-transition class="space-y-8">
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 space-y-4">
            <h3 class="text-[22px] font-bold text-slate-700 flex items-center">
                <span
                    x-text="form.tipoAmbiente === 'Ambiente acuático' ? '3. Intervalo batimétrico' : '10. Intervalo batimétrico'"></span>
            </h3>
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="flex flex-col gap-3 w-full lg:w-64 flex-shrink-0">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[14px] font-bold text-slate-700">De (m)</label>
                            <input type="number" x-model="form.interbatimetricoinicial"
                                @input="actualizarPromedio('interbatimetricoinicial', 'interbatimetricofinal', 'interbatimetricopromedio')"
                                @change="validarRango('interbatimetricoinicial', 'interbatimetricofinal', 'Batimetría')"
                                class="w-full px-3 py-2 rounded-xl bg-slate-50 border-2 border-slate-50 outline-none">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[14px] font-bold text-slate-700">A (m)</label>
                            <input type="number" x-model="form.interbatimetricofinal"
                                @input="actualizarPromedio('interbatimetricoinicial', 'interbatimetricofinal', 'interbatimetricopromedio')"
                                @change="validarRango('interbatimetricoinicial', 'interbatimetricofinal', 'Batimetría')"
                                class="w-full px-3 py-2 rounded-xl bg-slate-50 border-2 border-slate-50 outline-none">
                        </div>
                    </div>
                    <div class="bg-indigo-50/50 p-3 rounded-xl border border-indigo-100 flex justify-between items-center">
                        <label class="font-bold">Promedio:</label>
                        <span class="font-black text-indigo-600" x-text="form.interbatimetricopromedio"></span>
                    </div>
                </div>
                <div class="flex-grow flex flex-col">
                    <label class="font-bold mb-1">Información adicional</label>
                    <textarea id="tiny-batimetria"
                        class="w-full flex-grow rounded-2xl border-2 border-slate-50 p-3 bg-slate-50 outline-none min-h-[115px] shadow-inner"></textarea>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 space-y-4">
            <h3 class="text-[22px] font-bold text-slate-700 flex items-center">
                <span
                    x-text="form.tipoAmbiente === 'Ambiente acuático' ? '4. Amplitud de mareas' : '11. Amplitud de mareas'"></span>
            </h3>
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="flex flex-col gap-3 w-full lg:w-64 flex-shrink-0">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="font-bold">De</label>
                            <input type="number" x-model="form.amplitudmareasinicial"
                                @input="actualizarPromedio('amplitudmareasinicial', 'amplitudmareasfinal', 'amplitudmareaspromedio')"
                                @change="validarRango('amplitudmareasinicial', 'amplitudmareasfinal', 'Mareas')"
                                class="w-full px-3 py-2 rounded-xl bg-slate-50 border-2 border-slate-50 outline-none">
                        </div>
                        <div>
                            <label class="font-bold">A</label>
                            <input type="number" x-model="form.amplitudmareasfinal"
                                @input="actualizarPromedio('amplitudmareasinicial', 'amplitudmareasfinal', 'amplitudmareaspromedio')"
                                @change="validarRango('amplitudmareasinicial', 'amplitudmareasfinal', 'Mareas')"
                                class="w-full px-3 py-2 rounded-xl bg-slate-50 border-2 border-slate-50 outline-none">
                        </div>
                    </div>
                    <div class="bg-indigo-50/50 p-3 rounded-xl border border-indigo-100 flex justify-between items-center">
                        <label class="font-bold">Promedio:</label>
                        <span class="font-black text-indigo-600" x-text="form.amplitudmareaspromedio"></span>
                    </div>
                </div>
                <div class="flex-grow flex flex-col">
                    <label class="font-bold mb-1">Información adicional</label>
                    <textarea id="tiny-mareas"
                        class="w-full flex-grow rounded-2xl border-2 border-slate-50 p-3 bg-slate-50 outline-none min-h-[110px] shadow-inner"></textarea>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-8">
    <div class="flex items-center gap-4">
        <h3 class="text-[22px] font-bold text-slate-700 flex items-center">
            <span x-text="form.tipoAmbiente === 'Ambiente acuático' ? '5. Características del agua' : '12. Características del agua'"></span>
        </h3>
        <div class="h-[1px] bg-slate-100 flex-1"></div>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <template x-for="item in [
            { id_de: 'salinidadinicial', id_a: 'salinidadfinal', id_p: 'salinidadpromedio', label: 'a) Salinidad' },
            { id_de: 'oxigenoinicial', id_a: 'oxigenofinal', id_p: 'oxigenopromedio', label: 'b) Oxígeno disuelto' },
            { id_de: 'phinicial', id_a: 'phfinal', id_p: 'phpromedio', label: 'c) pH' },
            { id_de: 'temperaturainicial', id_a: 'temperaturafinal', id_p: 'temperaturapromedio', label: 'd) Temperatura' }
        ]">
            <div class="p-6 bg-slate-50 rounded-2xl">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center" x-text="item.label"></label>

                <div class="grid grid-cols-1 gap-4 mt-2 items-end"
                     :class="item.id_de === 'salinidadinicial' ? 'md:grid-cols-3 lg:grid-cols-4' : 'md:grid-cols-2'">

                    <div>
                        <span class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">De</span>
                        <input type="number" x-model="form[item.id_de]"
                            @input="actualizarPromedio(item.id_de, item.id_a, item.id_p)"
                            @change="validarRango(item.id_de, item.id_a, item.label)"
                            class="w-full px-4 py-3 rounded-xl bg-white border-2 border-slate-100 outline-none">
                    </div>

                    <div>
                        <span class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">A</span>
                        <input type="number" x-model="form[item.id_a]"
                            @input="actualizarPromedio(item.id_de, item.id_a, item.id_p)"
                            @change="validarRango(item.id_de, item.id_a, item.label)"
                            class="w-full px-4 py-3 rounded-xl bg-white border-2 border-slate-100 outline-none">
                    </div>

                    <template x-if="item.id_de === 'salinidadinicial'">
                        <div>
                            <span class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Promedio</span>
                            <div class="w-full px-4 py-3 rounded-xl bg-white border-2 border-slate-200 font-bold h-[48px] flex items-center">
                                <span x-text="form[item.id_p]" class="text-indigo-600"></span>
                            </div>
                        </div>
                    </template>

                    <template x-if="item.id_de === 'salinidadinicial'">
                        <div class="w-full">
                            <span class="text-[14px] font-bold text-slate-400 block mb-1">Unidad</span>
                            <select x-model="form.unidadsalinidad"
                                class="w-full px-4 py-3 rounded-xl bg-white border-2 border-slate-100 appearance-none outline-none">
                                <option value="">Seleccione...</option>
                                <option value="Porcentaje(%)">Porcentaje (%)</option>
                                <option value="Partes por mil(ppt)">Partes por mil (ppt)</option>
                                <option value="Gramos por litro(g/L)">Gramos por litro (g/L)</option>
                                <option value="Unidades prácticas de salinidad (ups,psu)">Unidades prácticas de salinidad (ups, psu)</option>
                            </select>
                        </div>
                    </template>
                </div>
            </div>
        </template>

        <div class="p-6 bg-slate-50 rounded-2xl">
            <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">e) Corrientes</label>
            <textarea id="tiny-corrientes"
                class="w-full rounded-xl border-2 border-slate-100 p-3 text-xs bg-white outline-none focus:border-indigo-400 min-h-[60px] shadow-inner"></textarea>
        </div>

        <div class="p-6 bg-slate-50 rounded-2xl">
            <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Información adicional</label>
            <textarea id="tiny-agua"
                class="w-full rounded-2xl border-2 border-slate-100 p-4 text-xs bg-white outline-none min-h-[80px] shadow-inner"></textarea>
        </div>
    </div>
</div>
    </div>
    <div class="mt-16 flex justify-between pb-24"></div>
</div>
