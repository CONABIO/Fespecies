@props([
    'label',
    'modelMin' => '',
    'modelMax' => '',
    'modelPromedio' => '',
    'modelUnidad' => null,
    'optionsUnidad' => []
])

<div x-data="{
    init() {
        if (typeof {{ $modelMin }} !== 'undefined' && !this.minVal) {
            this.minVal = {{ $modelMin }} ?? '';
        }
        if (typeof {{ $modelMax }} !== 'undefined' && !this.maxVal) {
            this.maxVal = {{ $modelMax }} ?? '';
        }
        if (typeof {{ $modelPromedio }} !== 'undefined' && !this.promVal) {
            this.promVal = {{ $modelPromedio }} ?? '';
        }
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

    <div @class([
        'grid grid-cols-1 gap-3 items-center',
        'sm:grid-cols-[1fr_1fr_1fr_auto]' => !empty($modelUnidad),
        'sm:grid-cols-3' => empty($modelUnidad),
    ])>

        <div class="flex items-center justify-between bg-white px-3 py-2 rounded-xl border border-slate-200 shadow-2xs">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">De</span>
            <input type="number" step="any" min="0"
                   x-model="minVal"
                   @input="validarValor('min', $event)"
                   placeholder="0"
                   class="w-20 text-right bg-transparent text-slate-700 font-semibold text-sm focus:outline-none">
        </div>

        <div class="flex items-center justify-between bg-white px-3 py-2 rounded-xl border border-slate-200 shadow-2xs">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">A</span>
            <input type="number" step="any" min="0"
                   x-model="maxVal"
                   @input="validarValor('max', $event)"
                   placeholder="0"
                   class="w-20 text-right bg-transparent text-slate-700 font-semibold text-sm focus:outline-none">
        </div>

        <div class="flex items-center justify-between bg-indigo-50/50 px-3 py-2 rounded-xl border border-indigo-100/80 shadow-2xs">
            <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Promedio</span>
            <input type="text"
                   x-model="promVal"
                   readonly
                   placeholder="0"
                   class="w-20 text-right bg-transparent text-indigo-900 font-bold text-sm focus:outline-none cursor-default">
        </div>

        @if(!empty($modelUnidad))
        <div class="bg-white px-3 py-1.5 rounded-xl border border-slate-200 shadow-2xs flex items-center gap-2 h-[42px]">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Unidad</span>
            <select x-model="{{ $modelUnidad }}" class="bg-transparent text-slate-700 font-semibold text-xs focus:outline-none cursor-pointer pr-2">
                <option value="">Seleccionar...</option>
                @foreach($optionsUnidad as $key => $text)
                    <option value="{{ $key }}">{{ $text }}</option>
                @endforeach
            </select>
        </div>
        @endif

    </div>
</div>
