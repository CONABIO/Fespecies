<div x-show="step === 6" x-cloak x-transition:enter="transition ease-out duration-300" class="space-y-8 pt-10">

    <div class="max-w-3xl mx-auto space-y-12">
        <h2 class="text-[30px] font-black text-slate-800 tracking-tight">
            VI. Genética de la especie
        </h2>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <h3 class="text-[22px] font-bold text-slate-700 tracking-tight mb-2">1. Variabilidad genética</h3>

            <div class="flex flex-col">
                <label class="text-[16px] font-bold text-slate-800 mb-2">a) Descripción de la variabilidad genética:</label>
                <textarea id="variabilidad_genetica_editor" x-model="form.variabilidad_genetica" class="w-full rounded-2xl border border-slate-200 p-4 text-sm" rows="4" placeholder="Descripción de la variabilidad genética..."></textarea>
            </div>

            <div class="h-px bg-slate-100"></div>

            <div class="flex flex-col">
                <label class="text-[16px] font-bold text-slate-800 mb-2">b) Marcadores utilizados para describir la variabilidad:</label>
                <textarea id="marcadores_variabilidad_editor" x-model="form.marcadores_variabilidad" class="w-full rounded-2xl border border-slate-200 p-4 text-sm" rows="4" placeholder="Marcadores utilizados..."></textarea>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <div class="flex flex-col">
                <label class="text-[22px] font-bold text-slate-700 tracking-tight mb-2">2. Secuencias publicadas para la especie:</label>
                <textarea id="secuencias_publicadas_editor" x-model="form.secuencias_publicadas" class="w-full rounded-2xl border border-slate-200 p-4 text-sm mt-2" rows="4" placeholder="Secuencias publicadas..."></textarea>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <div class="flex flex-col">
                <label class="text-[22px] font-bold text-slate-700 tracking-tight mb-2">3. MEXBOL/Código de barras:</label>
                <textarea id="mexbol_codigo_barras_editor" x-model="form.mexbol_codigo_barras" class="w-full rounded-2xl border border-slate-200 p-4 text-sm mt-2" rows="4" placeholder="Información de MEXBOL o código de barras..."></textarea>
            </div>
        </div>

        <div class="mt-16 flex justify-end pb-24"></div>
    </div>
</div>
