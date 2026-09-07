<div
    x-data="{
        open: false,
        filter: '',
        activeIndex: 0,
        optionsList: [],
        optionsMap: {},
        id: 'select-' + Math.random().toString(36).substr(2, 9),

        init() {
            let raw = {{ is_string($options) ? $options : json_encode($options) }};
            if (typeof raw === 'string') raw = window[raw] || [];

            raw.forEach(o => {
                let item = {
                    v: o.v ?? o.municipioId ?? o.id ?? String(o),
                    t: o.t ?? o.nombreMunicipio ?? o.nombre ?? String(o),
                    g: o.g ?? o.nombreEstado ?? null
                };
                this.optionsList.push(item);
                this.optionsMap[item.v] = item.t;
            });

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

        get selectedIds() {
            let val = {{ $model }};
            return Array.isArray(val) ? val : [];
        },

        get filteredOptions() {
            if (!this.filter) return this.optionsList.slice(0, 80);
            let f = this.filter.toLowerCase();
            return this.optionsList.filter(i =>
                i.t.toLowerCase().includes(f) || (i.g && i.g.toLowerCase().includes(f))
            ).slice(0, 100);
        },

        toggle(id) {
            let current = [...this.selectedIds];
            if (current.includes(id)) return;
            current.push(id);
            {{ $model }} = current;
            this.filter = '';
            this.$refs.searchInput.focus();
        },

        remove(id) {
            let current = [...this.selectedIds];
            {{ $model }} = current.filter(i => i != id);
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
            <input x-ref="searchInput" type="text" x-model="filter"
                @keydown.down.prevent="activeIndex = Math.min(activeIndex + 1, filteredOptions.length - 1)"
                @keydown.up.prevent="activeIndex = Math.max(activeIndex - 1, 0)"
                @keydown.enter.prevent="if(filteredOptions[activeIndex]) toggle(filteredOptions[activeIndex].v)"
                placeholder="Buscar..."
                class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-indigo-400">
        </div>

        <div x-ref="listContainer" class="max-h-80 overflow-y-auto">
            <template x-for="(opt, index) in filteredOptions" :key="opt.v">
                <div>
                    <template x-if="opt.g && (index === 0 || opt.g !== filteredOptions[index-1].g)">
                        <div class="bg-slate-100 px-4 py-1.5 text-[10px] font-black text-indigo-500 uppercase tracking-widest border-y border-slate-200 sticky top-0 z-10">
                            <span x-text="opt.g"></span>
                        </div>
                    </template>

                    <div :id="id + '-opt-' + index"
                        @click="toggle(opt.v)"
                        @mouseenter="activeIndex = index"
                        class="px-8 py-2.5 text-[13px] flex justify-between items-center border-b border-slate-50 transition-colors cursor-pointer"
                        :class="{
                            'bg-indigo-600 text-white font-black': activeIndex === index && !selectedIds.includes(opt.v),
                            'text-slate-700 hover:bg-slate-50': activeIndex !== index && !selectedIds.includes(opt.v),
                            'bg-slate-50 text-slate-400 italic opacity-60 cursor-not-allowed': selectedIds.includes(opt.v)
                        }">

                        <span x-text="opt.t"></span>

                        <template x-if="selectedIds.includes(opt.v)">
                            <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
