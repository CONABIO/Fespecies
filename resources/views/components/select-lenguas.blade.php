@props(['model' => 'tempNombre.lengua'])

<div
    x-data="{
        open: false,
        filter: '',
        activeIndex: 0,
        optionsList: [
            { v: 'Otro', t: 'Ingresar la lengua', g: null },
            { v: 'Español', t: 'Español', g: 'Lenguas comunes' },
            { v: 'Inglés', t: 'Inglés', g: 'Lenguas comunes' },
            { v: 'Francés', t: 'Francés', g: 'Lenguas comunes' },
            { v: 'Portugués', t: 'Portugués', g: 'Lenguas comunes' },
            { v: 'Italiano', t: 'Italiano', g: 'Lenguas comunes' },
            { v: 'Alemán', t: 'Alemán', g: 'Lenguas comunes' },
            { v: 'Chino', t: 'Chino', g: 'Lenguas comunes' },
            { v: 'Japonés', t: 'Japonés', g: 'Lenguas comunes' },
            { v: 'Ruso', t: 'Ruso', g: 'Lenguas comunes' },
            { v: 'I Familia Álgica', t: 'I Familia Álgica', g: 'Familias linguisticas' },
            { v: 'II Familia Yuto-nahua', t: 'II Familia Yuto-nahua', g: 'Familias linguisticas' },
            { v: 'III Familia Cochimí-yumana', t: 'III Familia Cochimí-yumana', g: 'Familias linguisticas' },
            { v: 'IV Familia Seri', t: 'IV Familia Seri', g: 'Familias linguisticas' },
            { v: 'V Familia Oto-mangue', t: 'V Familia Oto-mangue', g: 'Familias linguisticas' },
            { v: 'VI Familia Maya', t: 'VI Familia Maya', g: 'Familias linguisticas' },
            { v: 'VII Familia Totonaco-tepehua', t: 'VII Familia Totonaco-tepehua', g: 'Familias linguisticas' },
            { v: 'VIII Familia Tarasca', t: 'VIII Familia Tarasca', g: 'Familias linguisticas' },
            { v: 'IX Familia Mixe-zoque', t: 'IX Familia Mixe-zoque', g: 'Familias linguisticas' },
            { v: 'X Familia Chontal de Oaxaca', t: 'X Familia Chontal de Oaxaca', g: 'Familias linguisticas' },
            { v: 'XI Familia Huave', t: 'XI Familia Huave', g: 'Familias linguisticas' },
            { v: 'Akateko', t: 'Akateko', g: 'Lenguas individuales' },
            { v: 'Amuzgo', t: 'Amuzgo', g: 'Lenguas individuales' },
            { v: 'Awakateko', t: 'Awakateko', g: 'Lenguas individuales' },
            { v: 'Ayapaneco', t: 'Ayapaneco', g: 'Lenguas individuales' },
            { v: 'Cora', t: 'Cora', g: 'Lenguas individuales' },
            { v: 'Cucapá', t: 'Cucapá', g: 'Lenguas individuales' },
            { v: 'Cuicateco', t: 'Cuicateco', g: 'Lenguas individuales' },
            { v: 'Chatino', t: 'Chatino', g: 'Lenguas individuales' },
            { v: 'Chichimeco jonaz', t: 'Chichimeco jonaz', g: 'Lenguas individuales' },
            { v: 'Chinanteco', t: 'Chinanteco', g: 'Lenguas individuales' },
            { v: 'Chocholteco', t: 'Chocholteco', g: 'Lenguas individuales' },
            { v: 'Chontal de Oaxaca', t: 'Chontal de Oaxaca', g: 'Lenguas individuales' },
            { v: 'Chontal de Tabasco', t: 'Chontal de Tabasco', g: 'Lenguas individuales' },
            { v: 'Chuj', t: 'Chuj', g: 'Lenguas individuales' },
            { v: 'Ch’ol', t: 'Ch’ol', g: 'Lenguas individuales' },
            { v: 'Guarijío', t: 'Guarijío', g: 'Lenguas individuales' },
            { v: 'Huasteco', t: 'Huasteco', g: 'Lenguas individuales' },
            { v: 'Huave', t: 'Huave', g: 'Lenguas individuales' },
            { v: 'Huichol', t: 'Huichol', g: 'Lenguas individuales' },
            { v: 'Ixcateco', t: 'Ixcateco', g: 'Lenguas individuales' },
            { v: 'Ixil', t: 'Ixil', g: 'Lenguas individuales' },
            { v: 'Jakalteko', t: 'Jakalteko', g: 'Lenguas individuales' },
            { v: 'Kaqchikel', t: 'Kaqchikel', g: 'Lenguas individuales' },
            { v: 'Kickapoo', t: 'Kickapoo', g: 'Lenguas individuales' },
            { v: 'Kiliwa', t: 'Kiliwa', g: 'Lenguas individuales' },
            { v: 'Kumiai', t: 'Kumiai', g: 'Lenguas individuales' },
            { v: 'Ku’ahl', t: 'Ku’ahl', g: 'Lenguas individuales' },
            { v: 'K’iche’', t: 'K’iche’', g: 'Lenguas individuales' },
            { v: 'Lacandón', t: 'Lacandón', g: 'Lenguas individuales' },
            { v: 'Mam', t: 'Mam', g: 'Lenguas individuales' },
            { v: 'Matlatzinca', t: 'Matlatzinca', g: 'Lenguas individuales' },
            { v: 'Maya', t: 'Maya', g: 'Lenguas individuales' },
            { v: 'Mayo', t: 'Mayo', g: 'Lenguas individuales' },
            { v: 'Mazahua', t: 'Mazahua', g: 'Lenguas individuales' },
            { v: 'Mazateco', t: 'Mazateco', g: 'Lenguas individuales' },
            { v: 'Mixe', t: 'Mixe', g: 'Lenguas individuales' },
            { v: 'Mixteco', t: 'Mixteco', g: 'Lenguas individuales' },
            { v: 'Náhuatl', t: 'Náhuatl', g: 'Lenguas individuales' },
            { v: 'Oluteco', t: 'Oluteco', g: 'Lenguas individuales' },
            { v: 'Otomí', t: 'Otomí', g: 'Lenguas individuales' },
            { v: 'Paipai', t: 'Paipai', g: 'Lenguas individuales' },
            { v: 'Pame', t: 'Pame', g: 'Lenguas individuales' },
            { v: 'Pápago', t: 'Pápago', g: 'Lenguas individuales' },
            { v: 'Pima', t: 'Pima', g: 'Lenguas individuales' },
            { v: 'Popoloca', t: 'Popoloca', g: 'Lenguas individuales' },
            { v: 'Popoluca de la Sierra', t: 'Popoluca de la Sierra', g: 'Lenguas individuales' },
            { v: 'Qato’k', t: 'Qato’k', g: 'Lenguas individuales' },
            { v: 'Q’anjob’al', t: 'Q’anjob’al', g: 'Lenguas individuales' },
            { v: 'Q’eqchí’', t: 'Q’eqchí’', g: 'Lenguas individuales' },
            { v: 'Sayulteco', t: 'Sayulteco', g: 'Lenguas individuales' },
            { v: 'Seri', t: 'Seri', g: 'Lenguas individuales' },
            { v: 'Tarahumara', t: 'Tarahumara', g: 'Lenguas individuales' },
            { v: 'Tarasco', t: 'Tarasco', g: 'Lenguas individuales' },
            { v: 'Teko', t: 'Teko', g: 'Lenguas individuales' },
            { v: 'Tepehua', t: 'Tepehua', g: 'Lenguas individuales' },
            { v: 'Tepehuano del norte', t: 'Tepehuano del norte', g: 'Lenguas individuales' },
            { v: 'Tepehuano del sur', t: 'Tepehuano del sur', g: 'Lenguas individuales' },
            { v: 'Texistepequeño', t: 'Texistepequeño', g: 'Lenguas individuales' },
            { v: 'Tojolabal', t: 'Tojolabal', g: 'Lenguas individuales' },
            { v: 'Totonaco', t: 'Totonaco', g: 'Lenguas individuales' },
            { v: 'Triqui', t: 'Triqui', g: 'Lenguas individuales' },
            { v: 'Tlahuica', t: 'Tlahuica', g: 'Lenguas individuales' },
            { v: 'Tlapaneco', t: 'Tlapaneco', g: 'Lenguas individuales' },
            { v: 'Tseltal', t: 'Tseltal', g: 'Lenguas individuales' },
            { v: 'Tsotsil', t: 'Tsotsil', g: 'Lenguas individuales' },
            { v: 'Yaqui', t: 'Yaqui', g: 'Lenguas individuales' },
            { v: 'Zapoteco', t: 'Zapoteco', g: 'Lenguas individuales' },
            { v: 'Zoque', t: 'Zoque', g: 'Lenguas individuales' }
        ],
        id: 'select-' + Math.random().toString(36).substr(2, 9),

        init() {
            this.$watch('filter', () => this.activeIndex = 0);
        },

        get selectedValue() {
            return {{ $model }} || '';
        },

        get filteredOptions() {
            let f = this.filter.toLowerCase().trim();
            return this.optionsList
                .filter(i => !f || i.t.toLowerCase().includes(f) || (i.g && i.g.toLowerCase().includes(f)))
                .slice(0, 50);
        },

        select(val) {
            {{ $model }} = val;
            this.open = false;
            this.filter = '';
        }
    }"
    class="relative w-full mt-1"
    @click.away="open = false"
    @keydown.escape="open = false"
>
    <div @click="open = !open; if(open) $nextTick(() => $refs.searchInput.focus());"
         class="px-4 py-2.5 rounded-full border-2 border-gray-100 bg-gray-50 flex items-center justify-between cursor-pointer shadow-inner hover:border-indigo-300 transition-all">

        <span class="text-xs font-bold" :class="selectedValue ? 'text-slate-700' : 'text-slate-400'" x-text="selectedValue || 'Seleccione una lengua...'"></span>

        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </div>

    <div x-show="open" x-cloak x-transition
         class="absolute z-[2100] w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden">

        <div class="p-2 border-b border-slate-100 bg-slate-50">
            <input x-ref="searchInput"
                   type="text"
                   x-model.debounce.200ms="filter"
                   @keydown.down.prevent="activeIndex = Math.min(activeIndex + 1, filteredOptions.length - 1)"
                   @keydown.up.prevent="activeIndex = Math.max(activeIndex - 1, 0)"
                   @keydown.enter.prevent="if(filteredOptions[activeIndex]) select(filteredOptions[activeIndex].v)"
                   placeholder="Buscar lengua o idioma..."
                   class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-indigo-400">
        </div>

        <div class="max-h-60 overflow-y-auto">
            <template x-if="filteredOptions.length === 0">
                <div class="p-4 text-xs text-slate-400 font-bold text-center">No hay opciones disponibles</div>
            </template>

            <template x-for="(opt, index) in filteredOptions" :key="opt.v">
                <div>
                    <template x-if="opt.g && (index === 0 || opt.g !== filteredOptions[index-1].g)">
                        <div class="bg-slate-100 px-4 py-1.5 text-[10px] font-black text-indigo-500 tracking-widest border-y border-slate-200 sticky top-0 z-10">
                            <span x-text="opt.g"></span>
                        </div>
                    </template>
                    <div :id="id + '-opt-' + index"
                         @click="select(opt.v)"
                         @mouseenter="activeIndex = index"
                         class="px-4 py-2.5 text-[13px] flex justify-between items-center border-b border-slate-50 transition-colors cursor-pointer"
                         :class="{
                            'bg-indigo-600 text-white font-black': activeIndex === index,
                            'text-slate-700': activeIndex !== index,
                            'text-red-500 font-black': opt.v === 'Otro'
                         }">
                        <span x-text="opt.t" class="font-bold"></span>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
