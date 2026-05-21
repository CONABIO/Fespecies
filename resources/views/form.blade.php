    @php
        $defaultForm = [
            'id' => null,'especieId' => null,'taxon' => '','Nom' => '','Iucn' => '','Cites' => '','Reino' => '','Divisionphylum' => '','Clase' => '','Orden' => '','Familia' => '',
            'Genero' => '','Especie_epiteto' => '','Nombreinfra' => '','Categinfra' => '','EstatusTaxon' => '','AutorTaxon' => '','IdCAT' => '','especiesSmilares' => '',
            'resumenEspecie' => '','descEspecie' => '', 'descripcionOrigen' => '','origen' => [],'nombres_comunes' => [],'sinonimos' => [],'largoinicialhembras' => '','largofinalhembras' => '',
            'largoinicialmachos' => '','largofinalmachos' => '','pesoinicialhembras' => '','pesofinalhembras' => '','pesoinicialmachos' => '','pesofinalmachos' => '','siNoToxicidad' => '0',
            'toxicidad' => '','riesgoUICN' => '','infoUICN' => '','cites' => '','infoCITES' => '','nom059' => ['2001' => ['categoria' => '', 'info' => ''],'2010' => ['categoria' => '', 'info' => ''],'2019' => ['categoria' => '', 'info' => ''],],
            'promedioLargoHembras' => '', 'unidadLargoHembras' => 'mm', 'promedioLargoMachos' => '', 'unidadLargoMachos' => 'mm', 'promedioPesoHembras' => '', 'unidadPesoHembras' => 'g', 'promedioPesoMachos' => '', 'unidadPesoMachos' => 'g',
        ];
        $formData = $especie ?? $defaultForm;
    @endphp
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Fespecies</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://cdn.tiny.cloud/1/l23138ijuswnhf39d1698oh19vx6b1fc8z6uutbyi2ecynz4/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('formEspecies', () => ({
                    step: 1,
                    search: @json($especie->taxon ?? ''),
                    showResults: false,
                    isEdit: {{ isset($especie->id) ? 'true' : 'false' }},
                    isItemSelected: {{ isset($especie) ? 'true' : 'false' }},
                    showModalNombre: false,
                    showModalSinonimo: false,
                    especies: [],
                    form: @json($formData),
                    editandoIndice: -1,
                    tempNombre: {nombre: '',lengua: '',bibliografia: '',editable: true},
                    editandoIndiceSinonimo: -1,
                    tempSinonimo: {sinonimo: '',autor: '',anio: ''},
                    selectedIndex: -1,

                    init() {
                        this.$nextTick(() => {
                            this.initEditor('#resumenEspecie_editor', 'resumenEspecie');
                            this.initEditor('#descripcionEspecie_editor', 'descEspecie');
                            this.initEditor('#especiesSimilares_editor', 'especiesSmilares');
                            this.initEditor('#descripcionOrigen_editor', 'descripcionOrigen');
                            this.initEditor('#infoUICN_editor', 'infoUICN');
                            this.initEditor('#infoCITES_editor', 'infoCITES');
                            if (this.form.siNoToxicidad === '1') {
                                this.initEditor('#toxicidad_editor', 'toxicidad');
                            }
                        });
                        this.$watch('form.siNoToxicidad', v => {
                            if (v === '1') {
                                this.$nextTick(() => this.initEditor('#toxicidad_editor',
                                    'toxicidad'));
                            } else {
                                tinymce.remove('#toxicidad_editor');
                                this.form.toxicidad = '';
                            }
                        });
                        this.$watch('showModalNombre', v => {
                            if (v) {
                                this.$nextTick(() => this.initEditor('#bibliografia_editor',
                                    'bibliografia', 'tempNombre'));
                            } else {
                                tinymce.remove('#bibliografia_editor');
                            }
                        });

                        this.$watch('selectedIndex', index => {
                            if (index >= 0) {
                                this.$nextTick(() => {
                                    const activeItem = document.getElementById(`res-item-${index}`);
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

                    initEditor(selector, field, parent = 'form') {
                        tinymce.remove(selector);
                        tinymce.init({
                            selector: selector,
                            plugins: 'lists link',
                            toolbar: 'bold italic | link',
                            height: 130,
                            menubar: false,
                            branding: false,
                            language: 'es',
                            setup: (editor) => {
                                editor.on('init', () => editor.setContent(this[parent][field] ||
                                    ''));
                                editor.on('change keyup', () => {
                                    this[parent][field] = editor.getContent();
                                });
                            }
                        });
                    },

                   abrirModalNombre() {
                    if (!this.form.especieId) {
                            Swal.fire({
                            title: 'Aviso',
                            text: `Debe seleccionar una especie antes de agregar nombres comunes`,
                            icon: 'warning',
                            confirmButtonColor: '#4f46e5',
                            confirmButtonText: 'Ok'
                        });
                        }else{
                            this.editandoIndice = -1;
                            this.tempNombre = { nombre: '', lengua: '', bibliografia: '', editable: true };
                            if (tinymce.get('bibliografia_editor')) {
                                tinymce.get('bibliografia_editor').setContent('');
                            }
                            this.showModalNombre = true;
                        }
                    },

                    editarNombre(index) {
                        const nombreRef = this.form.nombres_comunes[index];
                        if (!nombreRef.editable) return;
                        this.editandoIndice = index;
                        this.tempNombre = { ...nombreRef };
                        if (tinymce.get('bibliografia_editor')) {
                            tinymce.get('bibliografia_editor').setContent(nombreRef.bibliografia || '');
                        }
                        this.showModalNombre = true;
                    },

                    guardarNombreComun() {
                        if (!this.tempNombre.nombre || this.tempNombre.nombre.trim() === '') {
                            return alert('El nombre común es obligatorio.');
                        }
                        if (tinymce.get('bibliografia_editor')) {
                            this.tempNombre.bibliografia = tinymce.get('bibliografia_editor').getContent();
                        }
                        if (this.editandoIndice === -1) {
                            this.form.nombres_comunes.push({
                                ...this.tempNombre,
                                editable: true
                            });
                        } else {
                            this.form.nombres_comunes[this.editandoIndice] = {
                                ...this.tempNombre,
                                editable: true
                            };
                        }
                        this.tempNombre = { nombre: '', lengua: '', bibliografia: '', editable: true };
                        this.showModalNombre = false;
                        this.editandoIndice = -1;
                    },

                    abrirModalSinonimo() {
                        if (!this.form.especieId) {
                            Swal.fire({
                            title: 'Aviso',
                            text: `Debe seleccionar una especie antes de agregar sinonimos`,
                            icon: 'warning',
                            confirmButtonColor: '#4f46e5',
                            confirmButtonText: 'Ok'
                        });
                        }else{
                            this.editandoIndiceSinonimo = -1;
                            this.tempSinonimo = { sinonimo: '', autor: '', anio: '', editable: true };
                            this.showModalSinonimo = true;
                        }
                    },

                    editarSinonimo(index) {
                        const sinonimoRef = this.form.sinonimos[index];
                        if (!sinonimoRef.editable) return;
                        this.editandoIndiceSinonimo = index;
                        this.tempSinonimo = { ...sinonimoRef };
                        this.showModalSinonimo = true;
                    },

                    guardarSinonimoManual() {
                        if (!this.tempSinonimo.sinonimo || this.tempSinonimo.sinonimo.trim() === '') {
                            return alert('El nombre del sinónimo es obligatorio.');
                        }

                        if (this.editandoIndiceSinonimo === -1) {
                            this.form.sinonimos.push({
                                ...this.tempSinonimo,
                                editable: true
                            });
                        } else {
                            this.form.sinonimos[this.editandoIndiceSinonimo] = {
                                ...this.tempSinonimo,
                                editable: true
                            };
                        }
                        this.tempSinonimo = { nombre: '', autor: '', anio: '', editable: true };
                        this.showModalSinonimo = false;
                        this.editandoIndiceSinonimo = -1;
                    },

                    async cargarSinonimos(id) {
                        const res = await fetch(`/obtener-sinonimos?id=${id}`);
                        const data = await res.json();
                        this.form.sinonimos = data.map(s => ({
                            sinonimo: s.sinonimo,
                            autor: s.autor || 'EMPTY',
                            anio: null,
                            editable: false
                        }));
                    },
                    buscarEspecie() {
                        if (this.search.length < 1) {
                            this.especies = []; this.showResults = false; this.selectedIndex = -1; return;
                        }
                        fetch(`/buscar-especie?q=${encodeURIComponent(this.search)}`)
                            .then(res => res.json()).then(data => {
                                this.especies = data; this.showResults = true; this.selectedIndex = -1;
                            });
                    },

                    async seleccionar(item) {
                        Object.assign(this.form, {
                            Nom: item.Nom || '',  riesgoUICN: item.Iucn || '', cites: item.Cites || '', especieId: item.IdNombre, taxon: item.taxon || '', Reino: item.Reino || '', Divisionphylum: item.Divisionphylum || '', Clase: item.Clase || '',Orden: item.Orden || '',Familia: item.Familia || '', Genero: item.Genero || '', Categinfra: item.Categinfra || '', AutorTaxon: item.AutorTaxon || '', EstatusTaxon: item.EstatusTaxon || '',Especie_epiteto: item.Especie_epiteto || '', Nombreinfra: item.Nombreinfra || '', IdCAT: item.IdCAT || '', origen: item.origen ? (typeof item.origen === 'string' ? item.origen.split(', ') : item.origen) : [], nombres_comunes: item.nombres_comunes_array || []});
                        await this.cargarSinonimos(item.IdNombre);
                        this.search = item.taxon; this.isItemSelected = true; this.showResults = false; this.selectedIndex = -1;
                    },


                    async avanzarSeccion() {
                        console.log("Datos que se enviarán:", JSON.parse(JSON.stringify(this.form)));

                         if (this.step === 1 && !this.form.especieId) {
                            Swal.fire({
                                title: 'Atención',
                                text: 'Por favor, seleccione una especie antes de continuar.',
                                icon: 'warning',
                                confirmButtonColor: '#4f46e5',
                                confirmButtonText: 'Aceptar'
                            });
                            return;
                        }
                        if (this.step === 1 && !this.form.especieId) return alert(
                            'Seleccione una especie.');
                        const url = this.form.id ? `/actualizar_seccion/${this.form.id}` :
                            '/guardar_seccion';
                        const res = await fetch(url, {
                            method: this.form.id ? 'PUT' : 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                form: {
                                    ...this.form,
                                    origen: this.form.origen.join(', ')
                                }
                            })
                        });
                        const result = await res.json();
                        if (result.success) {
                            this.step = 2;
                            window.scrollTo(0, 0);
                        }
                    },

                    limpiarSeleccion() {
                        this.isItemSelected = false;
                        this.search = '';
                        this.form = @json($defaultForm);
                        tinymce.editors.forEach(ed => ed.setContent(''));
                    },

                    calcularPromedio(min, max) {
                        const n1 = parseFloat(min),
                            n2 = parseFloat(max);
                        return (isNaN(n1) || isNaN(n2)) ? '' : ((n1 + n2) / 2).toFixed(2);
                    },

                    validarRango(keyInicio, keyFin, etiqueta) {
                    let inicio = parseFloat(this.form[keyInicio]);
                    let fin = parseFloat(this.form[keyFin]);
                    if (!isNaN(inicio) && !isNaN(fin) && fin < inicio) {
                        Swal.fire({
                            title: 'Valor inválido',
                            text: `En "${etiqueta}", el valor final no puede ser menor al inicial.`,
                            icon: 'error',
                            confirmButtonColor: '#4f46e5',
                            confirmButtonText: 'Corregir'
                        });
                        this.form[keyFin] = '';
                    }
                },

                eliminarNombre(index) {
                    Swal.fire({
                        title: 'Eliminación',
                        text: `¿Estás seguro de quitar este nombre común?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#4f46e5',
                        cancelButtonColor: '#ef4444',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.form.nombres_comunes.splice(index, 1);
                        }
                    });
                },

                eliminarSinonimo(index) {
                    Swal.fire({
                        title: 'Eliminación',
                        text: "¿Estás seguro de quitar este sinónimo?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#4f46e5',
                        confirmButtonText: 'Sí, eliminar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.form.sinonimos.splice(index, 1);
                        }
                    });
                },
                }));
            });
        </script>
        <style>
            [x-cloak] {
                display: none !important;
            }
            .tox-tinymce-aux {
                z-index: 9999 !important;
            }
        </style>
    </head>

    <body class="bg-gray-100 min-h-screen">
        <x-header />
        <main class="max-w-7xl mx-auto p-6" x-data="formEspecies">
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 p-8">
                    <div class="max-w-5xl mx-auto">
                        <div x-show="!isItemSelected" class="max-w-xl mx-auto">
                            <div class="relative">
                                <div class="flex items-center bg-white border-2 border-indigo-100 focus-within:border-indigo-500 rounded-xl shadow-sm px-4 py-3 transition-all">
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <input type="text" x-model="search" @input.debounce.300ms="showResults = true; buscarEspecie()" @click.away="showResults = false"  @keydown.arrow-down.prevent="if (selectedIndex < especies.length - 1) selectedIndex++" @keydown.arrow-up.prevent="if (selectedIndex > 0) selectedIndex--" @keydown.enter.prevent="if (selectedIndex !== -1) seleccionar(especies[selectedIndex])"  @keydown.escape="showResults = false"  placeholder="Escribe el nombre de la especie..." class="w-full ml-3 focus:outline-none text-base text-gray-700 bg-transparent">
                                </div>

                                <div x-show="showResults && especies.length > 0" x-cloak class="absolute z-[100] w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-2xl overflow-hidden">
                                    <div class="max-h-60 overflow-y-auto custom-scroll">
                                        <template x-for="(item, index) in especies" :key="index">
                                            <div :id="'res-item-' + index" @click="seleccionar(item)"  @mouseenter="selectedIndex = index" :class="{ 'bg-indigo-600 text-white': selectedIndex === index, 'text-gray-800': selectedIndex !== index }" class="px-5 py-3 cursor-pointer transition-colors group">
                                                <div class="text-sm font-bold" :class="selectedIndex === index ? 'text-white' : 'text-gray-800'" x-text="item.taxon"></div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div x-show="isItemSelected" x-cloak class="flex items-center justify-between w-full">
                            <div class="flex items-baseline gap-2 overflow-hidden">
                                <h1 class="text-3xl font-black text-indigo-900 italic tracking-tight whitespace-nowrap" x-text="form.taxon"></h1>
                                <span class="text-3xl font-black text-indigo-900 tracking-tight">,</span>
                                <h1 class="text-3xl font-black text-indigo-900 tracking-tight whitespace-nowrap" x-text="form.AutorTaxon"></h1>
                            </div>
                            <div x-show="isItemSelected" x-cloak class="flex items-center justify-between w-full">
                                <div class="flex items-baseline gap-2 overflow-hidden">
                                    <h1 class="text-3xl font-black text-indigo-900 italic tracking-tight whitespace-nowrap" x-text="form.taxon"></h1>
                                    <span class="text-3xl font-black text-indigo-900 tracking-tight">,</span>
                                    <h1 class="text-3xl font-black text-indigo-900 tracking-tight whitespace-nowrap" x-text="form.AutorTaxon"></h1>
                                </div>
                                <button x-show="!isEdit" @click="limpiarSeleccion()" class="text-[10px] font-bold text-red-400 hover:text-red-600 uppercase transition-colors whitespace-nowrap ml-6">
                                    ✕ Cambiar especie
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <x-seccion1 />
                <x-seccion2 />
            </div>

            {{-- <div class="fixed top-48 z-[100] hidden xl:block" style="margin-left: 130px">
                <a href="{{ route('dashboard') }}" class="group flex items-center justify-center w-14 h-14 bg-white border-2 border-indigo-100 text-indigo-600 rounded-full shadow-xl hover:bg-indigo-600 hover:text-white hover:border-indigo-600 hover:-translate-y-1 transition-all duration-300">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="absolute left-20 bg-slate-900 text-white text-[10px] font-black uppercase tracking-[0.2em] px-4 py-2 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none shadow-2xl whitespace-nowrap">
                        Ir al inicio
                        <div class="absolute top-1/2 -left-1 -translate-y-1/2 w-2 h-2 bg-slate-900 rotate-45"></div>
                    </span>
                </a>
            </div> --}}
        </main>
    </body>

    </html>
