<div x-show="step === 9" x-cloak x-transition:enter="transition ease-out duration-300" class="space-y-8 pt-10">
    <div class="max-w-3xl mx-auto space-y-12">
        <h2 class="text-[30px] font-black text-slate-800 tracking-tight">
            IX. Especies prioritarias para la conservación
        </h2>

        <!-- Pregunta 58 -->
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <h3 class="text-[20px] font-bold text-slate-700 tracking-tight">1. ¿Se encuentra enlistada como especie prioritaria?</h3>
            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                    <input type="radio" x-model="form.snprioritaria" value="Sí" class="w-4 h-4 text-indigo-600 focus:ring-indigo-500"> Sí
                </label>
                <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                    <input type="radio" x-model="form.snprioritaria" value="no" class="w-4 h-4 text-indigo-600 focus:ring-indigo-500"> No
                </label>
            </div>

            <div x-show="form.snprioritaria === 'Sí'" x-cloak class="space-y-6 pt-4 border-t border-slate-100">
                <div class="flex flex-col space-y-3">
                    <label class="text-[16px] font-bold text-slate-800">a) ¿En qué listado se encuentra?:</label>
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                            <input type="radio" x-model="form.listaprioritaria" value="DOF" class="w-4 h-4 text-indigo-600 focus:ring-indigo-500"> DOF
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                            <input type="radio" x-model="form.listaprioritaria" value="CONABIO" class="w-4 h-4 text-indigo-600 focus:ring-indigo-500"> CONABIO
                        </label>
                    </div>
                </div>

                <div class="flex flex-col space-y-3">
                    <label class="text-[16px] font-bold text-slate-800">b) Prioridad resultante del proceso:</label>
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                            <input type="radio" x-model="form.prioridadresul" value="Alta" class="w-4 h-4 text-indigo-600 focus:ring-indigo-500"> Alta
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                            <input type="radio" x-model="form.prioridadresul" value="Media" class="w-4 h-4 text-indigo-600 focus:ring-indigo-500"> Media
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                            <input type="radio" x-model="form.prioridadresul" value="Baja" class="w-4 h-4 text-indigo-600 focus:ring-indigo-500"> Baja
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-6">
            <h3 class="text-[20px] font-bold text-slate-700 tracking-tight">2. Justificación del estatus de especie prioritaria</h3>
            <div class="flex flex-col">
                <textarea id="justificaprioritaria_editor" x-model="form.justificaprioritaria" class="w-full rounded-2xl border border-slate-200 p-4 text-sm" rows="6" placeholder="Escriba la justificación..."></textarea>
            </div>
        </div>

        <div class="mt-16 flex justify-end pb-24"></div>
    </div>
</div>
