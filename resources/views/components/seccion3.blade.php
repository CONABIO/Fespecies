<div x-show="step === 3" x-cloak x-transition:enter="transition ease-out duration-300" class="max-w-6xl mx-auto space-y-8">
    <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight" style="margin-top: 35px">III. TIPO DE AMBIENTE EN DONDE SE DESARROLLA LA ESPECIE</h2>
    <div x-show="step === 3" x-cloak x-transition:enter="transition ease-out duration-300" class="max-w-6xl mx-auto space-y-8">
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-8">
        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] flex items-center">
            <span class="mr-4">15. Tipo de Ambiente y Ecorregión</span>
            <div class="h-px bg-slate-100 flex-grow"></div>
        </h3>

        <div class="grid grid-cols-1 lg:grid-cols-2 lg:grid-cols-4 gap-4 pb-6 border-b border-slate-50">
            <label class="flex items-center space-x-3 cursor-pointer group">
                <input type="radio" name="tipo_ambiente" value="A" x-model="form.tipo_ambiente" class="w-5 h-5 text-indigo-600 border-slate-300">
                <span class="text-xs font-black text-slate-700 uppercase">A. Ambiente terrestre-acuático</span>
            </label>
            <label class="flex items-center space-x-3 cursor-pointer group">
                <input type="radio" name="tipo_ambiente" value="C" x-model="form.tipo_ambiente" class="w-5 h-5 text-indigo-600 border-slate-300">
                <span class="text-xs font-black text-slate-700 uppercase">B. Ambiente acuático</span>
            </label>
            <label class="flex items-center space-x-3 cursor-pointer group">
                <input type="radio" name="tipo_ambiente" value="D" x-model="form.tipo_ambiente" class="w-5 h-5 text-indigo-600 border-slate-300">
                <span class="text-xs font-black text-slate-700 uppercase">C. Ambiente</span>
            </label>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <div x-show="form.tipo_ambiente === 'A' || form.tipo_ambiente === 'B'" x-transition class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">a) Ecorregiones terrestres</label>
                <input type="text" x-model="form.ecorregiones_terrestres" class="w-full px-4 py-3 rounded-xl border-2 border-slate-100 bg-slate-50 text-sm outline-none focus:border-indigo-400 shadow-inner">
            </div>
            <div x-show="form.tipo_ambiente === 'A' || form.tipo_ambiente === 'C'" x-transition class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">b) Ecorregiones marinas</label>
                <input type="text" x-model="form.ecorregiones_marinas" class="w-full px-4 py-3 rounded-xl border-2 border-slate-100 bg-slate-50 text-sm outline-none focus:border-indigo-400 shadow-inner">
            </div>
            <div x-show="form.tipo_ambiente === 'A' || form.tipo_ambiente === 'D'" x-transition class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">c) Ecosistemas</label>
                <input type="text" x-model="form.ecosistemas" class="w-full px-4 py-3 rounded-xl border-2 border-slate-100 bg-slate-50 text-sm outline-none focus:border-indigo-400 shadow-inner">
            </div>
            <div x-show="form.tipo_ambiente" class="pt-4">
                <label class="text-[10px] font-black text-slate-400 uppercase mb-2 block">Incluir recuadro de información adicional (para los tres incisos):</label>
                <textarea x-model="form.ecorregiones_info_adicional" class="w-full rounded-2xl border-2 border-slate-100 p-4 text-sm bg-slate-50 focus:border-indigo-400 outline-none min-h-[80px] shadow-inner"></textarea>
            </div>
        </div>
    </div>

    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-10">
        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] flex items-center">
            <span class="mr-4">16. Tipo de vegetación</span>
            <div class="h-px bg-slate-100 flex-grow"></div>
        </h3>
        <div class="space-y-4">
            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">a)  Indicar el o los tipos de vegetación en los que se desarrolla la especie:</label>
            <input type="text" x-model="form.tipo_vegetacion_a" class="w-full px-4 py-3 rounded-xl border-2 border-slate-100 bg-slate-50 text-sm outline-none focus:border-indigo-400">
            <textarea x-model="form.vegetacion_info_adicional_a" placeholder="Recuadro de información adicional..." class="w-full rounded-xl border-2 border-slate-100 p-3 text-xs bg-slate-50 outline-none focus:border-indigo-400 min-h-[60px]"></textarea>
        </div>
        <div class="space-y-4">
            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">b) Mencionar las especies asociadas</label>
            <input type="text" x-model="form.especies_asociadas" class="w-full px-4 py-3 rounded-xl border-2 border-slate-100 bg-slate-50 text-sm outline-none focus:border-indigo-400">
            <textarea x-model="form.especies_asociadas_info" placeholder="Recuadro de información adicional..." class="w-full rounded-xl border-2 border-slate-100 p-3 text-xs bg-slate-50 outline-none focus:border-indigo-400 min-h-[60px]"></textarea>
        </div>
        <div class="space-y-6">
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">c) Hábitats antrópicos:</label>
                <input type="text" x-model="form.habitats_antropicos" class="flex-grow px-4 py-2 rounded-xl border-2 border-slate-100 bg-slate-50 text-sm outline-none focus:border-indigo-400">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                <template x-for="(label, key) in {
                    habitats_agropecuario: 'i. ¿Hábitat agropecuario?',
                    zonas_urbanas: 'ii. ¿Zonas urbanas?',
                    vegetacion_secundaria_check: 'iii. ¿Vegetación secundaria?'
                }">
                    <div class="flex flex-col space-y-3">
                        <span class="text-[9px] font-black text-slate-400 uppercase" x-text="label"></span>
                        <div class="flex space-x-4">
                            <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" :name="key" value="si" x-model="form[key]" class="w-4 h-4 text-indigo-600 border-slate-300"><span class="text-[10px] font-bold">Sí</span></label>
                            <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" :name="key" value="no" x-model="form[key]" class="w-4 h-4 text-rose-500 border-slate-300"><span class="text-[10px] font-bold">No</span></label>
                        </div>
                    </div>
                </template>
            </div>
        </div>
        <div class="space-y-4">
            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">d) Tipo de vegetación secundaria (vocabulario controlado)</label>
            <input type="text" x-model="form.tipo_vegetacion_secundaria" class="w-full px-4 py-3 rounded-xl border-2 border-slate-100 bg-slate-50 text-sm outline-none focus:border-indigo-400">
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">17. Intervalo altitudinal</h3>
            <div class="flex items-center gap-4">
                <div class="flex-grow"><label class="text-[9px] font-bold text-slate-400 uppercase block mb-1">de</label><input type="number" x-model="form.altitud_de" class="w-full px-3 py-2 rounded-lg border-2 border-slate-50 bg-slate-50 text-sm outline-none"></div>
                <div class="flex-grow"><label class="text-[9px] font-bold text-slate-400 uppercase block mb-1">a</label><input type="number" x-model="form.altitud_a" class="w-full px-3 py-2 rounded-lg border-2 border-slate-50 bg-slate-50 text-sm outline-none"></div>
                <div class="flex-grow"><label class="text-[9px] font-bold text-slate-400 uppercase block mb-1">Promedio</label><input type="number" x-model="form.altitud_prom" class="w-full px-3 py-2 rounded-lg border-2 border-slate-50 bg-slate-50 text-sm outline-none"></div>
            </div>
            <textarea x-model="form.altitud_info" placeholder="Recuadro de información adicional..." class="w-full rounded-xl border-2 border-slate-50 p-3 text-xs bg-slate-50 outline-none min-h-[60px]"></textarea>
        </div>
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">18. Clima</h3>
            <input type="text" x-model="form.clima_tipo" class="w-full px-4 py-2 rounded-xl border-2 border-slate-50 bg-slate-50 text-sm outline-none">
            <textarea x-model="form.clima_info" placeholder="Recuadro de información adicional..." class="w-full rounded-xl border-2 border-slate-50 p-3 text-xs bg-slate-50 outline-none min-h-[60px]"></textarea>
        </div>
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">19. Intervalo de temperatura</h3>
            <div class="flex items-center gap-3">
                <div class="relative flex-grow"><span class="absolute right-3 top-7 text-[10px] font-bold text-slate-400">°C</span><label class="text-[9px] font-bold text-slate-400 uppercase mb-1 block">de</label><input type="number" x-model="form.temp_de" class="w-full px-3 py-2 rounded-lg border-2 border-slate-50 bg-slate-50 text-sm outline-none"></div>
                <div class="relative flex-grow"><span class="absolute right-3 top-7 text-[10px] font-bold text-slate-400">°C</span><label class="text-[9px] font-bold text-slate-400 uppercase mb-1 block">a</label><input type="number" x-model="form.temp_a" class="w-full px-3 py-2 rounded-lg border-2 border-slate-50 bg-slate-50 text-sm outline-none"></div>
                <div class="relative flex-grow"><span class="absolute right-3 top-7 text-[10px] font-bold text-slate-400">°C</span><label class="text-[9px] font-bold text-slate-400 uppercase mb-1 block">Promedio</label><input type="number" x-model="form.temp_prom" class="w-full px-3 py-2 rounded-lg border-2 border-slate-50 bg-slate-50 text-sm outline-none"></div>
            </div>
            <textarea x-model="form.temp_info" placeholder="Recuadro de información adicional..." class="w-full rounded-xl border-2 border-slate-50 p-3 text-xs bg-slate-50 outline-none min-h-[60px]"></textarea>
        </div>
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">20. Intervalo de precipitación</h3>
            <div class="flex items-center gap-3">
                <div class="relative flex-grow"><span class="absolute right-3 top-7 text-[10px] font-bold text-slate-400">mm</span><label class="text-[9px] font-bold text-slate-400 uppercase mb-1 block">de</label><input type="number" x-model="form.prec_de" class="w-full px-3 py-2 rounded-lg border-2 border-slate-50 bg-slate-50 text-sm outline-none"></div>
                <div class="relative flex-grow"><span class="absolute right-3 top-7 text-[10px] font-bold text-slate-400">mm</span><label class="text-[9px] font-bold text-slate-400 uppercase mb-1 block">a</label><input type="number" x-model="form.prec_a" class="w-full px-3 py-2 rounded-lg border-2 border-slate-50 bg-slate-50 text-sm outline-none"></div>
                <div class="relative flex-grow"><span class="absolute right-3 top-7 text-[10px] font-bold text-slate-400">mm</span><label class="text-[9px] font-bold text-slate-400 uppercase mb-1 block">Promedio</label><input type="number" x-model="form.prec_prom" class="w-full px-3 py-2 rounded-lg border-2 border-slate-50 bg-slate-50 text-sm outline-none"></div>
            </div>
            <textarea x-model="form.prec_info" placeholder="Recuadro de información adicional..." class="w-full rounded-xl border-2 border-slate-50 p-3 text-xs bg-slate-50 outline-none min-h-[60px]"></textarea>
        </div>
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">21. Humedad relativa</h3>
            <div class="flex items-center gap-3">
                <div class="relative flex-grow"><span class="absolute right-3 top-7 text-[10px] font-bold text-slate-400">%</span><label class="text-[9px] font-bold text-slate-400 uppercase mb-1 block">de</label><input type="number" x-model="form.hum_de" class="w-full px-3 py-2 rounded-lg border-2 border-slate-50 bg-slate-50 text-sm outline-none"></div>
                <div class="relative flex-grow"><span class="absolute right-3 top-7 text-[10px] font-bold text-slate-400">%</span><label class="text-[9px] font-bold text-slate-400 uppercase mb-1 block">a</label><input type="number" x-model="form.hum_a" class="w-full px-3 py-2 rounded-lg border-2 border-slate-50 bg-slate-50 text-sm outline-none"></div>
                <div class="relative flex-grow"><span class="absolute right-3 top-7 text-[10px] font-bold text-slate-400">%</span><label class="text-[9px] font-bold text-slate-400 uppercase mb-1 block">Promedio</label><input type="number" x-model="form.hum_prom" class="w-full px-3 py-2 rounded-lg border-2 border-slate-50 bg-slate-50 text-sm outline-none"></div>
            </div>
            <textarea x-model="form.hum_info" placeholder="Recuadro de información adicional..." class="w-full rounded-xl border-2 border-slate-50 p-3 text-xs bg-slate-50 outline-none min-h-[60px]"></textarea>
        </div>
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">22. Tipo de suelo</h3>
            <input type="text" x-model="form.suelo_tipo" placeholder="Vocabulario controlado..." class="w-full px-4 py-2 rounded-xl border-2 border-slate-50 bg-slate-50 text-sm outline-none focus:border-indigo-400 shadow-inner">
            <textarea x-model="form.suelo_info" placeholder="Recuadro de información adicional..." class="w-full rounded-xl border-2 border-slate-50 p-3 text-xs bg-slate-50 outline-none min-h-[60px] shadow-inner"></textarea>
        </div>
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6 col-span-1 lg:col-span-2">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">23. Geoforma</h3>
            <input type="text" x-model="form.geoforma_tipo" class="w-full px-4 py-3 rounded-xl border-2 border-slate-50 bg-slate-50 text-sm outline-none focus:border-indigo-400 shadow-inner">
            <textarea x-model="form.geoforma_info" placeholder="Recuadro de información adicional..." class="w-full rounded-xl border-2 border-slate-50 p-3 text-xs bg-slate-50 outline-none min-h-[80px] shadow-inner"></textarea>
        </div>
    </div>

    <div class="mt-16 flex justify-between items-center pb-24">
        <button type="button" @click="step = 2" class="group flex items-center px-10 py-5 bg-slate-200 text-slate-600 rounded-full font-black text-xs uppercase tracking-[0.3em] shadow-xl hover:bg-slate-300 transition-all">Anterior</button>
        <button type="button" @click="avanzarSeccion()" class="group flex items-center px-12 py-5 bg-indigo-600 text-white rounded-full font-black text-xs uppercase tracking-[0.3em] shadow-2xl hover:bg-indigo-700 transition-all">Siguiente</button>
    </div>
</div>
