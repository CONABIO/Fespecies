<div x-show="step === 3" x-cloak x-transition:enter="transition ease-out duration-300" class="max-w-3xl mx-auto space-y-8">
    <h2 class="text-[30px] font-black text-slate-800  tracking-tight" style="margin-top: 35px"> III. Tipo de ambiente en donde se desarrolla la especie</h2>

    <!-- 1. TIPO DE AMBIENTE -->
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-8">
        <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">
            <span class="mr-4">1. Tipo de Ambiente</span>
            <div class="h-px bg-slate-100 flex-grow"></div>
        </h3>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 pb-6 border-b border-slate-50">
            <label class="flex items-center space-x-3 cursor-pointer group p-3 rounded-xl hover:bg-slate-50 transition-colors">
                <input type="radio" name="tipo_ambiente" value="Ambiente terrestre" x-model="form.tipoAmbiente" @change="form.ecosistemas = []" class="w-5 h-5 text-indigo-600 border-slate-300">
                <span class="text-[16px] font-black text-slate-700 ">A. Ambiente terrestre</span>
            </label>
            <label class="flex items-center space-x-3 cursor-pointer group p-3 rounded-xl hover:bg-slate-50 transition-colors">
                <input type="radio" name="tipo_ambiente" value="Ambiente acuático" x-model="form.tipoAmbiente" @change="form.ecosistemas = []" class="w-5 h-5 text-indigo-600 border-slate-300">
                <span class="text-[16px] font-black text-slate-700">B. Ambiente marino </span>
            </label>
            <label class="flex items-center space-x-3 cursor-pointer group p-3 rounded-xl hover:bg-slate-50 transition-colors">
                <input type="radio" name="tipo_ambiente" value="Ambiente terrestre-acuático" x-model="form.tipoAmbiente" @change="form.ecosistemas = []" class="w-5 h-5 text-indigo-600 border-slate-300">
                <span class="text-[16px] font-black text-slate-700 ">C. Ambiente epicontinental</span>
            </label>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <!-- Ecorregiones terrestres -->
            <div x-show="form.tipoAmbiente === 'Ambiente terrestre' || form.tipoAmbiente === 'Ambiente terrestre-acuático'" x-transition class="space-y-4" x-data="{ openEco: false, filterEco: '' }">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center"> a) Ecorregiones terrestres </label>
                <div class="relative">
                    <div @click="openEco = !openEco" class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 cursor-pointer shadow-inner hover:border-indigo-300">
                        <template x-if="form.ecorregiones_terrestres.length === 0"><span class="text-xs text-slate-400 p-2 font-black"> SELECCIONAR...</span></template>
                        <template x-for="idSel in form.ecorregiones_terrestres" :key="idSel">
                            <div class="bg-indigo-600 text-white text-[10px] font-black px-4 py-1.5 rounded-xl flex items-center">
                                <span x-text="ecorregionOptions.find(o => o.ecorregionId == idSel)?.descripcion"></span>
                                <button type="button" @click.stop="form.ecorregiones_terrestres = form.ecorregiones_terrestres.filter(i => i != idSel)" class="ml-2 hover:text-rose-300">✕</button>
                            </div>
                        </template>
                    </div>
                    <div x-show="openEco" @click.away="openEco = false" class="absolute z-[110] w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden max-h-60 overflow-y-auto">
                        <template x-for="item in ecorregionOptions" :key="item.ecorregionId">
                            <div @click="if(!form.ecorregiones_terrestres.includes(item.ecorregionId)) form.ecorregiones_terrestres.push(item.ecorregionId); openEco = false;" class="px-5 py-3 text-xs hover:bg-indigo-50 cursor-pointer border-b">
                                <span x-text="item.descripcion"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Ecorregiones marinas -->
            <div x-show="form.tipoAmbiente === 'Ambiente acuático'" x-transition class="space-y-4" x-data="{ openEcoM: false, filterEcoM: '' }">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center"> a) Ecorregiones marinas </label>
                <div class="relative">
                    <div @click="openEcoM = !openEcoM" class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 cursor-pointer shadow-inner">
                        <template x-if="form.ecorregiones_marinas_ids.length === 0"><span class="text-xs text-slate-400 p-2 font-black"> SELECCIONAR... </span></template>
                        <template x-for="idSel in form.ecorregiones_marinas_ids" :key="idSel">
                            <div class="bg-indigo-600 text-white text-[10px] font-black px-4 py-1.5 rounded-xl flex items-center shadow-md">
                                <span x-text="marinasOptions.find(o => o.idopcion == idSel)?.descn1"></span>
                                <button type="button" @click.stop="form.ecorregiones_marinas_ids = form.ecorregiones_marinas_ids.filter(i => i != idSel)" class="ml-2 hover:text-rose-300">✕</button>
                            </div>
                        </template>
                    </div>
                    <div x-show="openEcoM" @click.away="openEcoM = false" class="absolute z-[110] w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden max-h-60 overflow-y-auto">
                        <template x-for="item in marinasOptions" :key="item.idopcion">
                            <div @click="if(!form.ecorregiones_marinas_ids.includes(item.idopcion)) form.ecorregiones_marinas_ids.push(item.idopcion); openEcoM = false;" class="px-5 py-3 text-xs hover:bg-indigo-50 cursor-pointer border-b">
                                <span x-text="item.descn1"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Ecosistemas -->
            <div x-show="form.tipoAmbiente" x-transition class="space-y-4" x-data="{ openEcoS: false, filterEcoS: '' }">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center"> b) Ecosistemas </label>
                <div class="relative">
                    <div @click="openEcoS = !openEcoS" class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 cursor-pointer shadow-inner">
                        <template x-if="form.ecosistemas.length === 0"><span class="text-xs text-slate-400 p-2 font-black"> SELECCIONAR...</span></template>
                        <template x-for="idSel in form.ecosistemas" :key="idSel">
                            <div class="bg-indigo-600 text-white text-[10px] font-black px-4 py-1.5 rounded-xl flex items-center shadow-md">
                                <span x-text="ecosistemaOptions.find(o => o.ecosistemaid == idSel)?.tipoecosistema"></span>
                                <button type="button" @click.stop="form.ecosistemas = form.ecosistemas.filter(i => i != idSel)" class="ml-2 hover:text-rose-300">✕</button>
                            </div>
                        </template>
                    </div>
                    <div x-show="openEcoS" @click.away="openEcoS = false" class="absolute z-[110] w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden max-h-60 overflow-y-auto">
                        <template x-for="item in ecosistemaOptions.filter(o => {
                            const grupoA = ['Selvas húmedas', 'Selvas secas', 'Bosques mesófilos de montaña', 'Bosques templados de coníferas y latifoliadas', 'Matorrales xerófilos', 'Pastizales'];
                            const grupoB = ['Fondos blandos', 'Lechos de pastos marinos', 'Fondos duros o rocosos', 'Lechos de rodolitos', 'Arrecifes coralinos', 'Bosques de macroalgas', 'Regiones mesofóticas', 'Ambientes pelágicos', 'Fosas y llanuras abisales'];
                            const grupoC = ['Humedales', 'Manglares', 'Marismas', 'Estuarios y lagunas costeras', 'Playas arenosas y zonas rocosas intermareales'];
                            if (form.tipoAmbiente === 'Ambiente terrestre') return grupoA.includes(o.tipoecosistema);
                            if (form.tipoAmbiente === 'Ambiente acuático') return grupoB.includes(o.tipoecosistema);
                            if (form.tipoAmbiente === 'Ambiente terrestre-acuático') return grupoC.includes(o.tipoecosistema);
                            return false;
                        })" :key="item.ecosistemaid">
                            <div @click="if(!form.ecosistemas.includes(item.ecosistemaid)) form.ecosistemas.push(item.ecosistemaid); openEcoS = false;" class="px-5 py-3 text-xs hover:bg-indigo-50 cursor-pointer border-b">
                                <span x-text="item.tipoecosistema"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div x-show="form.tipoAmbiente" class="pt-2">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Información adicional</label>
                <div class="mt-2">
                    <textarea id="ecorregiones_info_adicional_editor" class="w-full rounded-2xl border-2 border-slate-100 p-4 text-xs bg-slate-50 outline-none focus:border-indigo-400 min-h-[80px] shadow-inner"></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. TIPO DE VEGETACIÓN -->
    <div x-show="form.tipoAmbiente === 'Ambiente terrestre' || form.tipoAmbiente === 'Ambiente terrestre-acuático'" x-transition class="space-y-8">
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-10">
            <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">2. Tipo de Vegetación</h3>

            <div class="space-y-4">
                <div class="flex flex-col" x-data="{ openVeg: false, filterVeg: '', get agrupadosVeg() { return this.vegetacionOptions.reduce((acc, obj) => { const key = obj.descripcionVegetacion; if (!acc[key]) acc[key] = []; acc[key].push(obj); return acc; }, {}); } }">
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">a)Indicar el o los tipos de vegetación en los que se desarrolla la especie </label>
                    <div class="relative">
                        <div @click="openVeg = !openVeg" class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 cursor-pointer hover:border-indigo-300 shadow-inner">
                            <template x-if="form.tipo_vegetacion_a.length === 0"><span class="text-xs text-slate-400 p-2 font-black">SELECCIONAR...</span></template>
                            <template x-for="idSel in form.tipo_vegetacion_a" :key="idSel">
                                <div class="bg-indigo-600 text-white text-[10px] font-black px-4 py-1.5 rounded-xl flex items-center shadow-md">
                                    <span x-text="vegetacionOptions.find(o => o.vegetacionId == idSel)?.descripcionSubVegetacion"></span>
                                    <button type="button" @click.stop="form.tipo_vegetacion_a = form.tipo_vegetacion_a.filter(i => i != idSel)" class="ml-2 hover:text-rose-300">✕</button>
                                </div>
                            </template>
                        </div>
                        <div x-show="openVeg" @click.away="openVeg = false" class="absolute z-[150] w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden max-h-80 overflow-y-auto">
                            <template x-for="(items, categoria) in agrupadosVeg" :key="categoria">
                                <div>
                                    <div class="bg-slate-100 px-4 py-2 text-[10px] font-black text-slate-900 sticky top-0" x-text="categoria"></div>
                                    <template x-for="item in items" :key="item.vegetacionId">
                                        <div @click="if(!form.tipo_vegetacion_a.includes(item.vegetacionId)) form.tipo_vegetacion_a.push(item.vegetacionId); openVeg = false;" class="px-8 py-2 text-xs hover:bg-indigo-50 cursor-pointer">
                                            <span x-text="item.descripcionSubVegetacion"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Información adicional</label>
                        <textarea id="vegetacion_info_adicional_a_editor" class="w-full rounded-xl border-2 border-slate-100 p-3 text-xs bg-slate-50 outline-none focus:border-indigo-400 min-h-[60px] shadow-inner"></textarea>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">b) Mencionar las especies asociadas</label>
                <textarea id="especies_asociadas_info_editor" class="w-full rounded-xl border-2 border-slate-100 p-3 text-xs bg-slate-50 outline-none focus:border-indigo-400 min-h-[80px] shadow-inner"></textarea>
            </div>

            <!-- c) Hábitats antrópicos -->
            <div class="space-y-6" x-data="{ openAntro: false }">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">c) Hábitats antrópicos</label>
                <div class="relative mb-6">
                    <div @click="openAntro = !openAntro" class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 cursor-pointer shadow-inner hover:border-indigo-300">
                        <template x-if="form.habitats_antropicos.length === 0"><span class="text-xs text-slate-400 p-2 font-black">Indicar si la especie se encuentra presente</span></template>
                        <template x-for="idSel in form.habitats_antropicos" :key="idSel">
                            <div class="bg-indigo-600 text-white text-[10px] font-black px-4 py-1.5 rounded-xl flex items-center shadow-md">
                                <span x-text="habitatsAntropicosOptions.find(o => o.idopcion == idSel)?.descn1"></span>
                                <button type="button" @click.stop="form.habitats_antropicos = form.habitats_antropicos.filter(i => i != idSel)" class="ml-2 hover:text-rose-300">✕</button>
                            </div>
                        </template>
                    </div>
                    <div x-show="openAntro" @click.away="openAntro = false" class="absolute z-[100] w-full mt-2 bg-white border rounded-2xl shadow-xl max-h-60 overflow-y-auto">
                        <template x-for="item in habitatsAntropicosOptions" :key="item.idopcion">
                            <div @click="if(!form.habitats_antropicos.includes(item.idopcion)) form.habitats_antropicos.push(item.idopcion); openAntro = false;" class="px-5 py-3 text-xs hover:bg-indigo-50 cursor-pointer border-b">
                                <span x-text="item.descn1"></span>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-slate-50 p-6 rounded-2xl border border-slate-100">
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

            <!-- d) Tipo de vegetación secundaria -->
            <div class="space-y-4" x-data="{ openVegSec: false }">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">d) Tipo de vegetación secundaria</label>
                <div class="relative">
                    <div @click="openVegSec = !openVegSec" class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 cursor-pointer shadow-inner">
                        <template x-if="form.vegetacion_secundaria.length === 0"><span class="text-xs text-slate-400 p-2 font-black">SELECCIONAR...</span></template>
                        <template x-for="idSel in form.vegetacion_secundaria" :key="idSel">
                            <div class="bg-indigo-600 text-white text-[10px] font-black px-4 py-1.5 rounded-xl flex items-center shadow-md">
                                <span x-text="vegSecundariaOptions.find(o => o.idopcion == idSel)?.descn1"></span>
                                <button type="button" @click.stop="form.vegetacion_secundaria = form.vegetacion_secundaria.filter(i => i != idSel)" class="ml-2">✕</button>
                            </div>
                        </template>
                    </div>
                    <div x-show="openVegSec" @click.away="openVegSec = false" class="absolute z-[100] w-full mt-2 bg-white border rounded-2xl shadow-xl max-h-60 overflow-y-auto">
                        <template x-for="item in vegSecundariaOptions" :key="item.idopcion">
                            <div @click="if(!form.vegetacion_secundaria.includes(item.idopcion)) form.vegetacion_secundaria.push(item.idopcion); openVegSec = false;" class="px-5 py-3 text-xs hover:bg-indigo-50 cursor-pointer border-b">
                                <span x-text="item.descn1"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- 3. Intervalo altitudinal -->
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex flex-col h-full hover:shadow-md transition-all duration-300">
                <h3 class="text-[22px] font-black text-slate-800 tracking-tight mb-6">3. Intervalo altitudinal</h3>
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="space-y-1">
                        <label class="text-[11px] font-black text-slate-400  tracking-wider ml-1">de (m)</label>
                        <input type="number" x-model="form.intervaloaltitudinalinicial" class="w-full px-4 py-3 rounded-2xl border-2 border-slate-50 bg-slate-50 text-sm font-bold outline-none focus:border-indigo-400 transition-all shadow-inner">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-black text-slate-400 tracking-wider ml-1">a (m)</label>
                        <input type="number" x-model="form.intervaloaltitudinalfinal" class="w-full px-4 py-3 rounded-2xl border-2 border-slate-50 bg-slate-50 text-sm font-bold outline-none focus:border-indigo-400 transition-all shadow-inner">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-black text-indigo-400 tracking-wider ml-1">Promedio</label>
                        <div class="w-full px-4 py-3 rounded-2xl bg-indigo-50 border-2 border-indigo-50 text-sm font-black text-indigo-600 flex items-center justify-center">
                            <span x-text="form.altitud_prom = calcularPromedio(form.intervaloaltitudinalinicial, form.intervaloaltitudinalfinal)"></span>
                        </div>
                    </div>
                </div>
                <div class="flex-grow">
                    <label class="text-[11px] font-black text-slate-400  tracking-wider mb-2 block ml-1">Información adicional altitud</label>
                    <textarea id="tiny-altitud" class="w-full rounded-xl border-2 border-slate-50 p-3 text-xs bg-slate-50 outline-none min-h-[80px] shadow-inner"></textarea>
                </div>
            </div>

            <!-- 4. Clima -->
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex flex-col h-full hover:shadow-md transition-all duration-300" x-data="{ openClima: false }">
                <h3 class="text-[22px] font-black text-slate-800 tracking-tight mb-6">4. Clima</h3>
                <div class="mb-6">
                    <div class="relative">
                        <div @click="openClima = !openClima" class="min-h-[55px] p-3 rounded-2xl border-2 border-slate-50 bg-slate-50 flex flex-wrap gap-2 cursor-pointer shadow-inner hover:border-indigo-300 transition-all">
                            <template x-if="form.clima_tipo.length === 0"><span class="text-xs text-slate-400 p-2 font-bold uppercase tracking-widest">SELECCIONAR...</span></template>
                            <template x-for="idSel in form.clima_tipo" :key="idSel">
                                <div class="bg-indigo-600 text-white text-[10px] font-black px-4 py-2 rounded-xl flex items-center shadow-sm">
                                    <span x-text="climaOptions.find(o => o.idopcion == idSel)?.descn2"></span>
                                    <button type="button" @click.stop="form.clima_tipo = form.clima_tipo.filter(i => i != idSel)" class="ml-2">✕</button>
                                </div>
                            </template>
                        </div>
                        <div x-show="openClima" @click.away="openClima = false" class="absolute z-[100] w-full mt-2 bg-white border rounded-2xl shadow-2xl max-h-60 overflow-y-auto">
                            <template x-for="item in climaOptions" :key="item.idopcion">
                                <div @click="if(!form.clima_tipo.includes(item.idopcion)) form.clima_tipo.push(item.idopcion); openClima = false;" class="px-5 py-3 text-xs hover:bg-indigo-50 cursor-pointer border-b">
                                    <span x-text="item.descn2"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="flex-grow">
                    <label class="text-[11px] font-black text-slate-400  tracking-wider mb-2 block ml-1">Información adicional clima</label>
                    <textarea id="tiny-clima" class="w-full rounded-xl border-2 border-slate-50 p-3 text-xs bg-slate-50 outline-none min-h-[80px] shadow-inner"></textarea>
                </div>
            </div>

            <!-- 5, 6 y 7 -->
            <template x-for="param in [
                { id: 'temperatura', label: '5. Intervalo de temperatura', unit: '°C', tiny: 'tiny-temperatura', init: 'temperaturainicial', final: 'temperaturafinal' },
                { id: 'precipitacion', label: '6. Intervalo de precipitación', unit: 'mm', tiny: 'tiny-precipitacion', init: 'precipitacioninicial', final: 'precipitacionfinal' },
                { id: 'humedad', label: '7. Intervalo de humedad relativa', unit: '%', tiny: 'tiny-humedad', init: 'humedadinicial', final: 'humedadfinal' }
            ]">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex flex-col h-full hover:shadow-md transition-all duration-300">
                    <h3 class="text-[22px] font-black text-slate-800 tracking-tight mb-6" x-text="param.label"></h3>
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div class="relative">
                            <label class="text-[11px] font-black text-slate-400  mb-1 block ml-1">De</label>
                            <div class="relative">
                                <input type="number" x-model="form[param.init]" class="w-full px-4 py-3 rounded-2xl border-2 border-slate-50 bg-slate-50 text-sm font-bold shadow-inner">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[12px] font-black text-slate-300" x-text="param.unit"></span>
                            </div>
                        </div>
                        <div class="relative">
                            <label class="text-[11px] font-black text-slate-400  mb-1 block ml-1">A</label>
                            <div class="relative">
                                <input type="number" x-model="form[param.final]" class="w-full px-4 py-3 rounded-2xl border-2 border-slate-50 bg-slate-50 text-sm font-bold shadow-inner">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[12px] font-black text-slate-300" x-text="param.unit"></span>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow">
                        <textarea :id="param.tiny" class="w-full rounded-xl border-2 border-slate-50 p-3 text-xs bg-slate-50 outline-none min-h-[80px] shadow-inner"></textarea>
                    </div>
                </div>
            </template>

            <!-- 8. Tipo de Suelo -->
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex flex-col h-full hover:shadow-md transition-all duration-300" x-data="{ openSuelo: false }">
                <h3 class="text-[22px] font-black text-slate-800 tracking-tight mb-6">8. Tipo de Suelo</h3>
                <div class="relative mb-6">
                    <div @click="openSuelo = !openSuelo" class="min-h-[55px] p-3 rounded-2xl border-2 border-slate-50 bg-slate-50 flex flex-wrap gap-2 cursor-pointer shadow-inner hover:border-indigo-300">
                        <template x-if="form.suelo_tipo.length === 0"><span class="text-xs text-slate-400 p-2 font-bold tracking-widest">SELECCIONAR...</span></template>
                        <template x-for="idSel in form.suelo_tipo" :key="idSel">
                            <div class="bg-indigo-600 text-white text-[10px] font-black px-4 py-2 rounded-xl flex items-center shadow-sm">
                                <span x-text="suelosOptions.find(o => o.idopcion == idSel)?.descn1"></span>
                                <button type="button" @click.stop="form.suelo_tipo = form.suelo_tipo.filter(i => i != idSel)" class="ml-2">✕</button>
                            </div>
                        </template>
                    </div>
                    <div x-show="openSuelo" @click.away="openSuelo = false" class="absolute z-[100] w-full mt-2 bg-white border rounded-2xl shadow-xl max-h-60 overflow-y-auto">
                        <template x-for="item in suelosOptions" :key="item.idopcion">
                            <div @click="if(!form.suelo_tipo.includes(item.idopcion)) form.suelo_tipo.push(item.idopcion); openSuelo = false;" class="px-5 py-3 text-xs hover:bg-indigo-50 cursor-pointer border-b">
                                <span x-text="item.descn1"></span>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="flex-grow">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2 block ml-1">Información adicional suelo</label>
                    <textarea id="tiny-suelo" class="w-full rounded-xl border-2 border-slate-50 p-3 text-xs bg-slate-50 outline-none min-h-[80px] shadow-inner"></textarea>
                </div>
            </div>

            <!-- 9. Geoforma -->
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 space-y-6 col-span-1 lg:col-span-2" x-data="{ openGeo: false }">
                <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">9. Geoforma </h3>
                <div class="relative">
                    <div @click="openGeo = !openGeo" class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 cursor-pointer hover:border-indigo-300 transition-all shadow-inner">
                        <template x-if="form.geoforma_tipo.length === 0"><span class="text-xs text-slate-400 p-2 font-black tracking-widest">SELECCIONAR...</span></template>
                        <template x-for="idSel in form.geoforma_tipo" :key="idSel">
                            <div class="bg-indigo-600 text-white text-[10px] font-black px-4 py-1.5 rounded-xl flex items-center shadow-md">
                                <span x-text="geoformaOptions.find(o => o.idopcion == idSel)?.descn1"></span>
                                <button type="button" @click.stop="form.geoforma_tipo = form.geoforma_tipo.filter(i => i != idSel)" class="ml-2 hover:text-rose-300">✕</button>
                            </div>
                        </template>
                    </div>
                    <div x-show="openGeo" @click.away="openGeo = false" class="absolute z-[110] w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden max-h-60 overflow-y-auto">
                        <template x-for="item in geoformaOptions" :key="item.idopcion">
                            <div @click="if(!form.geoforma_tipo.includes(item.idopcion)) form.geoforma_tipo.push(item.idopcion); openGeo = false;" class="px-5 py-3 text-xs hover:bg-indigo-50 cursor-pointer border-b">
                                <span x-text="item.descn1"></span>
                            </div>
                        </template>
                    </div>
                </div>
                <textarea id="tiny-geoforma" class="w-full rounded-xl border-2 border-slate-50 p-3 text-xs bg-slate-50 outline-none focus:border-indigo-400 min-h-[80px] shadow-inner"></textarea>
            </div>
        </div>
    </div>

    <!-- SECCIÓN AMBIENTE ACUÁTICO -->
    <div x-show="form.tipoAmbiente === 'Ambiente acuático'" x-transition class="space-y-8">
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-8">
            <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center"><span class="mr-4">2. Tipo de hábitat marino</span><div class="h-px bg-slate-100 flex-grow"></div></h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Vertical -->
                <div class="space-y-2" x-data="{ open: false, search: '', options: ['Bentónico', 'Demersal', 'Epipelágico', 'Mesopelágico', 'Bathipelágico'], select(option) { form.habitat_marino_vertical = option; this.open = false; this.search = ''; } }">
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">a) Vertical:</label>
                    <div class="relative">
                        <div @click="open = !open" class="w-full min-h-[50px] p-2 rounded-xl bg-slate-50 border-2 border-slate-100 cursor-pointer shadow-inner flex items-center gap-2">
                            <template x-if="form.habitat_marino_vertical">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-600 text-white">
                                    <span x-text="form.habitat_marino_vertical"></span>
                                    <button type="button" @click.stop="form.habitat_marino_vertical = ''" class="ml-2 hover:text-indigo-200">✕</button>
                                </span>
                            </template>
                            <input type="text" x-model="search" placeholder="Buscar..." class="flex-grow bg-transparent border-none outline-none text-sm px-2">
                        </div>
                        <div x-show="open" @click.away="open = false" class="absolute z-50 w-full mt-2 bg-white rounded-xl shadow-xl border overflow-hidden max-h-60 overflow-y-auto">
                            <template x-for="option in options.filter(o => o.toLowerCase().includes(search.toLowerCase()))" :key="option">
                                <div @click="select(option)" class="px-4 py-3 text-sm hover:bg-indigo-50 cursor-pointer border-b">
                                    <span x-text="option"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <!-- Horizontal -->
                <div class="space-y-2" x-data="{ open: false, search: '', options: ['Asociado a arrecifes', 'Costero', 'Plataforma continental', 'Talud continental', 'Oceánico'], select(option) { form.habitat_marino_horizontal = option; this.open = false; this.search = ''; } }">
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">b) Horizontal:</label>
                    <div class="relative">
                        <div @click="open = !open" class="w-full min-h-[50px] p-2 rounded-xl bg-slate-50 border-2 border-slate-100 cursor-pointer shadow-inner flex items-center gap-2">
                            <template x-if="form.habitat_marino_horizontal">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-600 text-white">
                                    <span x-text="form.habitat_marino_horizontal"></span>
                                    <button type="button" @click.stop="form.habitat_marino_horizontal = ''" class="ml-2 hover:text-indigo-200">✕</button>
                                </span>
                            </template>
                            <input type="text" x-model="search" placeholder="Buscar..." class="flex-grow bg-transparent border-none outline-none text-sm px-2">
                        </div>
                        <div x-show="open" @click.away="open = false" class="absolute z-50 w-full mt-2 bg-white rounded-xl shadow-xl border overflow-hidden max-h-60 overflow-y-auto">
                            <template x-for="option in options.filter(o => o.toLowerCase().includes(search.toLowerCase()))" :key="option">
                                <div @click="select(option)" class="px-4 py-3 text-sm hover:bg-indigo-50 cursor-pointer border-b">
                                    <span x-text="option"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-y-4">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Informacion adicional</label>
                <textarea id="tiny-marino-vh" class="w-full rounded-2xl border-2 border-slate-100 p-4 text-xs bg-slate-50 outline-none focus:border-indigo-400 min-h-[100px] shadow-inner"></textarea>
            </div>
            <div class="space-y-4">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">c) Mencionar las especies asociadas</label>
                <textarea id="tiny-marino-especies" class="w-full rounded-2xl border-2 border-slate-100 p-4 text-xs bg-slate-50 outline-none focus:border-indigo-400 min-h-[100px] shadow-inner"></textarea>
            </div>
            <div class="space-y-6">
                <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                        <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">d) Indicar si hay disturbios antrópicos</label>
                        <div class="flex bg-slate-200/50 p-1 rounded-xl border border-slate-200">
                            <label class="flex items-center px-6 py-2 rounded-lg cursor-pointer transition-all duration-300" :class="form.habitat_marino_disturbiosAntropicos == 'SÍ' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-600'"><input type="radio" x-model="form.habitat_marino_disturbiosAntropicos" value="SÍ" class="hidden"><span class="text-[10px] font-black">SÍ</span></label>
                            <label class="flex items-center px-6 py-2 rounded-lg cursor-pointer transition-all duration-300" :class="form.habitat_marino_disturbiosAntropicos == 'NO' ? 'bg-white text-rose-500 shadow-sm' : 'text-slate-400 hover:text-slate-600'"><input type="radio" x-model="form.habitat_marino_disturbiosAntropicos" value="NO" class="hidden"><span class="text-[10px] font-black">NO</span></label>
                        </div>
                    </div>
                    <div x-show="form.habitat_marino_disturbiosAntropicos == 'SÍ'" x-transition class="mt-4">
                        <textarea id="tiny-marino-disturbios" class="w-full rounded-xl border-2 border-white p-4 text-xs bg-white outline-none focus:border-indigo-400 min-h-[100px] shadow-sm"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- INTERVALO BATIMÉTRICO Y MAREAS -->
    <div x-show="form.tipoAmbiente === 'Ambiente acuático' || form.tipoAmbiente === 'Ambiente terrestre-acuático'" x-transition class="space-y-8">
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 space-y-4">
            <h3 class="text-[22px] font-bold text-slate-700 flex items-center">
                <span x-text="form.tipoAmbiente === 'Ambiente acuático' ? '3. Intervalo batimétrico' : '10. Intervalo batimétrico'"></span>
            </h3>
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="flex flex-col gap-3 w-full lg:w-64 flex-shrink-0">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[14px] font-bold text-slate-700">De (m)</label>
                            <input type="number" x-model="form.interbatimetricoinicial"
                                @input="form.interbatimetricopromedio = calcularPromedio(form.interbatimetricoinicial, form.interbatimetricofinal)"
                                @blur="validarRango('interbatimetricoinicial', 'interbatimetricofinal')"
                                class="w-full px-3 py-2 rounded-xl bg-slate-50 border-2 border-slate-50 outline-none">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[14px] font-bold text-slate-700">A (m)</label>
                            <input type="number" x-model="form.interbatimetricofinal"
                                @input="form.interbatimetricopromedio = calcularPromedio(form.interbatimetricoinicial, form.interbatimetricofinal)"
                                @blur="validarRango('interbatimetricoinicial', 'interbatimetricofinal')"
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
                    <textarea id="tiny-batimetria" class="w-full flex-grow rounded-2xl border-2 border-slate-50 p-3 bg-slate-50 outline-none min-h-[115px] shadow-inner"></textarea>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 space-y-4">
            <h3 class="text-[22px] font-bold text-slate-700 flex items-center">
                <span x-text="form.tipoAmbiente === 'Ambiente acuático' ? '4. Amplitud de mareas' : '11. Amplitud de mareas'"></span>
            </h3>
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="flex flex-col gap-3 w-full lg:w-64 flex-shrink-0">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="font-bold">De</label>
                            <input type="number" x-model="form.amplitudmareasinicial"
                                @input="form.amplitudmareaspromedio = calcularPromedio(form.amplitudmareasinicial, form.amplitudmareasfinal)"
                                @blur="validarRango('amplitudmareasinicial', 'amplitudmareasfinal')"
                                class="w-full px-3 py-2 rounded-xl bg-slate-50 border-2 border-slate-50 outline-none">
                        </div>
                        <div>
                            <label class="font-bold">A</label>
                            <input type="number" x-model="form.amplitudmareasfinal"
                                @input="form.amplitudmareaspromedio = calcularPromedio(form.amplitudmareasinicial, form.amplitudmareasfinal)"
                                @blur="validarRango('amplitudmareasinicial', 'amplitudmareasfinal')"
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
                    <textarea id="tiny-mareas" class="w-full flex-grow rounded-2xl border-2 border-slate-50 p-3 bg-slate-50 outline-none min-h-[110px] shadow-inner"></textarea>
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
                    { id_de: 'temeperaturainicial', id_a: 'temeperaturafinal', id_p: 'temeperaturapromedio', label: 'd) Temperatura', }
                ]">
                    <div class="p-6 bg-slate-50 rounded-2xl">
                        <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center" x-text="item.label"></label>
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-2 items-end">
                            <div>
                                <span class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">De</span>
                                <input type="number" :placeholder="item.placeholder" x-model="form[item.id_de]"
                                    @input="form[item.id_p] = calcularPromedio(form[item.id_de], form[item.id_a])"
                                    @blur="validarRango(item.id_de, item.id_a)"
                                    class="w-full px-4 py-3 rounded-xl bg-white border-2 border-slate-100 outline-none">
                            </div>
                            <div>
                                <span class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">A</span>
                                <input type="number" :placeholder="item.placeholder" x-model="form[item.id_a]"
                                    @input="form[item.id_p] = calcularPromedio(form[item.id_de], form[item.id_a])"
                                    @blur="validarRango(item.id_de, item.id_a)"
                                    class="w-full px-4 py-3 rounded-xl bg-white border-2 border-slate-100 outline-none">
                            </div>
                            <div>
                                <span class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Promedio</span>
                                <div class="w-full px-4 py-3 rounded-xl bg-white border-2 border-slate-200 font-bold h-[48px] flex items-center">
                                    <span x-text="form[item.id_p]"></span>
                                </div>
                            </div>
                            <template x-if="item.id_de === 'salinidadinicial'">
                                <div class="w-full"><span class="text-[14px] font-bold text-slate-400 block mb-1">Unidad</span><select x-model="form.unidadsalinidad" class="w-full px-4 py-3 rounded-xl bg-white border-2 border-slate-100 appearance-none outline-none"><option value="">Seleccione...</option><option value="Porcentaje(%)">Porcentaje (%)</option><option value="Partes por mil(ppt)">Partes por mil (ppt)</option><option value="Gramos por litro(g/L)">Gramos por litro (g/L)</option><option value="Unidades prácticas de salinidad (ups,psu)">ups, psuUnidades prácticas de salinidad (ups, psu)</option></select></div>
                            </template>
                        </div>
                    </div>
                </template>
                <div class="p-6 bg-slate-50 rounded-2xl"><label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">e) Corrientes</label>
                    <textarea id="tiny-corrientes" class="w-full rounded-xl border-2 border-slate-100 p-3 text-xs bg-white outline-none focus:border-indigo-400 min-h-[60px] shadow-inner"></textarea>
                </div>
                <div class="p-6 bg-slate-50 rounded-2xl"><label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Información adicional</label><textarea id="tiny-agua" class="w-full rounded-2xl border-2 border-slate-100 p-4 text-xs bg-white outline-none min-h-[80px] shadow-inner"></textarea></div>
            </div>
        </div>
    </div>
    <div class="mt-16 flex justify-between pb-24"></div>
</div>
