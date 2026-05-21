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
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <x-header />
    <main class="max-w-[1400px] mx-auto p-6">
        <div class="bg-white border border-gray-200 rounded shadow-sm p-4" x-data="{ selectedId: null }">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center space-x-4">
                    <a href="{{ url()->current() }}" class="flex items-center gap-2 px-3 py-1.5 bg-white hover:bg-gray-50 text-gray-600 text-[11px] font-bold uppercase tracking-wider rounded border border-gray-300 transition-colors shadow-sm">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Borrar Filtros
                    </a>
                </div>
                <div class="flex items-center space-x-6 text-sm text-blue-600"></div>
                <div class="flex space-x-3">
                    <a href="{{ route('form.index') }}" class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center hover:bg-green-600 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                            </path>
                        </svg>
                    </a>
                    <button
                       @click="if(selectedId) window.location.href = '/editar-ficha/' + selectedId"
                        :disabled="!selectedId"
                        :class="selectedId ? 'bg-blue-500 hover:bg-blue-600 opacity-100 cursor-pointer' : 'bg-blue-300 opacity-50 cursor-not-allowed'"
                        class="w-8 h-8 rounded-full text-white flex items-center justify-center shadow-sm transition-all"
                        title="Editar ficha seleccionada">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                    </button>
                    <button :disabled="!selectedId" :class="selectedId ? 'bg-red-500 hover:bg-red-600 opacity-100 cursor-pointer' : 'bg-red-300 opacity-50 cursor-not-allowed'" class="w-8 h-8 rounded-full text-white flex items-center justify-center shadow-sm transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>

            <form method="GET" action="{{ url()->current() }}">
                <div x-data="{ activeSearch: null }" class="border border-gray-200 rounded-lg shadow-sm bg-white">
                    <div class="overflow-x-auto" style="overflow: visible;">
                        <table class="w-full text-sm border-collapse">
                            <thead class="bg-[#003D4A] text-white uppercase text-[11px] tracking-wider">
                                <tr>
                                    <th class="p-3 border-r border-white/10 relative" style="min-width: 130px;">
                                        <div class="flex items-center justify-center gap-2">
                                            <span>Id de Ficha</span>
                                            <button type="button" @click="activeSearch = (activeSearch === 'id' ? null : 'id')" class="focus:outline-none">
                                                <svg class="w-4 h-4 hover:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <div x-show="activeSearch === 'id'" x-transition @click.away="activeSearch = null" class="absolute z-50 top-full left-1/2 -translate-x-1/2 mt-2 p-3 bg-white border border-gray-200 rounded-lg shadow-2xl" style="min-width: 220px;">
                                            <div class="absolute -top-2 left-1/2 -translate-x-1/2 w-4 h-4 bg-white border-t border-l border-gray-200 rotate-45"></div>

                                            <div class="flex gap-2">
                                                <input type="text" name="f_id" value="{{ request('f_id') }}" placeholder="Ej: 12" @keydown.enter="$el.form.submit()" class="w-full px-2 py-1.5 text-gray-800 border rounded text-xs focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                                <button type="submit" class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round"stroke-linejoin="round" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </th>

                                    <th class="p-3 border-r border-white/10 relative" style="min-width: 150px;">
                                        <div class="flex items-center justify-center gap-2">
                                            <span>Tipo Ficha</span>
                                            <button type="button" @click="activeSearch = (activeSearch === 'tipo' ? null : 'tipo')" class="focus:outline-none">
                                                <svg class="w-4 h-4 hover:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div x-show="activeSearch === 'tipo'" x-transition @click.away="activeSearch = null" class="absolute z-50 top-full left-1/2 -translate-x-1/2 mt-2 p-3 bg-white border border-gray-200 rounded-lg shadow-2xl" style="min-width: 220px;">
                                            <div class="absolute -top-2 left-1/2 -translate-x-1/2 w-4 h-4 bg-white border-t border-l border-gray-200 rotate-45"></div>
                                            <div class="flex gap-2">
                                                <input type="text" name="f_tipo" value="{{ request('f_tipo') }}" placeholder="Filtro Tipo..." @keydown.enter="$el.form.submit()" class="w-full px-2 py-1.5 text-gray-800 border rounded text-xs focus:outline-none">
                                                <button type="submit" class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </th>

                                    <th class="p-3 border-r border-white/10 relative" style="min-width: 150px;">
                                        <div class="flex items-center justify-center gap-2">
                                            <span>Familia</span>
                                            <button type="button" @click="activeSearch = (activeSearch === 'familia' ? null : 'familia')" class="focus:outline-none">
                                                <svg class="w-4 h-4 hover:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div x-show="activeSearch === 'familia'" x-transition @click.away="activeSearch = null" class="absolute z-50 top-full left-1/2 -translate-x-1/2 mt-2 p-3 bg-white border border-gray-200 rounded-lg shadow-2xl" style="min-width: 220px;">
                                            <div class="absolute -top-2 left-1/2 -translate-x-1/2 w-4 h-4 bg-white border-t border-l border-gray-200 rotate-45"></div>
                                            <div class="flex gap-2">
                                                <input type="text" name="f_familia" value="{{ request('f_familia') }}" placeholder="Filtro Familia..." @keydown.enter="$el.form.submit()" class="w-full px-2 py-1.5 text-gray-800 border rounded text-xs focus:outline-none">
                                                <button type="submit" class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </th>

                                    <th class="p-3 border-r border-white/10 relative" style="min-width: 150px;">
                                        <div class="flex items-center justify-center gap-2">
                                            <span>Género</span>
                                            <button type="button" @click="activeSearch = (activeSearch === 'genero' ? null : 'genero')" class="focus:outline-none">
                                                <svg class="w-4 h-4 hover:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div x-show="activeSearch === 'genero'" x-transition @click.away="activeSearch = null" class="absolute z-50 top-full left-1/2 -translate-x-1/2 mt-2 p-3 bg-white border border-gray-200 rounded-lg shadow-2xl" style="min-width: 220px;">
                                            <div class="absolute -top-2 left-1/2 -translate-x-1/2 w-4 h-4 bg-white border-t border-l border-gray-200 rotate-45"></div>
                                            <div class="flex gap-2">
                                                <input type="text" name="f_genero" value="{{ request('f_genero') }}" placeholder="Filtro Género..." @keydown.enter="$el.form.submit()" class="w-full px-2 py-1.5 text-gray-800 border rounded text-xs focus:outline-none">
                                                <button type="submit" class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </th>

                                    <th class="p-3 border-r border-white/10 relative" style="min-width: 150px;">
                                        <div class="flex items-center justify-center gap-2">
                                            <span>Especie</span>
                                            <button type="button" @click="activeSearch = (activeSearch === 'especie' ? null : 'especie')" class="focus:outline-none">
                                                <svg class="w-4 h-4 hover:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div x-show="activeSearch === 'especie'" x-transition @click.away="activeSearch = null" class="absolute z-50 top-full left-1/2 -translate-x-1/2 mt-2 p-3 bg-white border border-gray-200 rounded-lg shadow-2xl" style="min-width: 220px;">
                                            <div class="absolute -top-2 left-1/2 -translate-x-1/2 w-4 h-4 bg-white border-t border-l border-gray-200 rotate-45"></div>
                                            <div class="flex gap-2">
                                                <input type="text" name="f_especie" value="{{ request('f_especie') }}" placeholder="Filtro Especie..." @keydown.enter="$el.form.submit()" class="w-full px-2 py-1.5 text-gray-800 border rounded text-xs focus:outline-none">
                                                <button type="submit" class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </th>

                                    <th class="p-3 border-r border-white/10 relative" style="min-width: 150px;">
                                        <div class="flex items-center justify-center gap-2">
                                            <span>Infraespecie</span>
                                            <button type="button" @click="activeSearch = (activeSearch === 'infraespecie' ? null : 'infraespecie')" class="focus:outline-none">
                                                <svg class="w-4 h-4 hover:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div x-show="activeSearch === 'infraespecie'" x-transition @click.away="activeSearch = null" class="absolute z-50 top-full left-1/2 -translate-x-1/2 mt-2 p-3 bg-white border border-gray-200 rounded-lg shadow-2xl" style="min-width: 220px;">
                                            <div class="absolute -top-2 left-1/2 -translate-x-1/2 w-4 h-4 bg-white border-t border-l border-gray-200 rotate-45"></div>
                                            <div class="flex gap-2">
                                                <input type="text" name="f_infraespecie" value="{{ request('f_infraespecie') }}" placeholder="Filtro Infraespecie..." @keydown.enter="$el.form.submit()" class="w-full px-2 py-1.5 text-gray-800 border rounded text-xs focus:outline-none">
                                                <button type="submit" class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white text-gray-700">
                                @forelse ($taxones as $taxon)
                                    <tr @click="selectedId = (selectedId === {{ $taxon->especieId }} ? null : {{ $taxon->especieId }})" :class="{ 'bg-green-200 hover:bg-green-200': selectedId === {{ $taxon->especieId }} }" class="border-b border-gray-200 hover:bg-gray-50 transition-colors cursor-pointer select-none" >
                                        <td class="p-3 border-r border-gray-200 text-blue-600 font-medium text-center">{{ $taxon->especieId }}</td>
                                        <td class="p-3 border-r border-gray-200 text-center">{{ $taxon->tipoficha }}</td>
                                        <td class="p-3 border-r border-gray-200 text-center">{{ $taxon->familia }}</td>
                                        <td class="p-3 border-r border-gray-200 italic text-center">{{ $taxon->genero }}</td>
                                        <td class="p-3 border-r border-gray-200 italic text-center">{{ $taxon->especie }}</td>
                                        <td class="p-3 text-center">{{ $taxon->infraespecie ?? '---' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-8 text-center text-gray-400 italic">No se encontraron registros...</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-8 flex justify-center [&_nav_p]:hidden [&_nav_div:nth-child(2)]:flex [&_nav_div:nth-child(2)]:justify-center [&_nav_div:nth-child(2)]:w-full"> {{ $taxones->links() }}</div>
            </form>
        </div>
    </main>

</body>

</html>
