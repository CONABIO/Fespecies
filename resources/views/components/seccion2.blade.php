<div x-show="step === 2" x-cloak x-transition:enter="transition ease-out duration-300" class="space-y-8 pt-10">

    <div class="max-w-3xl mx-auto space-y-12">
        <h2 class="text-[30px] font-black text-slate-800 tracking-tight">
            II. Distribución de la especie
        </h2>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
            <div class="flex flex-col">
                <label class="text-[22px] font-bold text-slate-700 tracking-tight mb-4">1. Distribución mundial:</label>
                <label class="text-[16px] font-bold text-slate-800 mb-2">a) Selecciona país(es):</label>

                @include('components.multi-select', [
                    'model' => 'form.paises_seleccionados',
                    'options' => 'paisesOptions.map(p => p.nombrepais)',
                    'placeholder' => 'Selecciona pais(es)'
                ])

                <label class="text-[16px] font-bold text-slate-700 tracking-tight mt-6 mb-2">Información adicional</label>
                <textarea id="infoAddDistribucionMundialPais" class="w-full rounded-2xl border border-slate-200 p-4 text-sm"
                    placeholder="Información adicional de pais(es)"></textarea>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100"
            x-show="form.paises_seleccionados.includes('México')" x-transition>

            <h3 class="text-[22px] font-bold text-slate-700 tracking-tight mb-6">2. Distribución histórica en México:</h3>

            <div class="space-y-6">
                <div class="flex flex-col">
                    <label class="text-[16px] font-bold text-slate-800 mb-2">a) Estado(s):</label>

                    @include('components.multi-select', [
                        'model' => 'form.estados_seleccionados',
                        'options' => 'estadosOptions.map(e => e.nombreEstado)',
                        'placeholder' => 'Selecciona estado(s)',
                    ])

                    <label class="text-[16px] font-bold text-slate-700 tracking-tight mt-6 mb-2">Información adicional</label>
                    <textarea id="infoAddDistribucionMundialEstado" class="w-full rounded-2xl border border-slate-200 p-4 text-sm mb-6"  placeholder="Información adicional de estado(s)"></textarea>
                </div>

                <div class="flex flex-col" x-show="form.estados_seleccionados.length > 0" x-transition>
                    <label class="text-[16px] font-bold text-slate-800 mb-2">b) Municipio(s):</label>

                    @include('components.multi-select', [
                        'model' => 'form.municipios_seleccionados',
                        'options' => "municipiosOptions",
                        'placeholder' => 'Selecciona municipio(s)'
                    ])

                    <label class="text-[16px] font-bold text-slate-700 tracking-tight mt-6 mb-2">Información adicional</label>
                    <textarea id="infoAddDistribucionMundialMunicipio" class="w-full rounded-2xl border border-slate-200 p-4 text-sm" placeholder="Información adicional de municipio(s)"></textarea>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 space-y-10">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <label class="text-[22px] font-bold text-slate-700 tracking-tight">3. Distribución potencial en México:</label>
                    <div class="flex bg-slate-100 p-1 rounded-xl border border-slate-200">
                        <label class="flex items-center px-6 py-2 rounded-lg cursor-pointer transition-all"
                            :class="form.siNoPotencial == '1' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400'">
                            <input type="radio" x-model="form.siNoPotencial" value="1" class="hidden"><span class="text-[10px] font-black">SÍ</span>
                        </label>
                        <label class="flex items-center px-6 py-2 rounded-lg cursor-pointer transition-all"
                            :class="form.siNoPotencial == '0' ? 'bg-white text-rose-500 shadow-sm' : 'text-slate-400'">
                            <input type="radio" x-model="form.siNoPotencial" value="0" class="hidden"><span class="text-[10px] font-black">NO</span>
                        </label>
                    </div>
                </div>
                <div x-show="form.siNoPotencial == '1'" x-transition>
                    <textarea id="infoAddDistPotMex" class="w-full rounded-2xl border border-slate-200 p-4 text-sm" placeholder="Informacion de distribución potencial"></textarea>
                </div>
            </div>

            <div class="h-px bg-slate-100"></div>

            <div>
                <div class="flex items-center justify-between mb-6">
                    <label class="text-[22px] font-bold text-slate-700 tracking-tight">4. Endemismo:</label>
                    <div class="flex bg-slate-100 p-1 rounded-xl border border-slate-200">
                        <label class="flex items-center px-6 py-2 rounded-lg cursor-pointer transition-all"
                            :class="form.siNoEndemismo == '1' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400'">
                            <input type="radio" x-model="form.siNoEndemismo" value="1" class="hidden"><span class="text-[10px] font-black">SÍ</span>
                        </label>
                        <label class="flex items-center px-6 py-2 rounded-lg cursor-pointer transition-all"
                            :class="form.siNoEndemismo == '0' ? 'bg-white text-rose-500 shadow-sm' : 'text-slate-400'">
                            <input type="radio" x-model="form.siNoEndemismo" value="0" class="hidden"><span class="text-[10px] font-black">NO</span>
                        </label>
                    </div>
                </div>
                <div x-show="form.siNoEndemismo == '1'" x-transition class="space-y-4">
                    <div class="flex flex-col">
                        <label class="text-[16px] font-bold text-slate-800 mb-2">a) Endémica a:</label>
                        <input type="text" x-model="form.endemica_a"
                            class="px-5 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm font-bold text-indigo-900 outline-none focus:border-indigo-400 transition-all mb-4"
                            placeholder="Ej. Sierra Madre Oriental">
                        <label class="text-[16px] font-bold text-slate-700 tracking-tight mb-2">Información adicional</label>
                        <textarea id="infoAddEndemismo" class="w-full rounded-2xl border border-slate-200 p-4 text-sm" placeholder="Informacion adicional de endemismo"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
