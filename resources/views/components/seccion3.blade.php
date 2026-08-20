<div x-show="step === 3" x-cloak x-transition:enter="transition ease-out duration-300" class="max-w-3xl mx-auto space-y-8">
    <h2 class="text-[30px] font-black text-slate-800  tracking-tight" style="margin-top: 35px"> III. Tipo de ambiente en donde se desarrolla la especie</h2>

    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-8">
        <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">
            <span class="mr-4">1. Tipo de Ambiente</span>
            <div class="h-px bg-slate-100 flex-grow"></div>
        </h3>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 pb-6 border-b border-slate-50">
            <label
                class="flex items-center space-x-3 cursor-pointer group p-3 rounded-xl hover:bg-slate-50 transition-colors">
                <input type="radio" name="tipo_ambiente" value="Ambiente terrestre" x-model="form.tipoAmbiente"
                    class="w-5 h-5 text-indigo-600 border-slate-300">
                <span class="text-[16px] font-black text-slate-700 ">A. Ambiente terrestre</span>
            </label>
            <label
                class="flex items-center space-x-3 cursor-pointer group p-3 rounded-xl hover:bg-slate-50 transition-colors">
                <input type="radio" name="tipo_ambiente" value="Ambiente terrestre-acuático"
                    x-model="form.tipoAmbiente" class="w-5 h-5 text-indigo-600 border-slate-300">
                <span class="text-[16px] font-black text-slate-700 ">C. Ambiente epicontinental</span>
            </label>
            <label
                class="flex items-center space-x-3 cursor-pointer group p-3 rounded-xl hover:bg-slate-50 transition-colors">
                <input type="radio" name="tipo_ambiente" value="Ambiente acuático" x-model="form.tipoAmbiente"
                    class="w-5 h-5 text-indigo-600 border-slate-300">
                <span class="text-[16px] font-black text-slate-700">B. Ambiente marino </span>
            </label>

        </div>

        <div class="grid grid-cols-1 gap-6">
            <div x-show="form.tipoAmbiente === 'Ambiente terrestre' || form.tipoAmbiente === 'Ambiente terrestre-acuático'"
                x-transition class="space-y-4" x-data="{ openEco: false, filterEco: '' }">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center"> a) Ecorregiones terrestres </label>
                <div class="relative">
                    <div @click="openEco = !openEco; if(openEco) $nextTick(() => $refs.inputBusquedaEco.focus())"
                        class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 cursor-pointer hover:border-indigo-300 shadow-inner">
                        <template x-if="form.ecorregiones_terrestres.length === 0">
                            <span class="text-xs text-slate-400 p-2 font-black"> SELECCIONAR ECORREGIONES...
                            </span>
                        </template>
                        <template x-for="idSel in form.ecorregiones_terrestres" :key="idSel">
                            <div
                                class="bg-indigo-600 text-white text-[10px] font-black px-4 py-1.5 rounded-xl flex items-center shadow-md">
                                <span x-text="ecorregionOptions.find(o => o.ecorregionId == idSel)?.descripcion"></span>
                                <button type="button"
                                    @click.stop="form.ecorregiones_terrestres = form.ecorregiones_terrestres.filter(i => i != idSel)"
                                    class="ml-2 hover:text-rose-300">✕</button>
                            </div>
                        </template>
                    </div>
                    <div x-show="openEco" @click.away="openEco = false" x-cloak
                        class="absolute z-[110] w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden">
                        <div class="p-2 border-b bg-slate-50">
                            <input type="text" x-model="filterEco" placeholder="Buscar ecorregión..."
                                x-ref="inputBusquedaEco"
                                class="w-full px-3 py-2 text-xs border rounded-lg outline-none focus:ring-2 focus:ring-indigo-400">
                        </div>
                        <div class="max-h-60 overflow-y-auto bg-white">
                            <template
                                x-for="item in ecorregionOptions.filter(i => i.descripcion.toLowerCase().includes(filterEco.toLowerCase()))"
                                :key="item.ecorregionId">
                                <div @click="let id = item.ecorregionId; if(!form.ecorregiones_terrestres.some(s => s == id)) { form.ecorregiones_terrestres = [...form.ecorregiones_terrestres, id]; } openEco = false; filterEco = '';"
                                    class="px-5 py-3 text-xs font-black text-slate-600 hover:bg-indigo-50 cursor-pointer border-b border-slate-50 flex justify-between items-center"
                                    :class="form.ecorregiones_terrestres.some(s => s == item.ecorregionId) ?
                                        'bg-indigo-50 text-indigo-700' : ''">
                                    <span x-text="item.descripcion"></span>
                                    <template x-if="form.ecorregiones_terrestres.some(s => s == item.ecorregionId)">
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="form.tipoAmbiente === 'Ambiente acuático'"
                x-transition class="space-y-4" x-data="{ openEcoM: false, filterEcoM: '' }">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center"> a) Ecorregiones
                    marinas </label>
                <div class="relative">
                    <div @click="openEcoM = !openEcoM; if(openEcoM) $nextTick(() => $refs.inputBusquedaEcoM.focus())"
                        class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 cursor-pointer hover:border-indigo-300 shadow-inner">
                        <template x-if="form.ecorregiones_marinas_ids.length === 0">
                            <span class="text-xs text-slate-400 p-2 font-black"> SELECCIONAR ECORREGIONES
                                MARINAS... </span>
                        </template>
                        <template x-for="idSel in form.ecorregiones_marinas_ids" :key="idSel">
                            <div
                                class="bg-indigo-600 text-white text-[10px] font-black px-4 py-1.5 rounded-xl flex items-center shadow-md">
                                <span x-text="marinasOptions.find(o => o.idopcion == idSel)?.descn1"></span>
                                <button type="button"
                                    @click.stop="form.ecorregiones_marinas_ids = form.ecorregiones_marinas_ids.filter(i => i != idSel)"
                                    class="ml-2 hover:text-rose-300">✕</button>
                            </div>
                        </template>
                    </div>
                    <div x-show="openEcoM" @click.away="openEcoM = false" x-cloak
                        class="absolute z-[110] w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden">
                        <div class="p-2 border-b bg-slate-50">
                            <input type="text" x-model="filterEcoM" placeholder="Buscar ecorregión marina..."
                                x-ref="inputBusquedaEcoM"
                                class="w-full px-3 py-2 text-xs border rounded-lg outline-none focus:ring-2 focus:ring-indigo-400">
                        </div>
                        <div class="max-h-60 overflow-y-auto bg-white">
                            <template
                                x-for="item in marinasOptions.filter(i => i.descn1.toLowerCase().includes(filterEcoM.toLowerCase()))"
                                :key="item.idopcion">
                                <div @click="if(!form.ecorregiones_marinas_ids.includes(item.idopcion)) { form.ecorregiones_marinas_ids = [...form.ecorregiones_marinas_ids, item.idopcion]; } openEcoM = false; filterEcoM = '';"
                                    class="px-5 py-3 text-xs font-black text-slate-600 hover:bg-indigo-50 cursor-pointer border-b border-slate-50 flex justify-between items-center"
                                    :class="form.ecorregiones_marinas_ids.includes(item.idopcion) ?
                                        'bg-indigo-50 text-indigo-700' : ''">
                                    <span x-text="item.descn1"></span>
                                    <template x-if="form.ecorregiones_marinas_ids.includes(item.idopcion)">
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="form.tipoAmbiente" x-transition class="space-y-4" x-data="{ openEcoS: false, filterEcoS: '' }">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center"> b) Ecosistemas
                </label>
                <div class="relative">
                    <div @click="openEcoS = !openEcoS; if(openEcoS) $nextTick(() => $refs.inputBusquedaEcoS.focus())"
                        class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 cursor-pointer hover:border-indigo-300 shadow-inner">
                        <template x-if="form.ecosistemas.length === 0">
                            <span class="text-xs text-slate-400 p-2 font-black"> SELECCIONAR ECOSISTEMAS...
                            </span>
                        </template>
                        <template x-for="idSel in form.ecosistemas" :key="idSel">
                            <div
                                class="bg-indigo-600 text-white text-[10px] font-black px-4 py-1.5 rounded-xl flex items-center shadow-md">
                                <span
                                    x-text="ecosistemaOptions.find(o => o.ecosistemaid == idSel)?.tipoecosistema"></span>
                                <button type="button"
                                    @click.stop="form.ecosistemas = form.ecosistemas.filter(i => i != idSel)"
                                    class="ml-2 hover:text-rose-300">✕</button>
                            </div>
                        </template>
                    </div>
                    <div x-show="openEcoS" @click.away="openEcoS = false" x-cloak
                        class="absolute z-[110] w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden">
                        <div class="p-2 border-b bg-slate-50">
                            <input type="text" x-model="filterEcoS" placeholder="Buscar ecosistema..."
                                x-ref="inputBusquedaEcoS"
                                class="w-full px-3 py-2 text-xs border rounded-lg outline-none focus:ring-2 focus:ring-indigo-400">
                        </div>
                        <div class="max-h-60 overflow-y-auto bg-white">
                            <template
                                x-for="item in ecosistemaOptions.filter(i => {const coincideBusqueda = i.tipoecosistema.toLowerCase().includes(filterEcoS.toLowerCase());if (!coincideBusqueda) return false;if (form.tipoAmbiente === 'Ambiente terrestre') { return i.ecosistemaid >= 1 && i.ecosistemaid <= 6;} else if (form.tipoAmbiente === 'Ambiente terrestre-acuático') { return i.ecosistemaid >= 7 && i.ecosistemaid <= 11;} else if (form.tipoAmbiente === 'Ambiente acuático') { return i.ecosistemaid >= 12 && i.ecosistemaid <= 20;}return true; })"
                                :key="item.ecosistemaid">
                                <div @click="if(!form.ecosistemas.includes(item.ecosistemaid)) { form.ecosistemas.push(item.ecosistemaid); } openEcoS = false; filterEcoS = '';"
                                    class="px-5 py-3 text-xs font-black text-slate-600 hover:bg-indigo-50 cursor-pointer border-b border-slate-50 flex justify-between items-center"
                                    :class="form.ecosistemas.includes(item.ecosistemaid) ? 'bg-indigo-50 text-indigo-700' : ''">
                                    <span x-text="item.tipoecosistema"></span>
                                    <template x-if="form.ecosistemas.includes(item.ecosistemaid)">
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="form.tipoAmbiente" class="pt-2">
    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">
        Información adicional
    </label>
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
            <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">2. Tipo de Vegetación
            </h3>

            <div class="space-y-4">
                <div class="flex flex-col" x-data="{
                    get agrupadosVeg() {
                        return this.vegetacionOptions.reduce((acc, obj) => {
                            const key = obj.descripcionVegetacion;
                            if (!acc[key]) acc[key] = [];
                            acc[key].push(obj);
                            return acc;
                        }, {});
                    }
                }">
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">a)Indicar el o los tipos de vegetación en los que se desarrolla la especie: </label>
                    <div class="relative">
                        <div @click="openVeg = !openVeg; if(openVeg) $nextTick(() => $refs.inputBusquedaVeg.focus())"
                            class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 cursor-pointer hover:border-indigo-300 shadow-inner">
                            <template x-if="form.tipo_vegetacion_a.length === 0">
                                <span class="text-xs text-slate-400 p-2 font-black">SELECCIONAR
                                    VEGETACIÓN...</span>
                            </template>
                            <template x-for="idSel in form.tipo_vegetacion_a" :key="idSel">
                                <div
                                    class="bg-indigo-600 text-white text-[10px] font-black px-4 py-1.5 rounded-xl flex items-center shadow-md">
                                    <span
                                        x-text="vegetacionOptions.find(o => o.vegetacionId == idSel)?.descripcionSubVegetacion"></span>
                                    <button type="button"
                                        @click.stop="form.tipo_vegetacion_a = form.tipo_vegetacion_a.filter(i => i != idSel)"
                                        class="ml-2 hover:text-rose-300">✕</button>
                                </div>
                            </template>
                        </div>
                        <div x-show="openVeg" @click.away="openVeg = false" x-cloak
                            class="absolute z-[150] w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden">
                            <div class="p-2 border-b bg-slate-50">
                                <input type="text" x-model="filterVeg" placeholder="Filtrar vegetación..."
                                    x-ref="inputBusquedaVeg"
                                    class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-indigo-400 outline-none">
                            </div>
                            <div class="max-h-80 overflow-y-auto bg-white">
                                <template x-for="(items, categoria) in agrupadosVeg" :key="categoria">
                                    <div>
                                        <div class="bg-slate-100 px-4 py-2 text-[10px] font-black text-slate-900 sticky top-0 border-y border-slate-200"
                                            x-text="categoria"></div>
                                        <template
                                            x-for="item in items.filter(i => i.descripcionSubVegetacion.toLowerCase().includes(filterVeg.toLowerCase()))"
                                            :key="item.vegetacionId">
                                            <div @click="if(!form.tipo_vegetacion_a.some(s => s == item.vegetacionId)) { form.tipo_vegetacion_a = [...form.tipo_vegetacion_a, item.vegetacionId]; } openVeg = false; filterVeg = '';"
                                                class="px-8 py-3 text-xs font-bold text-slate-600 hover:bg-indigo-50 cursor-pointer border-b border-slate-50 flex justify-between items-center"
                                                :class="form.tipo_vegetacion_a.some(s => s == item.vegetacionId) ?
                                                    'bg-indigo-50 text-indigo-700' : ''">
                                                <span x-text="item.descripcionSubVegetacion"></span>
                                                <template
                                                    x-if="form.tipo_vegetacion_a.some(s => s == item.vegetacionId)">
                                                    <svg class="w-4 h-4 text-indigo-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">
        Información adicional
    </label>
    <textarea id="vegetacion_info_adicional_a_editor"
        placeholder="Información adicional sobre vegetación..."
        class="w-full rounded-xl border-2 border-slate-100 p-3 text-xs bg-slate-50 outline-none focus:border-indigo-400 min-h-[60px]"></textarea>
</div>
                </div>
            </div>

            <div class="space-y-4">
    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">b) Mencionar las especies asociadas</label>
    <textarea id="especies_asociadas_info_editor"
        placeholder="Describa las especies asociadas..."
        class="w-full rounded-xl border-2 border-slate-100 p-3 text-xs bg-slate-50 outline-none focus:border-indigo-400 min-h-[80px] shadow-inner"></textarea>
</div>

            <div class="space-y-6">
                <div class="flex flex-col" x-data="{ openAntro: false, filterAntro: '' }">
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">c) Hábitats antrópicos:</label>
                    <div class="relative mb-6">
                        <div @click="openAntro = !openAntro; if(openAntro) $nextTick(() => $refs.inputBusquedaAntro.focus())"
                            class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 cursor-pointer shadow-inner hover:border-indigo-300 transition-all">
                            <template x-if="form.habitats_antropicos.length === 0">
                                <span class="text-xs text-slate-400 p-2 font-black">SELECCIONAR
                                    HÁBITAT...</span>
                            </template>
                            <template x-for="idSel in form.habitats_antropicos" :key="idSel">
                                <div
                                    class="bg-indigo-600 text-white text-[10px] font-black px-4 py-1.5 rounded-xl flex items-center shadow-md">
                                    <span
                                        x-text="habitatsAntropicosOptions.find(o => o.idopcion == idSel)?.descn1"></span>
                                    <button type="button"
                                        @click.stop="form.habitats_antropicos = form.habitats_antropicos.filter(i => i != idSel)"
                                        class="ml-2 hover:text-rose-300">✕</button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                        <template
                            x-for="(label, key) in { habitatAgropecuario: 'i. ¿Hábitat agropecuario?', zonaUrbana: 'ii. ¿Zonas urbanas?', VegetacionSecundaria: 'iii. ¿Vegetación secundaria?' }">
                            <div class="flex flex-col space-y-3">
                                <span class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center" x-text="label"></span>
                                <div class="flex space-x-4">
                                    <label class="flex items-center space-x-2 cursor-pointer"><input type="radio"
                                            :name="key" value="si" x-model="form[key]"
                                            class="w-4 h-4 text-indigo-600 border-slate-300"><span
                                            class="text-[10px] font-bold">Sí</span></label>
                                    <label class="flex items-center space-x-2 cursor-pointer"><input type="radio"
                                            :name="key" value="no" x-model="form[key]"
                                            class="w-4 h-4 text-rose-500 border-slate-300"><span
                                            class="text-[10px] font-bold">No</span></label>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex flex-col" x-data="{ openVegSec: false, filterVegSec: '' }">
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">d) Tipo de vegetación secundaria</label>
                    <div class="relative">
                        <div @click="openVegSec = !openVegSec; if(openVegSec) $nextTick(() => $refs.inputBusquedaVegSec.focus())"
                            class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 cursor-pointer shadow-inner">
                            <template x-if="form.vegetacion_secundaria.length === 0">
                                <span class="text-xs text-slate-400 p-2 font-black">SELECCIONAR
                                    VEGETACIÓN...</span>
                            </template>
                            <template x-for="idSel in form.vegetacion_secundaria" :key="idSel">
                                <div
                                    class="bg-indigo-600 text-white text-[10px] font-black px-4 py-1.5 rounded-xl flex items-center shadow-md">
                                    <span x-text="vegSecundariaOptions.find(o => o.idopcion == idSel)?.descn1"></span>
                                    <button type="button"
                                        @click.stop="form.vegetacion_secundaria = form.vegetacion_secundaria.filter(i => i != idSel)"
                                        class="ml-2">✕</button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
                <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">3. Intervalo altitudinal</h3>
                <div class="flex items-center gap-4">
                    <div class="flex-grow">
                        <label class="text-[9px] font-bold text-slate-400 block mb-1">de (m)</label>
                        <input type="number" x-model="form.intervaloaltitudinalinicial"
                            class="w-full px-3 py-2 rounded-lg border-2 border-slate-50 bg-slate-50 text-sm outline-none">
                    </div>
                    <div class="flex-grow">
                        <label class="text-[9px] font-bold text-slate-400 block mb-1">a (m)</label>
                        <input type="number" x-model="form.intervaloaltitudinalfinal"
                            class="w-full px-3 py-2 rounded-lg border-2 border-slate-50 bg-slate-50 text-sm outline-none">
                    </div>
                    <div class="flex-grow">
                        <label class="text-[9px] font-bold text-slate-400 block mb-1">Promedio</label>
                        <input type="number" step="0.01" readonly
                            :value="form.altitud_prom = calcularPromedio(form.intervaloaltitudinalinicial, form
                                .intervaloaltitudinalfinal)"
                            class="w-full px-3 py-2 rounded-lg bg-indigo-50 border-none text-sm font-black text-indigo-600 outline-none">
                    </div>
                </div>
                <textarea id="infoAddintervaloaltitudinal_editor" placeholder="Información adicional altitud..."
    class="w-full rounded-xl border-2 border-slate-50 p-3 text-xs bg-slate-50 outline-none min-h-[60px]"></textarea>
            </div>

            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
                <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">4. Clima</h3>
                <div x-data="{
                    openClima: false,
                    filterClima: '',
                    get agrupadosClima() {
                        return this.climaOptions.reduce((acc, obj) => {
                            const key = obj.descn1;
                            if (!acc[key]) acc[key] = [];
                            acc[key].push(obj);
                            return acc;
                        }, {});
                    }
                }">
                    <div class="relative">
                        <div @click="openClima = !openClima"
                            class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 cursor-pointer shadow-inner">
                            <template x-if="form.clima_tipo.length === 0"><span
                                    class="text-xs text-slate-400 p-2 font-black">SELECCIONAR
                                    CLIMA...</span></template>
                            <template x-for="idSel in form.clima_tipo" :key="idSel">
                                <div
                                    class="bg-indigo-600 text-white text-[10px] font-black px-4 py-1.5 rounded-xl flex items-center">
                                    <span x-text="climaOptions.find(o => o.idopcion == idSel)?.descn2"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <textarea x-model="form.clima_info" placeholder="Información adicional sobre el clima..."
                    class="w-full rounded-xl border-2 border-slate-50 p-3 text-xs bg-slate-50 outline-none min-h-[60px]"></textarea>
            </div>

            <template
                x-for="param in [
                { id: 'temperatura', label: '5. Intervalo de temperatura', unit: '°C' },
                { id: 'precipitacion', label: '6. Intervalo de precipitación', unit: 'mm' },
                { id: 'humedad', label: '7. Intervalo de humedad relativa', unit: '%' }
            ]">
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
                    <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center" x-text="param.label">
                    </h3>
                    <div class="flex items-center gap-3">
                        <div class="relative flex-grow">
                            <span class="absolute right-3 top-7 text-[10px] font-bold text-slate-400"
                                x-text="param.unit"></span>
                            <label class="text-[9px] font-bold text-slate-400 mb-1 block">de</label>
                            <input type="number" x-model="form[param.id + 'inicial']"
                                class="w-full px-3 py-2 rounded-lg bg-slate-50 text-sm border-2 border-slate-50">
                        </div>
                        <div class="relative flex-grow">
                            <span class="absolute right-3 top-7 text-[10px] font-bold text-slate-400"
                                x-text="param.unit"></span>
                            <label class="text-[9px] font-bold text-slate-400 mb-1 block">a</label>
                            <input type="number" x-model="form[param.id + 'final']"
                                class="w-full px-3 py-2 rounded-lg bg-slate-50 text-sm border-2 border-slate-50">
                        </div>
                    </div>
                    <textarea x-model="form['infoadd' + param.id]" placeholder="Información adicional..."
                        class="w-full rounded-xl border-2 border-slate-50 p-3 text-xs bg-slate-50 outline-none min-h-[60px]"></textarea>
                </div>
            </template>

            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
                <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">8. Tipo de Suelo</h3>
                <div x-data="{ openSuelo: false, filterSuelo: '' }" class="relative">
                    <div @click="openSuelo = !openSuelo"
                        class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 cursor-pointer shadow-inner">
                        <template x-if="form.suelo_tipo.length === 0"><span
                                class="text-xs text-slate-400 p-2 font-black">SELECCIONAR
                                SUELO...</span></template>
                        <template x-for="idSel in form.suelo_tipo" :key="idSel">
                            <div
                                class="bg-indigo-600 text-white text-[10px] font-black px-4 py-1.5 rounded-xl flex items-center">
                                <span x-text="suelosOptions.find(o => o.idopcion == idSel)?.descn1"></span>
                            </div>
                        </template>
                    </div>
                </div>
                <textarea x-model="form.suelo_info" placeholder="Información adicional sobre suelo..."
                    class="w-full rounded-xl border-2 border-slate-50 p-3 text-xs bg-slate-50 outline-none min-h-[60px]"></textarea>
            </div>

            <div
                class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6 col-span-1 lg:col-span-2">
                <div class="flex flex-col" x-data="{ openGeo: false, filterGeo: '' }">
                    <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">9.
                        Geoforma </h3>
                    <div class="relative">
                        <div @click="openGeo = !openGeo; if(openGeo) $nextTick(() => $refs.inputBusquedaGeo.focus())"
                            class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 cursor-pointer hover:border-indigo-300 transition-all shadow-inner">
                            <template x-if="form.geoforma_tipo.length === 0">
                                <span
                                    class="text-xs text-slate-400 p-2 font-black tracking-widest">SELECCIONAR
                                    GEOFORMA...</span>
                            </template>
                            <template x-for="idSel in form.geoforma_tipo" :key="idSel">
                                <div
                                    class="bg-indigo-600 text-white text-[10px] font-black px-4 py-1.5 rounded-xl flex items-center shadow-md">
                                    <span x-text="geoformaOptions.find(o => o.idopcion == idSel)?.descn1"></span>
                                    <button type="button"
                                        @click.stop="form.geoforma_tipo = form.geoforma_tipo.filter(i => i != idSel)"
                                        class="ml-2 hover:text-rose-300">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <div x-show="openGeo" @click.away="openGeo = false" x-cloak
                            class="absolute z-[110] w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden">
                            <div class="p-2 border-b border-slate-100 bg-slate-50">
                                <input type="text" x-model="filterGeo" placeholder="Buscar geoforma..."
                                    x-ref="inputBusquedaGeo"
                                    class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-indigo-400">
                            </div>
                            <div class="max-h-60 overflow-y-auto bg-white">
                                <template
                                    x-for="item in geoformaOptions.filter(i => i.descn1.toLowerCase().includes(filterGeo.toLowerCase()))"
                                    :key="item.idopcion">
                                    <div @click=" let id = item.idopcion; if(!form.geoforma_tipo.some(s => s == id)) { form.geoforma_tipo = [...form.geoforma_tipo, id]; } openGeo = false; filterGeo = ''; "
                                        class="px-5 py-3 text-xs font-black text-slate-600 hover:bg-indigo-50 cursor-pointer border-b border-slate-50 flex justify-between items-center":class="form.geoforma_tipo.some(s => s == item.idopcion) ? 'bg-indigo-50 text-indigo-700' : ''">
                                        <span x-text="item.descn1"></span>
                                        <template x-if="form.geoforma_tipo.some(s => s == item.idopcion)">
                                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <textarea x-model="form.geoforma_info" placeholder="Recuadro de información adicional..."
                    class="w-full rounded-xl border-2 border-slate-50 p-3 text-xs bg-slate-50 outline-none focus:border-indigo-400 min-h-[80px] shadow-inner"></textarea>
            </div>

        </div>
    </div>

    <div x-show="form.tipoAmbiente === 'Ambiente acuático'" x-transition class="space-y-8">
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-8">
            <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">
                <span class="mr-4">9. Tipo de hábitat marino</span>
                <div class="h-px bg-slate-100 flex-grow"></div>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2" x-data="{
                    open: false,
                    search: '',
                    options: ['Bentónico', 'Demersal', 'Epipelágico', 'Mesopelágico', 'Bathipelágico'],
                    select(option) {
                        form.habitat_marino_vertical = option;
                        this.open = false;
                        this.search = '';
                    }
                }">
                    <label class="text-[10px] font-black text-slate-500 tracking-widest block">a)
                        Vertical:</label>

                    <div class="relative">
                        <div @click="open = !open"
                            class="w-full min-h-[50px] p-2 rounded-xl bg-slate-50 border-2 border-slate-100 cursor-pointer transition-all focus-within:border-indigo-400 shadow-inner flex items-center gap-2">
                            <template x-if="form.habitat_marino_vertical">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-600 text-white">
                                    <span x-text="form.habitat_marino_vertical"></span>
                                    <button type="button" @click.stop="form.habitat_marino_vertical = ''"
                                        class="ml-2 hover:text-indigo-200">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z">
                                            </path>
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <input type="text" x-model="search" placeholder="Buscar opción..."
                                @click.away="open = false"
                                class="flex-grow bg-transparent border-none outline-none text-sm px-2">
                        </div>

                        <div x-show="open" x-transition
                            class="absolute z-50 w-full mt-2 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden max-h-60 overflow-y-auto">
                            <template
                                x-for="option in options.filter(o => o.toLowerCase().includes(search.toLowerCase()))"
                                :key="option">
                                <div @click="select(option)"
                                    :class="form.habitat_marino_vertical === option ? 'bg-indigo-50 text-indigo-700 font-bold' :
                                        'text-slate-600 hover:bg-slate-50'"
                                    class="px-4 py-3 text-sm cursor-pointer flex justify-between items-center border-b border-slate-50 last:border-0 transition-colors">
                                    <span x-text="option"></span>
                                    <template x-if="form.habitat_marino_vertical === option">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="space-y-2" x-data="{
                    open: false,
                    search: '',
                    options: ['Asociado a arrecifes (coralinos o rocosos)', 'Costero', 'Plataforma continental', 'Talud continental', 'Oceánico'],
                    select(option) {
                        form.habitat_marino_horizontal = option;
                        this.open = false;
                        this.search = '';
                    }
                }">
                    <label class="text-[10px] font-black text-slate-500 tracking-widest block">b)
                        Horizontal:</label>

                    <div class="relative">
                        <div @click="open = !open"
                            class="w-full min-h-[50px] p-2 rounded-xl bg-slate-50 border-2 border-slate-100 cursor-pointer transition-all focus-within:border-indigo-400 shadow-inner flex items-center gap-2">

                            <template x-if="form.habitat_marino_horizontal">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-600 text-white">
                                    <span x-text="form.habitat_marino_horizontal"></span>
                                    <button type="button" @click.stop="form.habitat_marino_horizontal = ''"
                                        class="ml-2 hover:text-indigo-200">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z">
                                            </path>
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <input type="text" x-model="search" placeholder="Buscar opción..."
                                @click.away="open = false"
                                class="flex-grow bg-transparent border-none outline-none text-sm px-2">
                        </div>

                        <div x-show="open" x-transition
                            class="absolute z-50 w-full mt-2 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden max-h-60 overflow-y-auto">
                            <template
                                x-for="option in options.filter(o => o.toLowerCase().includes(search.toLowerCase()))"
                                :key="option">
                                <div @click="select(option)"
                                    :class="form.habitat_marino_horizontal === option ?
                                        'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50'"
                                    class="px-4 py-3 text-sm cursor-pointer flex justify-between items-center border-b border-slate-50 last:border-0 transition-colors">
                                    <span x-text="option"></span>
                                    <template x-if="form.habitat_marino_horizontal === option">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <label class="text-[16px] font-black text-slate-500 tracking-widest block">Informacion
                    adicional</label>
                <textarea x-model="form.habitat_marino_infoAddVH" placeholder="Información adicional sobre VH..."
                    class="w-full rounded-2xl border-2 border-slate-100 p-4 text-xs bg-slate-50 outline-none focus:border-indigo-400 min-h-[100px] shadow-inner"></textarea>
            </div>

            <div class="space-y-4">
                <label class="text-[16px] font-black text-slate-500 tracking-widest block">c) Mencionar las
                    especies asociadas</label>
                <textarea x-model="form.habitat_marino_especiesAsociadas" placeholder="Describa las especies asociadas..."
                    class="w-full rounded-2xl border-2 border-slate-100 p-4 text-xs bg-slate-50 outline-none focus:border-indigo-400 min-h-[100px] shadow-inner"></textarea>
            </div>

            <div class="space-y-6">
                <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                        <label class="text-[10px] font-black text-slate-500 tracking-widest">
                            d) Indicar si hay disturbios antrópicos
                        </label>
                        <div class="flex bg-slate-200/50 p-1 rounded-xl border border-slate-200">
                            <label
                                class="flex items-center px-6 py-2 rounded-lg cursor-pointer transition-all duration-300"
                                :class="form.habitat_marino_disturbiosAntropicos == 'SÍ' ?
                                    'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-600'">
                                <input type="radio" x-model="form.habitat_marino_disturbiosAntropicos"
                                    value="SÍ" class="hidden">
                                <span class="text-[10px] font-black">SÍ</span>
                            </label>
                            <label
                                class="flex items-center px-6 py-2 rounded-lg cursor-pointer transition-all duration-300"
                                :class="form.habitat_marino_disturbiosAntropicos == 'NO' ? 'bg-white text-rose-500 shadow-sm' :
                                    'text-slate-400 hover:text-slate-600'">
                                <input type="radio" x-model="form.habitat_marino_disturbiosAntropicos"
                                    value="NO" class="hidden">
                                <span class="text-[10px] font-black">NO</span>
                            </label>
                        </div>
                    </div>
                    <div x-show="form.habitat_marino_disturbiosAntropicos == 'SÍ'"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0" class="mt-4">
                        <textarea x-model="form.habitat_marino_infoAddDisturbiosAntropicos"
                            placeholder="Describa los disturbios observados..."
                            class="w-full rounded-xl border-2 border-white p-4 text-xs bg-white outline-none focus:border-indigo-400 min-h-[100px] shadow-sm transition-all"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div x-show="form.tipoAmbiente === 'Ambiente acuático' || form.tipoAmbiente === 'Ambiente terrestre-acuático'"
        x-transition class="space-y-8">

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 space-y-4">
    <div class="flex items-center gap-4">
        <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">10. Intervalo batimétrico</h3>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
            <!-- Columna Izquierda: Inputs y Promedio (Más pequeña) -->
            <!-- Cambiamos min-w-[280px] por un ancho fijo en LG para que sea más estrecho -->
            <div class="flex flex-col gap-3 w-full lg:w-64 flex-shrink-0">
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-16px] font-bold text-slate-700 tracking-tight flex items-center">De (m)</label>
                        <input type="number" x-model="form.interbatimetricoinicial"
                            class="w-full px-3 py-2 rounded-xl border-2 border-slate-50 bg-slate-50 text-sm font-bold outline-none focus:border-indigo-400 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">A (m)</label>
                        <input type="number" x-model="form.interbatimetricofinal"
                            @change="validarRango('interbatimetricoinicial', 'interbatimetricofinal', 'Batimetría')"
                            class="w-full px-3 py-2 rounded-xl border-2 border-slate-50 bg-slate-50 text-sm font-bold outline-none focus:border-indigo-400 transition-all">
                    </div>
                </div>

                <div class="bg-indigo-50/50 p-3 rounded-xl border border-indigo-100 flex items-center justify-between">
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Promedio:</label>
                    <div class="flex items-center gap-1">
                        <span class="text-sm font-black text-indigo-600"
                            x-text="form.interbatimetricopromedio = calcularPromedio(form.interbatimetricoinicial, form.interbatimetricofinal)"></span>
                        <span class="text-[10px] font-bold text-indigo-400">m</span>
                    </div>
                </div>
            </div>

            <div class="flex-grow flex flex-col">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Información adicional</label>
                <textarea x-model="form.infoaddinterbatimetrico"
                    placeholder="Escriba aquí detalles relevantes..."
                    class="w-full flex-grow rounded-2xl border-2 border-slate-50 p-3 text-sm bg-slate-50 outline-none focus:border-indigo-200 min-h-[115px] resize-none transition-all shadow-inner"></textarea>
            </div>
        </div>
</div>

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 space-y-4">
    <!-- Título -->
    <div class="flex items-center gap-4">
        <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">11. Amplitud de mareas</h3>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        <div class="flex flex-col gap-3 w-full lg:w-64 flex-shrink-0">
            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">De</label>
                    <input type="text" x-model="form.amplitudmareasinicial"
                        class="w-full px-3 py-2 rounded-xl border-2 border-slate-50 bg-slate-50 text-sm font-bold outline-none focus:border-indigo-400 transition-all">
                </div>
                <div class="space-y-1">
                    <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">A</label>
                    <input type="text" x-model="form.amplitudmareasfinal"
                        class="w-full px-3 py-2 rounded-xl border-2 border-slate-50 bg-slate-50 text-sm font-bold outline-none focus:border-indigo-400 transition-all">
                </div>
            </div>

            <div class="bg-indigo-50/50 p-3 rounded-xl border border-indigo-100 flex items-center justify-between">
                <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Promedio:</label>
                <input type="text" x-model="form.amplitudmareaspromedio"
                    class="bg-transparent text-right text-sm font-black text-indigo-600 outline-none w-24">
            </div>
        </div>

        <div class="flex-grow flex flex-col">
            <label class="text-[16px] font-bold text-slate-700 tracking-tight flex items-center">Información adicional</label>
            <textarea x-model="form.infoaddamplitudmareas"
                placeholder="Escriba aquí detalles relevantes..."
                class="w-full flex-grow rounded-2xl border-2 border-slate-50 p-3 text-xs bg-slate-50 outline-none focus:border-indigo-200 min-h-[110px] resize-none transition-all shadow-inner"></textarea>
        </div>
    </div>
</div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-8">
            <div class="flex items-center gap-4">
                <h3 class="text-[22px] font-bold text-slate-700 tracking-tight flex items-center">12. Características del agua
                </h3>
                <div class="h-[1px] bg-slate-100 flex-1"></div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <template x-for="item in [{ id_de: 'salinidadinicial', id_a: 'salinidadfinal', id_p: 'salinidadpromedio', label: 'a) Salinidad', placeholder: '...' },{ id_de: 'oxigenoinicial', id_a: 'oxigenofinal', id_p: 'oxigenopromedio', label: 'b) Oxígeno disuelto', placeholder: 'mg/L' },{ id_de: 'phinicial', id_a: 'phfinal', id_p: 'phpromedio', label: 'c) pH', placeholder: '0-14' },{ id_de: 'temeperaturainicial', id_a: 'temeperaturafinal', id_p: 'temeperaturapromedio', label: 'd) Temperatura', placeholder: '°C' }]">
                    <div class="p-6 bg-slate-50 rounded-2xl">
                        <label class="text-xs font-black text-slate-400 tracking-[0.2em]" x-text="item.label"></label>
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-2 items-end">
                            <div>
                                <span class="text-[16px] font-bold text-slate-400 ml-2 mb-1 block">De</span>
                                <input type="text" :placeholder="item.placeholder" x-model="form[item.id_de]" class="w-full px-4 py-3 rounded-xl bg-white border-2 border-slate-100 text-sm outline-none focus:border-indigo-400">
                            </div>
                            <div>
                                <span class="text-[16px] font-bold text-slate-400 ml-2 mb-1 block">A</span>
                                <input type="text" :placeholder="item.placeholder" x-model="form[item.id_a]" class="w-full px-4 py-3 rounded-xl bg-white border-2 border-slate-100 text-sm outline-none focus:border-indigo-400">
                            </div>
                            <div>
                                <span class="text-[16px] font-bold text-slate-400 ml-2 mb-1 block">Promedio</span>
                                <input type="text" x-model="form[item.id_p]" class="w-full px-4 py-3 rounded-xl bg-white border-2 border-slate-200 text-sm outline-none focus:border-indigo-400 shadow-sm font-bold">
                            </div>
                            <template x-if="item.id_de === 'salinidadinicial'">
                                <div class="w-full">
                                    <span class="text-[16px] font-bold text-slate-400 ml-2 mb-1 block">Unidad</span>
                                    <select x-model="form.unidadsalinidad" class="w-full px-4 py-3 rounded-xl bg-white border-2 border-slate-100 text-sm outline-none focus:border-indigo-400 appearance-none">
                                        <option value="">Seleccione...</option>
                                        <option value="Porcentaje(%)">Porcentaje(%)</option>
                                        <option value="Partes por mil(ppt)">Partes por mil(ppt)</option>
                                        <option value="Gramos por litro(g/L)">Gramos por litro(g/L)</option>
                                        <option value="Unidades prácticas de salinidad(ups,psu)">ups, psu</option>
                                    </select>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <div class="p-6 bg-slate-50 rounded-2xl">
                    <label class="text-[16px] font-black text-slate-500 mb-3 block">e) Corrientes</label>
                    <input type="text" x-model="form.corrientes" class="w-full px-4 py-3 rounded-xl bg-white border-2 border-slate-100 text-sm outline-none focus:border-indigo-400">
                </div>
                <div class="p-6 bg-slate-50 rounded-2xl">
                    <label class="text-[16px] font-black text-slate-500 tracking-widest block mb-2">Información adicional de características del agua</label>
                    <textarea x-model="form.infoaddcaracagua" class="w-full rounded-2xl border-2 border-slate-100 p-4 text-xs bg-white outline-none focus:border-indigo-400 min-h-[80px] shadow-inner"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-16 flex justify-between items-center pb-24">
        <button type="button" @click="step = 2" class="group flex items-center px-10 py-5 bg-slate-200 text-slate-600 rounded-full font-black text-xs tracking-[0.3em] shadow-xl hover:bg-slate-300 transition-all">Anterior</button>
        <button type="button" @click="avanzarSeccion()" class="group flex items-center px-12 py-5 bg-indigo-600 text-white rounded-full font-black text-xs tracking-[0.3em] shadow-2xl hover:bg-indigo-700 transition-all">Siguiente</button>
    </div>
</div>
