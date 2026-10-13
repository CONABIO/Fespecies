<div x-show="step === 10" x-cloak x-transition:enter="transition ease-out duration-300" class="space-y-8 pt-10">
    <div class="max-w-3xl mx-auto space-y-12">
        <h2 class="text-[30px] font-black text-slate-800 tracking-tight">
            X. Necesidades de información
        </h2>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <h3 class="text-[20px] font-bold text-slate-700 tracking-tight">1. Diagnóstico sobre las necesidades de información de la especie</h3>
            <div class="flex flex-col">
                <textarea id="diagnosticoespecie_editor" x-model="form.diagnosticoespecie" class="w-full rounded-2xl border border-slate-200 p-4 text-sm" rows="6" placeholder="Escriba el diagnóstico..."></textarea>
            </div>
        </div>

        <div class="mt-16 flex justify-end pb-24"></div>
    </div>
</div>
