<div
    x-data="{
        open: false,
        filter: '',
        activeIndex: 0,
        optionsList: [],
        optionsMap: {},
        id: 'select-' + Math.random().toString(36).substr(2, 9),

        refresh(dataKey) {
            let rawItems = [];
            try {
                if (typeof dataKey === 'string' && dataKey.includes('.map')) {
                    rawItems = new Function('return ' + 'this.' + dataKey).call(this.$data) || [];
                } else if (typeof dataKey === 'string') {
                    rawItems = this.$data[dataKey] || this[dataKey] || window[dataKey] || [];
                } else if (Array.isArray(dataKey)) {
                    rawItems = dataKey;
                }
            } catch(e) { rawItems = []; }

            const newList = [];
            const newMap = {};

            for (let i = 0; i < rawItems.length; i++) {
                const o = rawItems[i];
                if (!o) continue;

                const v = String(o.v ?? o.idopcion ?? o.id ?? o);
                const t = String(o.t ?? o.descn1 ?? o.descripcion ?? o.nombre ?? o);
                const g = o.g ?? o.nombreEstado ?? null;

                newList.push({ v, t, g });
                newMap[v] = t;
                newMap[Number(v)] = t;
                newMap[String(v)] = t;
            }
            this.optionsList = newList;
            this.optionsMap = newMap;
        },

        init() {
            this.refresh('{{ $options }}');

            if ('{{ $options }}'.includes('municipiosOptions')) {
                this.$watch('municipiosOptions', () => this.refresh('municipiosOptions'));
            }

            if (typeof '{{ $options }}' === 'string' && this.$data['{{ $options }}']) {
                this.$watch('$data.{{ $options }}', () => this.refresh('{{ $options }}'));
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

            this.$watch('filter', () => { this.activeIndex = 0; });
        },

        get selectedIds() {
            const val = {{ $model }};
            return Array.isArray(val) ? val.map(String) : (val ? [String(val)] : []);
        },

        get filteredOptions() {
            if (!this.open) return [];
            const f = this.filter.toLowerCase();
            const selectedSet = new Set(this.selectedIds);
            const results = [];
            let count = 0;

            for (let i = 0; i < this.optionsList.length; i++) {
                const item = this.optionsList[i];
                if (selectedSet.has(item.v)) continue;

                if (!f || item.t.toLowerCase().includes(f) || (item.g && item.g.toLowerCase().includes(f))) {
                    results.push(item);
                    count++;
                }
                if (count >= 40) break;
            }
            return results;
        },

        toggle(id) {
            id = String(id);
            let current = [...this.selectedIds].map(String);
            const index = current.indexOf(id);
            if (index === -1) {
                current.push(id);
            } else {
                current.splice(index, 1);
            }
            {{ $model }} = current;
        },

        remove(id) {
            id = String(id);
            {{ $model }} = this.selectedIds.filter(i => String(i) !== id);
        }
    }"
    class="relative w-full"
    @click.away="open = false"
>
    <div @click="open = !open; if(open) $nextTick(() => $refs.searchInput.focus());"
         class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 cursor-pointer shadow-inner hover:border-indigo-300 transition-all">

        <template x-if="selectedIds.length === 0">
            <span class="text-[14px] text-slate-400 p-2 font-black">{{ $placeholder }}</span>
        </template>

        <template x-for="id in selectedIds" :key="id">
            <div class="bg-indigo-600 text-white text-[11px] font-black px-3 py-1.5 rounded-xl flex items-center shadow-md">
                <span x-text="optionsMap[id] || optionsMap[Number(id)] || optionsMap[String(id)] || id"></span>
                <button type="button" @click.stop="remove(id)" class="ml-2 hover:text-indigo-200 font-black text-lg">&times;</button>
            </div>
        </template>
    </div>

    <div x-show="open" x-cloak x-transition
         class="absolute z-[110] w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden">

        <div class="p-2 border-b border-slate-100 bg-slate-50">
            <input x-ref="searchInput"
                   type="text"
                   x-model.debounce.250ms="filter"
                   @keydown.down.prevent="activeIndex = Math.min(activeIndex + 1, filteredOptions.length - 1)"
                   @keydown.up.prevent="activeIndex = Math.max(activeIndex - 1, 0)"
                   @keydown.enter.prevent="if(filteredOptions[activeIndex]) toggle(filteredOptions[activeIndex].v)"
                   placeholder="Buscar..."
                   class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-indigo-400">
        </div>

        <div class="max-h-64 overflow-y-auto">
            <template x-for="(opt, index) in filteredOptions" :key="opt.v">
                <div>
                    <template x-if="opt.g && (index === 0 || opt.g !== filteredOptions[index-1].g)">
                        <div class="bg-slate-100 px-4 py-1 text-[10px] font-black text-indigo-500 tracking-widest sticky top-0 z-10">
                            <span x-text="opt.g"></span>
                        </div>
                    </template>

                    <div :id="id + '-opt-' + index"
                         @click="toggle(opt.v)"
                         @mouseenter="activeIndex = index"
                         class="px-8 py-2 text-[13px] flex justify-between items-center border-b border-slate-50 transition-colors cursor-pointer"
                         :class="{
                            'bg-indigo-600 text-white font-black': activeIndex === index,
                            'text-slate-700 hover:bg-slate-50': activeIndex !== index
                         }">
                        <span x-text="opt.t" class="font-medium"></span>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
