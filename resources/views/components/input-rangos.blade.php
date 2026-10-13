@props([
    'label',
    'modelMin' => '',
    'modelMax' => '',
    'modelPromedio' => '',
    'modelUnidad' => null,
    'optionsUnidad' => [],
    'showPromedio' => true
])

<div x-data="{
    init() {
        if (typeof {{ $modelMin }} !== 'undefined' && !this.minVal) {
            this.minVal = {{ $modelMin }} ?? '';
        }
        if (typeof {{ $modelMax }} !== 'undefined' && !this.maxVal) {
            this.maxVal = {{ $modelMax }} ?? '';
        }
        @if($showPromedio)
        if (typeof {{ $modelPromedio }} !== 'undefined' && !this.promVal) {
            this.promVal = {{ $modelPromedio }} ?? '';
        }
        @endif
    },
    minVal: @js(data_get($__env->getShared(), $modelMin, '')),
    maxVal: @js(data_get($__env->getShared(), $modelMax, '')),
    promVal: @js(data_get($__env->getShared(), $modelPromedio, '')),

    validarValor(tipo, event) {
        let val = event.target.value;

        if (val === '') {
            this.actualizarModelos(tipo, '');
            this.calcularPromedio();
            return;
        }

        let num = parseFloat(val);

        if (num === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Valor no permitido',
                text: 'No se puede ingresar el valor 0.',
                confirmButtonColor: '#4f46e5'
            });
            this.actualizarModelos(tipo, '');
            event.target.value = '';
            this.calcularPromedio();
            return;
        }

        if (num < 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Valor no permitido',
                text: 'No se pueden ingresar números negativos.',
                confirmButtonColor: '#4f46e5'
            });
            this.actualizarModelos(tipo, '');
            event.target.value = '';
            this.calcularPromedio();
            return;
        }

        this.actualizarModelos(tipo, val);

        let min = parseFloat(this.minVal);
        let max = parseFloat(this.maxVal);

        if (!isNaN(min) && !isNaN(max) && min > max) {
            Swal.fire({
                icon: 'error',
                title: 'Rango inválido',
                text: 'El valor mínimo (De) no puede ser mayor que el máximo (A).',
                confirmButtonColor: '#4f46e5'
            });
            this.actualizarModelos(tipo, '');
            event.target.value = '';
            this.calcularPromedio();
            return;
        }

        this.calcularPromedio();
    },

    actualizarModelos(tipo, val) {
        if (tipo === 'min') {
            this.minVal = val;
            {{ $modelMin }} = val;
        }
        if (tipo === 'max') {
            this.maxVal = val;
            {{ $modelMax }} = val;
        }
    },

    calcularPromedio() {
        @if(!$showPromedio) return; @endif

        let min = parseFloat(this.minVal);
        let max = parseFloat(this.maxVal);

        if (isNaN(min) || isNaN(max)) {
            this.promVal = '';
            {{ $modelPromedio }} = '';
            return;
        }

        let prom = (min + max) / 2;
        let resultado = Number.isInteger(prom) ? prom : prom.toFixed(2);

        this.promVal = resultado;
        {{ $modelPromedio }} = resultado;
    }
}"
class="bg-slate-50/60 border border-slate-200/80 rounded-2xl p-4 transition-all hover:bg-slate-50 hover:border-slate-300 space-y-3">

    <label class="text-sm font-bold text-slate-800 block">{{ $label }}</label>

    <div class="grid grid-cols-1 sm:grid-cols-2 @if($showPromedio && !empty($modelUnidad)) lg:grid-cols-4 @elseif($showPromedio || !empty($modelUnidad)) lg:grid-cols-3 @endif gap-3 items-center">

        <div class="relative bg-white px-3.5 py-2 rounded-xl border border-slate-200 shadow-2xs w-full flex items-center gap-3 focus-within:border-indigo-500 transition-all">
            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </div>
            <div class="flex flex-col w-full">
                <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest">Mínimo (De)</span>
                <input type="number" step="any" min="0"
                       x-model="minVal"
                       @change="validarValor('min', $event)"
                       @input="actualizarModelos('min', $event.target.value)"
                       placeholder="Ej. 1"
                       class="w-full bg-transparent text-slate-800 font-bold text-base focus:outline-none pt-0.5">
            </div>
        </div>

        <div class="relative bg-white px-3.5 py-2 rounded-xl border border-slate-200 shadow-2xs w-full flex items-center gap-3 focus-within:border-indigo-500 transition-all">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
            </div>
            <div class="flex flex-col w-full">
                <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest">Máximo (A)</span>
                <input type="number" step="any" min="0"
                       x-model="maxVal"
                       @change="validarValor('max', $event)"
                       @input="actualizarModelos('max', $event.target.value)"
                       placeholder="Ej. 100"
                       class="w-full bg-transparent text-slate-800 font-bold text-base focus:outline-none pt-0.5">
            </div>
        </div>

        @if($showPromedio)
        <div class="relative bg-indigo-50/50 px-3.5 py-2 rounded-xl border border-indigo-100/80 shadow-2xs w-full flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="flex flex-col w-full">
                <span class="text-[9px] font-extrabold text-indigo-500 uppercase tracking-widest">Promedio</span>
                <input type="text"
                       x-model="promVal"
                       readonly
                       placeholder="0"
                       class="w-full bg-transparent text-indigo-900 font-black text-base focus:outline-none cursor-default pt-0.5">
            </div>
        </div>
        @endif

        @if(!empty($modelUnidad))
        <div class="bg-white px-3.5 py-2 rounded-xl border border-slate-200 shadow-2xs flex items-center gap-3 w-full h-[55px]">
            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                </svg>
            </div>
            <div class="flex flex-col w-full justify-center">
                <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest">Unidad</span>
                <select x-model="{{ $modelUnidad }}" class="bg-transparent text-slate-800 font-bold text-xs focus:outline-none cursor-pointer pt-0.5 w-full">
                    <option value="">Seleccionar...</option>
                    @foreach($optionsUnidad as $key => $text)
                        <option value="{{ $key }}">{{ $text }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif

    </div>
</div>
