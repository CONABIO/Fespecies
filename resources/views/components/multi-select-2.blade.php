<div
    x-data="{
        open: false,
        filter: '',
        activeIndex: 0,
        optionsList: [],
        optionsMap: {},
        id: 'select-' + Math.random().toString(36).substr(2, 9),

        init() {
            this.refresh(@js($options ?? []));
            if ('{{ is_string($options ?? '') ? $options : '' }}' === 'ecosistemaOptions') {
                this.$watch('form.tipoAmbiente', () => this.refresh(@js($options ?? [])));
            }
            this.$watch('activeIndex', (val) => {
                if (!this.open) return;
                this.$nextTick(() => {
                    let activeEl = document.getElementById(this.id + '-opt-' + val);
                    if (activeEl) {
                        activeEl.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                    }
                });
            });
            this.$watch('filter', () => this.activeIndex = 0);
        },

        refresh(inputOptions) {
            let raw = [];
            if (typeof inputOptions === 'string') {
                raw = this[inputOptions] || (this.$data && this.$data[inputOptions]) || window[inputOptions] || [];
            } else if (Array.isArray(inputOptions)) {
                raw = inputOptions;
            }
            if ('{{ is_string($options ?? '') ? $options : '' }}' === 'ecosistemaOptions' && this.form && this.form.tipoAmbiente) {
                const grupoA = ['Selvas húmedas', 'Selvas secas', 'Bosques mesófilos de montaña', 'Bosques templados de coníferas y latifoliadas', 'Matorrales xerófilos', 'Pastizales'];
                const grupoB = ['Fondos blandos', 'Lechos de pastos marinos', 'Fondos duros o rocosos', 'Lechos de rodolitos', 'Arrecifes coralinos', 'Bosques de macroalgas', 'Regiones mesofóticas', 'Ambientes pelágicos', 'Fosas y llanuras abisales'];
                const grupoC = ['Humedales', 'Manglares', 'Marismas', 'Estuarios y lagunas costeras', 'Playas arenosas y zonas rocosas intermareales'];

                raw = raw.filter(o => {
                    let eco = o.tipoecosistema || o.t || o;
                    if (this.form.tipoAmbiente === 'Ambiente terrestre') return grupoA.includes(eco);
                    if (this.form.tipoAmbiente === 'Ambiente acuático') return grupoB.includes(eco);
                    if (this.form.tipoAmbiente === 'Ambiente terrestre-acuático') return grupoC.includes(eco);
                    return false;
                });
            }
            let newList = [];
            let newMap = {};
            newList.push({ v: 'ND', t: 'ND', g: null });
            newMap['ND'] = 'ND';
            if (Array.isArray(raw)) {
                raw.forEach(o => {
                    if (!o) return;
                    let val = String(o.v ?? o.idopcion ?? o.id ?? o.tipoecosistema ?? (typeof o === 'object' ? Object.values(o)[0] : o));
                    let txt = String(o.t ?? o.descn1 ?? o.descripcion ?? o.nombre ?? o.tipoecosistema ?? (typeof o === 'object' ? (o.t || o.descn1 || Object.values(o)[1] || JSON.stringify(o)) : o));
                    let grp = o.g ? String(o.g) : null;

                    if (txt.includes('[object Object]') || typeof txt !== 'string') {
                        txt = o.t || o.descn1 || o.nombre || o.descripcion || o.tipoecosistema || 'Opción';
                    }

                    if (val && txt && val !== 'ND') {
                        newList.push({ v: String(val), t: String(txt), g: grp });
                        newMap[String(val)] = String(txt);
                    }
                });
            }
            this.optionsList = newList;
            this.optionsMap = newMap;
        },

        get selectedIds() {
            let val = {{ $model }};
            return Array.isArray(val) ? val.map(String) : (val ? [String(val)] : []);
        },

        get filteredOptions() {
            let f = this.filter.toLowerCase().trim();
            return this.optionsList
                .filter(i => !this.selectedIds.includes(String(i.v)))
                .filter(i => !f || i.t.toLowerCase().includes(f) || (i.g && i.g.toLowerCase().includes(f)));
        },

        toggle(id) {
            id = String(id);
            let current = [...this.selectedIds];
            if (current.includes(id)) return;
            current.push(id);
            {{ $model }} = current;
            this.filter = '';
            if (this.$refs.searchInput) {
                this.$refs.searchInput.value = '';
                this.$refs.searchInput.focus();
            }
        },

        remove(id) {
            id = String(id);
            let current = this.selectedIds.filter(i => String(i) !== id);
            {{ $model }} = current;
        }
    }"
    class="relative w-full"
    @click.away="open = false"
    @keydown.escape="open = false"
>
    <div @click="open = !open; if(open) $nextTick(() => $refs.searchInput.focus());"
         class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 cursor-pointer shadow-inner hover:border-indigo-300 transition-all">

        <template x-if="selectedIds.length === 0">
            <span class="text-[14px] text-slate-400 p-2 font-black">{{ $placeholder }}</span>
        </template>

        <template x-for="id in selectedIds" :key="id">
            <div class="bg-indigo-600 text-white text-[11px] font-black px-3 py-1.5 rounded-xl flex items-center shadow-md">
                <span x-text="optionsMap[id] || id"></span>
                <button type="button" @click.stop="remove(id)" class="ml-2 hover:text-indigo-200 font-black text-lg">&times;</button>
            </div>
        </template>
    </div>

    <div x-show="open" x-cloak x-transition
         class="absolute z-[110] w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden">

        <div class="p-2 border-b border-slate-100 bg-slate-50">
            <input x-ref="searchInput"
                   type="text"
                   x-model.debounce.200ms="filter"
                   @keydown.down.prevent="activeIndex = Math.min(activeIndex + 1, filteredOptions.length - 1)"
                   @keydown.up.prevent="activeIndex = Math.max(activeIndex - 1, 0)"
                   @keydown.enter.prevent="if(filteredOptions[activeIndex]) toggle(filteredOptions[activeIndex].v)"
                   placeholder="Buscar..."
                   class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-indigo-400">
        </div>

        <div class="max-h-80 overflow-y-auto">
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
                         @click="toggle(opt.v)"
                         @mouseenter="activeIndex = index"
                         class="px-8 py-2.5 text-[13px] flex justify-between items-center border-b border-slate-50 transition-colors cursor-pointer"
                         :class="{
                            'bg-indigo-600 text-white font-black': activeIndex === index,
                            'text-slate-700': activeIndex !== index,
                            'text-indigo-600 font-bold': opt.v === 'ND'
                         }">
                        <span x-text="opt.t" class="font-bold"></span>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
