<div x-show="step === 8" x-cloak x-transition:enter="transition ease-out duration-300" class="space-y-8 pt-10">
    <div class="max-w-3xl mx-auto space-y-12">
        <h2 class="text-[30px] font-black text-slate-800 tracking-tight">
            VIII. Estado de conservación de la especie
        </h2>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <h3 class="text-[22px] font-bold text-slate-700 tracking-tight mb-2">1. Presiones o amenazas sobre la especie:</h3>
            <div class="flex flex-col space-y-3">
                <label class="text-[14px] font-bold text-slate-700">Seleccionar amenazas:</label>
                @include('components.multi-select', [
                    'model' => 'form.amenazas',
                    'options' => 'amenazasOptions',
                    'placeholder' => 'Seleccione las amenazas...'
                ])
            </div>
            <div class="flex flex-col pt-4">
                <label class="text-[14px] font-bold text-slate-800 mb-2">Descripción de presiones o amenazas:</label>
                <textarea id="presiones_editor" x-model="form.presiones" class="w-full rounded-2xl border border-slate-200 p-4 text-sm" rows="4" placeholder="Describa las presiones..."></textarea>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <h3 class="text-[22px] font-bold text-slate-700 tracking-tight mb-2">2. Tendencia poblacional:</h3>
            <div class="flex flex-col space-y-3">
                <label class="text-[14px] font-bold text-slate-700">Tendencia:</label>
                <select x-model="form.tendenciapobla" class="px-5 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm font-bold text-indigo-900 outline-none focus:border-indigo-400">
                    <option value="">Seleccionar >></option>
                    <option value="Estable">Estable</option>
                    <option value="Aumenta">Aumenta</option>
                    <option value="Decrece">Decrece</option>
                    <option value="ND">ND</option>
                    <option value="NA">NA</option>
                </select>
            </div>
            <div class="flex flex-col pt-4">
                <label class="text-[14px] font-bold text-slate-800 mb-2">Descripción de la tendencia poblacional:</label>
                <textarea id="tendenciapoblacion_editor" x-model="form.tendenciapoblacion" class="w-full rounded-2xl border border-slate-200 p-4 text-sm" rows="4" placeholder="Detalles de la tendencia..."></textarea>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <h3 class="text-[22px] font-bold text-slate-700 tracking-tight mb-2">3. Estado de conservación:</h3>
            <div class="flex flex-col">
                <textarea id="edoconservacion_editor" x-model="form.edoconservacion" class="w-full rounded-2xl border border-slate-200 p-4 text-sm" rows="4" placeholder="Descripción del estado de conservación..."></textarea>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <h3 class="text-[22px] font-bold text-slate-700 tracking-tight mb-2">4. Manejo, aprovechamiento y acciones de conservación:</h3>

            <div class="flex flex-col">
                <label class="text-[14px] font-bold text-slate-800 mb-2">a) Describir acciones de manejo y conservación de la especie:</label>
                <textarea id="addinfocons_editor" x-model="form.addinfocons" class="w-full rounded-2xl border border-slate-200 p-4 text-sm" rows="4" placeholder="Acciones de manejo..."></textarea>
            </div>

            <div class="h-px bg-slate-100"></div>

            <div class="flex flex-col space-y-3">
                <label class="text-[14px] font-bold text-slate-800">b) Esquema de manejo en el cual la especie es aprovechada o gestionada:</label>
                @include('components.multi-select', [
                    'model' => 'form.esquemamanejo',
                    'options' => 'esquemamanejoOptions',
                    'placeholder' => 'Seleccione el esquema de manejo...'
                ])
            </div>

            <div class="flex flex-col space-y-3 pt-4">
                <label class="text-[14px] font-bold text-slate-800">c) Indicar el tipo de aprovechamiento de la especie:</label>
                @include('components.multi-select', [
                    'model' => 'form.tipoaprovechamiento',
                    'options' => 'tipoaprovechamientoOptions',
                    'placeholder' => 'Seleccione el tipo de aprovechamiento...'
                ])
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <h3 class="text-[22px] font-bold text-slate-700 tracking-tight mb-2">5. Marco legal nacional e internacional</h3>

            <div class="flex flex-col">
                <label class="text-[14px] font-bold text-slate-800 mb-2">a) Regulaciones aplicables a la especie:</label>
                <textarea id="marcolegal_editor" x-model="form.marcolegal" class="w-full rounded-2xl border border-slate-200 p-4 text-sm" rows="4" placeholder="Regulaciones..."></textarea>
            </div>

            <div class="h-px bg-slate-100"></div>

            <div class="flex flex-col space-y-3">
                <label class="text-[14px] font-bold text-slate-800">b) Tipo de veda:</label>
                <select x-model="form.veda" class="px-5 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm font-bold text-indigo-900 outline-none focus:border-indigo-400">
                    <option value="">Seleccionar >></option>
                    <option value="Permanente">Permanente</option>
                    <option value="Permanente solo para pesca deportiva">Permanente solo para pesca deportiva</option>
                    <option value="Temporal fija">Temporal fija</option>
                </select>
            </div>

            <div class="flex flex-col pt-4">
                <label class="text-[14px] font-bold text-slate-800 mb-2">Información adicional sobre la veda:</label>
                <textarea id="infoaddveda_editor" x-model="form.infoaddveda" class="w-full rounded-2xl border border-slate-200 p-4 text-sm" rows="4" placeholder="Información adicional..."></textarea>
            </div>
        </div>

        <div class="mt-16 flex justify-end pb-24"></div>
    </div>
</div>
