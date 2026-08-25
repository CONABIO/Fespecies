<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fespecies</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        nav[role="navigation"] a,
        nav[role="navigation"] span[aria-disabled="true"] span,
        nav[role="navigation"] span[aria-current="page"] span {
            border-radius: 6px !important;
            margin: 0 2px !important;
            border: 1px solid #e5e7eb !important;
            padding: 8px 14px !important;
            transition: all 0.2s;
        }

        nav[role="navigation"] span[aria-current="page"] span {
            background-color: #003D4A !important;
            color: white !important;
            border-color: #003D4A !important;
        }

        nav[role="navigation"] a:hover {
            background-color: #f9fafb !important;
            color: #003D4A !important;
        }

        nav[role="navigation"] div.flex.justify-between.flex-1.sm\:hidden {
            display: none;
        }

        .shadow-sm {
            shadow: none !important;
        }

        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <x-header />
    <main class="max-w-[1400px] mx-auto p-6">
        <form method="GET" action="{{ url()->current() }}" id="mainFilterForm"
            x-data="{
                query: '{{ request('q') }}',
                results: [],
                showResults: false,
                loading: false,
                selectedIndex: -1,

                init() {
                    // ESTO ES LO QUE HACE EL SCROLL REAL
                    this.$watch('selectedIndex', index => {
                        if (index >= 0 && this.$refs.resultsContainer) {
                            this.$nextTick(() => {
                                const container = this.$refs.resultsContainer;
                                const activeItem = container.children[index];
                                if (activeItem) {
                                    activeItem.scrollIntoView({
                                        block: 'nearest',
                                        behavior: 'smooth'
                                    });
                                }
                            });
                        }
                    });
                },

                buscarCatalogo() {
                    if (this.query.length < 2) {
                        this.results = [];
                        this.showResults = false;
                        this.selectedIndex = -1;
                        return;
                    }
                    this.loading = true;
                    fetch(`/buscar-dashboard?q=${encodeURIComponent(this.query)}`)
                        .then(res => res.json())
                        .then(data => {
                            this.results = data;
                            this.showResults = (data.length > 0);
                            this.loading = false;
                            this.selectedIndex = -1;
                        })
                        .catch(() => { this.loading = false; });
                },
                seleccionar(taxon) {
                    this.query = taxon;
                    this.showResults = false;
                    $nextTick(() => { document.getElementById('mainFilterForm').submit(); });
                }
            }">

            <input type="hidden" name="q" :value="query">

            <div class="flex items-center justify-between gap-6 mb-12">
                <div class="flex-shrink-0"></div>
                <div class="flex-1 max-w-3xl w-full" @click.away="showResults = false">

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none z-30">
                            <svg x-show="!loading" class="h-6 w-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <svg x-show="loading" x-cloak class="animate-spin h-5 w-5 text-indigo-500" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>

                        <input type="text" x-model="query"
                            @input.debounce.400ms="buscarCatalogo()"
                            @keydown.down.prevent="if(results.length > 0) selectedIndex = (selectedIndex + 1) % results.length"
                            @keydown.up.prevent="if(results.length > 0) selectedIndex = (selectedIndex - 1 + results.length) % results.length"
                            @keydown.enter.prevent="selectedIndex >= 0 ? seleccionar(results[selectedIndex].taxon) : document.getElementById('mainFilterForm').submit()"
                            @keydown.escape="showResults = false"
                            placeholder="Escribe el nombre de la especie..." autocomplete="off"
                            class="block w-full pl-16 pr-8 py-4 bg-white border-[3px] border-indigo-500 rounded-full text-xl font-medium text-slate-700 placeholder-slate-300 focus:outline-none focus:ring-8 focus:ring-indigo-500/5 transition-all shadow-2xl relative z-20">

                        <div x-show="showResults" x-cloak x-transition x-ref="resultsContainer"
                            class="absolute top-full left-0 right-0 mt-3 bg-white rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-gray-100 overflow-hidden z-50 max-h-[450px] overflow-y-auto">
                            <template x-for="(item, index) in results" :key="item.especieId || index">
                                <div @click="seleccionar(item.taxon)"
                                    @mouseenter="selectedIndex = index"
                                    :class="{ 'bg-indigo-600 text-white': selectedIndex === index, 'text-slate-900': selectedIndex !== index }"
                                    class="px-8 py-4 cursor-pointer border-b border-gray-50 transition-colors group">
                                    <div class="flex flex-col text-left">
                                        <span class="font-bold text-lg italic"
                                              :class="selectedIndex === index ? 'text-white' : 'text-slate-900 group-hover:text-indigo-600'"
                                              x-text="item.taxon"></span>
                                        <span class="text-xs font-semibold tracking-widest"
                                              :class="selectedIndex === index ? 'text-indigo-100' : 'text-slate-400'"
                                              x-text="item.AutorTaxon"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('form.index') }}"
                        class="flex items-center gap-3 px-8 py-4 bg-emerald-500 hover:bg-emerald-600 text-white text-[18px] font-bold  tracking-widest rounded-full shadow-xl shadow-emerald-200 transition-all hover:-translate-y-1 active:translate-y-0 whitespace-nowrap">
                        <span>Ingresar nueva ficha</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </a>
                </div>
            </div>

            @if(request()->filled('q') || request()->anyFilled(['f_id', 'f_tipo', 'f_familia', 'f_genero', 'f_especie', 'f_infraespecie']))
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6" x-data="{ selectedId: null }">
                <div class="flex justify-between items-center mb-6">
                    <a href="{{ url()->current() }}"
                        class="flex items-center gap-2 px-4 py-2 bg-white hover:bg-red-50 text-gray-500 text-[16px] font-bold rounded-lg border border-gray-200 transition-all shadow-sm">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Borrar Filtros
                    </a>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-sm">
                        <thead class="bg-[#003D4A] text-white text-[16px] font-bold tracking-widest">
                            <tr>
                                <th class="p-4 border-r border-white/10 text-center">Id de Ficha</th>
                                <th class="p-4 border-r border-white/10 text-center">Tipo Ficha</th>
                                <th class="p-4 border-r border-white/10 text-center">Familia</th>
                                <th class="p-4 border-r border-white/10 text-center">Género</th>
                                <th class="p-4 border-r border-white/10 text-center">Especie</th>
                                <th class="p-4 border-r border-white/10 text-center">Infraespecie</th>
                                <th class="p-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse ($taxones as $taxon)
                                <tr @click="selectedId = (selectedId === {{ $taxon->especieId }} ? null : {{ $taxon->especieId }})"
                                    :class="{ 'bg-emerald-50': selectedId === {{ $taxon->especieId }} }"
                                    class="border-b border-gray-100 hover:bg-slate-50 transition-colors cursor-pointer text-center">
                                    <td class="p-4 border-r border-gray-50 text-blue-600 font-bold">{{ $taxon->especieId }}</td>
                                    <td class="p-4 border-r border-gray-50">{{ $taxon->tipoficha }}</td>
                                    <td class="p-4 border-r border-gray-50">{{ $taxon->familia }}</td>
                                    <td class="p-4 border-r border-gray-50 italic font-medium">{{ $taxon->genero }}</td>
                                    <td class="p-4 border-r border-gray-50 italic font-medium">{{ $taxon->especie }}</td>
                                    <td class="p-4 border-r border-gray-50 text-gray-400">{{ $taxon->infraespecie ?? '---' }}</td>
                                    <td class="p-2 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="/editar-ficha/{{ $taxon->especieId }}" class="w-10 h-10 rounded-full bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center transition-all shadow-md">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="p-12 text-center text-gray-400 italic bg-slate-50">No hay registros...</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-8 flex justify-center">{{ $taxones->links() }}</div>
            </div>
            @endif
        </form>
    </main>
</body>
</html>
