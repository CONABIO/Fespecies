<div
    x-data="{
        open: false,
        filter: '',
        activeIndex: 0,
        init() {
            this.$watch('filter', () => this.activeIndex = 0);
            this.$watch('activeIndex', (val) => {
                if (!this.open) return;
                this.$nextTick(() => {
                    const container = this.$refs.listContainer;
                    const items = container.querySelectorAll('.option-item');
                    if (items[val]) {
                        items[val].scrollIntoView({ block: 'nearest', behavior: 'auto' });
                    }
                });
            });
        },
        get allOptions() {
            let raw = {{ is_string($options) ? $options : json_encode($options) }};
            return raw.map(o => {
                if (typeof o === 'object' && o !== null) {
                    return {
                        v: o.v ?? o.value ?? o.id ?? o.municipioId ?? o.descripcion ?? o.descn1 ?? o.descn2 ?? o.tipoecosistema ?? o.descripcionSubVegetacion ?? o,
                        t: o.t ?? o.text ?? o.nombre ?? o.nombreMunicipio ?? o.descripcion ?? o.descn1 ?? o.descn2 ?? o.tipoecosistema ?? o.descripcionSubVegetacion ?? o
                    };
                }
                return { v: o, t: o };
            });
        },
        get selectedIds() {
            let val = {{ $model }};
            return Array.isArray(val) ? val : [];
        },
        get filteredOptions() {
            let f = this.filter.toLowerCase();
            return this.allOptions.filter(o =>
                String(o.t).toLowerCase().includes(f) &&
                !this.selectedIds.includes(o.v)
            ).slice(0, 1000);
        },
        toggle(id) {
            let current = [...this.selectedIds];
            if (current.includes(id)) return;
            current.push(id);
            {{ $model }} = current;
            this.filter = '';
        },
        remove(id) {
            let current = [...this.selectedIds];
            let index = current.indexOf(id);
            if (index > -1) {
                current.splice(index, 1);
                {{ $model }} = current;
            }
        },
        resetActive() { this.activeIndex = 0; }
    }"
    class="relative w-full"
    @click.away="open = false"
    @keydown.escape="open = false"
>
    <div @click="open = !open; if(open) { resetActive(); $nextTick(() => $refs.searchInput.focus()); }"
         class="min-h-[50px] p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 flex flex-wrap gap-2 cursor-pointer shadow-inner hover:border-indigo-300 transition-all">
        <template x-if="selectedIds.length === 0">
            <span class="text-[14px] text-slate-400 p-2 font-black">{{ $placeholder }}</span>
        </template>
        <template x-for="id in selectedIds" :key="id">
            <div class="bg-indigo-600 text-white text-[10px] font-black px-3 py-1.5 rounded-xl flex items-center shadow-md">
                <span x-text="allOptions.find(o => o.v == id)?.t || id"></span>
                <button type="button" @click.stop="remove(id)" class="ml-2 hover:text-indigo-200 font-black">×</button>
            </div>
        </template>
    </div>

    <div x-show="open" x-cloak x-transition
         class="absolute z-[110] w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden">

        <div class="p-2 border-b border-slate-100 bg-slate-50">
            <input x-ref="searchInput" type="text" x-model="filter" @input="resetActive()"
                @keydown.down.prevent="if(filteredOptions.length > 0) activeIndex = (activeIndex + 1) % filteredOptions.length"
                @keydown.up.prevent="if(filteredOptions.length > 0) activeIndex = (activeIndex - 1 + filteredOptions.length) % filteredOptions.length"
                @keydown.enter.prevent="if(filteredOptions[activeIndex]) toggle(filteredOptions[activeIndex].v)"
                placeholder="Buscar..."
                class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-indigo-400">
        </div>

        <div class="max-h-60 overflow-y-auto" x-ref="listContainer">
            <template x-for="(opt, index) in filteredOptions" :key="opt.v">
                <div @click="toggle(opt.v); $refs.searchInput.focus(); activeIndex = index;"
                    @mouseenter="activeIndex = index"
                    class="option-item px-5 py-2.5 text-xs flex justify-between items-center border-b border-slate-50 transition-colors cursor-pointer"
                    :class="activeIndex === index ? 'bg-indigo-600 text-white font-black' : 'text-slate-600 hover:bg-slate-50'">
                    <span x-text="opt.t"></span>
                </div>
            </template>
            <div x-show="filteredOptions.length === 0" class="px-5 py-3 text-xs text-slate-400 italic">
                No hay más opciones disponibles...
            </div>
        </div>
    </div>
</div>
