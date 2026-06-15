    @php
        $defaultForm = [
            'id' => null,
            'especieId' => null,
            'taxon' => '',
            'Nom' => '',
            'Iucn' => '',
            'Cites' => '',
            'Reino' => '',
            'Divisionphylum' => '',
            'Clase' => '',
            'Orden' => '',
            'Familia' => '',
            'Genero' => '',
            'Especie_epiteto' => '',
            'Nombreinfra' => '',
            'Categinfra' => '',
            'EstatusTaxon' => '',
            'AutorTaxon' => '',
            'IdCAT' => '',
            'especiesSmilares' => '',
            'resumenEspecie' => '',
            'descEspecie' => '',
            'descripcionOrigen' => '',
            'origen' => [],
            'paises_seleccionados' => [],
            'estados_seleccionados' => [],
            'municipios_seleccionados' => [],
            'dist_mundial_info' => '',
            'dist_historica_estado' => '',
            'info_adicional_estado' => '',
            'dist_historica_municipio' => '',
            'info_adicional_municipio' => '',
            'siNoPotencial' => '0',
            'potencial_info' => '',
            'siNoEndemismo' => '0',
            'endemica_a' => '',
            'endemismo_info' => '',
            'nombres_comunes' => [],
            'sinonimos' => [],
            'largoinicialhembras' => '',
            'largofinalhembras' => '',
            'largoinicialmachos' => '',
            'largofinalmachos' => '',
            'pesoinicialhembras' => '',
            'pesofinalhembras' => '',
            'pesoinicialmachos' => '',
            'pesofinalmachos' => '',
            'siNoToxicidad' => '0',
            'toxicidad' => '',
            'riesgoUICN' => '',
            'infoUICN' => '',
            'cites' => '',
            'infoCITES' => '',
            'nom059' => [
                '2001' => ['categoria' => '', 'info' => ''],
                '2010' => ['categoria' => '', 'info' => ''],
                '2019' => ['categoria' => '', 'info' => ''],
            ],
            'promedioLargoHembras' => '',
            'unidadLargoHembras' => 'mm',
            'promedioLargoMachos' => '',
            'unidadLargoMachos' => 'mm',
            'promedioPesoHembras' => '',
            'unidadPesoHembras' => 'g',
            'promedioPesoMachos' => '',
            'unidadPesoMachos' => 'g',
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
        <script src="https://cdn.tiny.cloud/1/diri29rn4y1j7vuymg9c8aurb8vpljholqhf9e8ujqoghqm5/tinymce/8/tinymce.min.js"
            referrerpolicy="origin" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('formEspecies', () => ({
                    step: 1,
                    yaAvanzo: false,
                    openMenu: false,
                    search: @json($especie->taxon ?? ''),
                    showResults: false,
                    isEdit: {{ isset($especie->id) ? 'true' : 'false' }},
                    isItemSelected: {{ isset($especie) ? 'true' : 'false' }},
                    showModalNombre: false,
                    showModalSinonimo: false,
                    paisesOptions: @json($paises ?? []),
                    estadosOptions: @json($estados ?? []),
                    municipiosOptions: [],
                    especies: [],
                    form: @json($formData),
                    editandoIndice: -1,
                    tempNombre: {
                        nombre: '',
                        lengua: '',
                        bibliografia: '',
                        editable: true
                    },
                    editandoIndiceSinonimo: -1,
                    tempSinonimo: {
                        sinonimo: '',
                        autor: '',
                        anio: ''
                    },
                    selectedIndex: -1,

                    async init() {
                        const municipioGuardado = this.form.dist_historica_municipio

                        this.$nextTick(async () => {
                            this.initEditor('#resumenEspecie_editor', 'resumenEspecie');
                            this.initEditor('#descripcionEspecie_editor', 'descEspecie');
                            this.initEditor('#especiesSimilares_editor',
                                'especiesSmilares');
                            this.initEditor('#descripcionOrigen_editor',
                                'descripcionOrigen');
                            this.initEditor('#infoUICN_editor', 'infoUICN');
                            this.initEditor('#infoCITES_editor', 'infoCITES');
                            if (this.form.siNoToxicidad === '1') {
                                this.initEditor('#toxicidad_editor', 'toxicidad');
                            }
                            if (this.form.dist_historica_estado) {
                                await this.cargarMunicipios(this.form
                                    .dist_historica_estado);
                                this.form.dist_historica_municipio = municipioGuardado;
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
                                    const activeItem = document.getElementById(
                                        `res-item-${index}`);
                                    if (activeItem) {
                                        activeItem.scrollIntoView({
                                            block: 'nearest',
                                            behavior: 'smooth'
                                        });
                                    }
                                });
                            }
                        });

                        this.$watch('step', value => {
                            if (value >= 2) {
                                this.yaAvanzo = true;
                            }
                        });

                        if (this.form.estados_seleccionados && this.form.estados_seleccionados.length >
                            0) {
                            this.municipiosOptions = [];
                            for (const estado of this.form.estados_seleccionados) {
                                await this.cargarMunicipios(estado,
                                    true);
                            }
                        }
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
                        } else {
                            this.editandoIndice = -1;
                            this.tempNombre = {
                                nombre: '',
                                lengua: '',
                                bibliografia: '',
                                editable: true
                            };
                            if (tinymce.get('bibliografia_editor')) {
                                tinymce.get('bibliografia_editor').setContent('');
                            }
                            this.showModalNombre = true;
                        }
                    },


                    async cargarMunicipios(nombreEdo, acumular = false) {
                        if (!nombreEdo) return;
                        try {
                            const response = await fetch(
                                `/obtener-municipios/${encodeURIComponent(nombreEdo)}`);
                            const data = await response.json();
                            if (acumular) {
                                this.municipiosOptions = [...this.municipiosOptions, ...data];
                            } else {
                                this.municipiosOptions = [...this.municipiosOptions, ...data];
                            }
                            this.municipiosOptions = Array.from(new Map(this.municipiosOptions.map(
                                m => [m.municipioId, m])).values());

                        } catch (error) {
                            console.error("Error:", error);
                        }
                    },

                    editarNombre(index) {
                        const nombreRef = this.form.nombres_comunes[index];
                        if (!nombreRef.editable) return;
                        this.editandoIndice = index;
                        this.tempNombre = {
                            ...nombreRef
                        };
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

                        if (this.tempNombre.lengua === 'Otro') {
                            if (!this.tempNombre.lengua_otra || this.tempNombre.lengua_otra.trim() === '') {
                                return alert('Por favor, especifique la lengua.');
                            }
                            this.tempNombre.lengua = this.tempNombre.lengua_otra;
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

                        this.tempNombre = {
                            nombre: '',
                            lengua: '',
                            lengua_otra: '',
                            bibliografia: '',
                            editable: true
                        };

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
                        } else {
                            this.editandoIndiceSinonimo = -1;
                            this.tempSinonimo = {
                                sinonimo: '',
                                autor: '',
                                anio: '',
                                editable: true
                            };
                            this.showModalSinonimo = true;
                        }
                    },

                    editarSinonimo(index) {
                        const sinonimoRef = this.form.sinonimos[index];
                        if (!sinonimoRef.editable) return;
                        this.editandoIndiceSinonimo = index;
                        this.tempSinonimo = {
                            ...sinonimoRef
                        };
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
                        this.tempSinonimo = {
                            nombre: '',
                            autor: '',
                            anio: '',
                            editable: true
                        };
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
                            this.especies = [];
                            this.showResults = false;
                            this.selectedIndex = -1;
                            return;
                        }
                        fetch(`/buscar-especie?q=${encodeURIComponent(this.search)}`)
                            .then(res => res.json()).then(data => {
                                this.especies = data;
                                this.showResults = true;
                                this.selectedIndex = -1;
                            });
                    },

                    async seleccionar(item) {
                        Object.assign(this.form, {
                            Nom: item.Nom || '',
                            riesgoUICN: item.Iucn || '',
                            cites: item.Cites || '',
                            especieId: item.IdNombre,
                            taxon: item.taxon || '',
                            Reino: item.Reino || '',
                            Divisionphylum: item.Divisionphylum || '',
                            Clase: item.Clase || '',
                            Orden: item.Orden || '',
                            Familia: item.Familia || '',
                            Genero: item.Genero || '',
                            Categinfra: item.Categinfra || '',
                            AutorTaxon: item.AutorTaxon || '',
                            EstatusTaxon: item.EstatusTaxon || '',
                            Especie_epiteto: item.Especie_epiteto || '',
                            Nombreinfra: item.Nombreinfra || '',
                            IdCAT: item.IdCAT || '',
                            origen: item.origen ? (typeof item.origen === 'string' ? item.origen
                                .split(', ') : item.origen) : [],
                            nombres_comunes: item.nombres_comunes_array || []
                        });
                        await this.cargarSinonimos(item.IdNombre);
                        this.search = item.taxon;
                        this.isItemSelected = true;
                        this.showResults = false;
                        this.selectedIndex = -1;
                    },


                    async avanzarSeccion() {
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

                        if (window.tinymce) {
                            const editors = [
                                'resumenEspecie_editor', 'descripcionEspecie_editor',
                                'especiesSimilares_editor', 'descripcionOrigen_editor',
                                'infoUICN_editor', 'infoCITES_editor', 'toxicidad_editor'
                            ];
                            editors.forEach(id => {
                                const ed = tinymce.get(id);
                                if (ed) {
                                    const field = id.replace('_editor', '')
                                        .replace('descripcionEspecie', 'descEspecie')
                                        .replace('especiesSimilares', 'especiesSmilares');
                                    this.form[field] = ed.getContent();
                                }
                            });
                        }

                        Swal.fire({
                            title: 'Guardando...',
                            didOpen: () => Swal.showLoading(),
                            allowOutsideClick: false
                        });

                        try {
                            const token = document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content');
                            const url = this.form.id ? `/actualizar_seccion/${this.form.id}` :
                                '/guardar_seccion';

                            const res = await fetch(url, {
                                method: this.form.id ? 'PUT' : 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    form: {
                                        ...this.form,
                                        origen: Array.isArray(this.form.origen) ? this
                                            .form.origen.join(', ') : this.form.origen
                                    }
                                })
                            });

                            const result = await res.json();

                            if (result.success) {
                                if (result.id) this.form.id = result.id;
                                Swal.fire({
                                    title: '¡Guardado!',
                                    text: 'Progreso guardado correctamente.',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                this.yaAvanzo = true;
                                this.step++;
                                window.scrollTo({
                                    top: 0,
                                    behavior: 'smooth'
                                });

                            } else {
                                throw new Error(result.error);
                            }
                        } catch (error) {
                            console.error(error);
                            Swal.fire('Error', 'No se pudo guardar la información de esta sección.',
                                'error');
                        }
                    },

                    async guardarAvance() {
                        if (!this.form.especieId) {
                            Swal.fire({
                                title: 'Atención',
                                text: 'Seleccione una especie antes de guardar.',
                                icon: 'warning'
                            });
                            return;
                        }

                        if (window.tinymce) {
                            const editors = [
                                'resumenEspecie_editor', 'descripcionEspecie_editor',
                                'especiesSimilares_editor', 'descripcionOrigen_editor',
                                'infoUICN_editor', 'infoCITES_editor', 'toxicidad_editor'
                            ];
                            editors.forEach(id => {
                                const ed = tinymce.get(id);
                                if (ed) {
                                    const field = id.replace('_editor', '').replace(
                                        'descripcionEspecie', 'descEspecie').replace(
                                        'especiesSimilares', 'especiesSmilares');
                                    this.form[field] = ed.getContent();
                                }
                            });
                        }

                        Swal.fire({
                            title: 'Guardando...',
                            didOpen: () => Swal.showLoading(),
                            allowOutsideClick: false
                        });

                        try {
                            const token = document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content');
                            const url = this.form.id ? `/actualizar_seccion/${this.form.id}` :
                                '/guardar_seccion';

                            const res = await fetch(url, {
                                method: this.form.id ? 'PUT' : 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    form: {
                                        ...this.form,
                                        origen: Array.isArray(this.form.origen) ? this
                                            .form.origen.join(', ') : this.form.origen
                                    }
                                })
                            });

                            const result = await res.json();
                            if (result.success) {
                                if (result.id) this.form.id = result.id;
                                Swal.fire({
                                    title: '¡Guardado!',
                                    text: 'Progreso guardado correctamente.',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            } else {
                                throw new Error();
                            }
                        } catch (error) {
                            Swal.fire('Error', 'No se pudo guardar la información', 'error');
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

                    async navegarSeccion(proximoPaso) {
                        if (this.step === proximoPaso) return;
                        if (this.step === 1 && !this.form.especieId && proximoPaso > 1) {
                            Swal.fire({
                                title: 'Atención',
                                text: 'Por favor, seleccione una especie antes de continuar.',
                                icon: 'warning',
                                confirmButtonColor: '#4f46e5'
                            });
                            return;
                        }
                        if (window.tinymce) {
                            const editors = [{
                                    id: 'resumenEspecie_editor',
                                    field: 'resumenEspecie'
                                },
                                {
                                    id: 'descripcionEspecie_editor',
                                    field: 'descEspecie'
                                },
                                {
                                    id: 'especiesSimilares_editor',
                                    field: 'especiesSmilares'
                                },
                                {
                                    id: 'descripcionOrigen_editor',
                                    field: 'descripcionOrigen'
                                },
                                {
                                    id: 'infoUICN_editor',
                                    field: 'infoUICN'
                                },
                                {
                                    id: 'infoCITES_editor',
                                    field: 'infoCITES'
                                },
                                {
                                    id: 'toxicidad_editor',
                                    field: 'toxicidad'
                                }
                            ];
                            editors.forEach(item => {
                                const ed = tinymce.get(item.id);
                                if (ed) {
                                    this.form[item.field] = ed.getContent();
                                }
                            });
                        }

                        Swal.fire({
                            title: 'Guardando...',
                            didOpen: () => Swal.showLoading(),
                            allowOutsideClick: false
                        });

                        try {
                            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            const url = this.form.id ? `/actualizar_seccion/${this.form.id}` : '/guardar_seccion';
                            const res = await fetch(url, {
                                method: this.form.id ? 'PUT' : 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    form: {
                                        ...this.form,
                                        origen: Array.isArray(this.form.origen) ? this
                                            .form.origen.join(', ') : this.form.origen
                                    }
                                })
                            });

                            const result = await res.json();

                            if (result.success) {
                                if (result.id) this.form.id = result.id;
                                Swal.fire({
                                    title: '¡Guardado!',
                                    text: 'Progreso guardado correctamente.',
                                    icon: 'success',
                                    timer: 1000,
                                    showConfirmButton: false
                                });
                                this.yaAvanzo = true;
                                this.step = proximoPaso;
                                window.scrollTo({
                                    top: 0,
                                    behavior: 'smooth'
                                });
                            } else {
                                throw new Error(result.error);
                            }
                        } catch (error) {
                            console.error(error);
                            Swal.fire('Error',
                                'No se pudo guardar la información al cambiar de sección.', 'error');
                        }
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

            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .no-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            .sticky-header-glass {
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
            }

            @keyframes subtle-bounce {

                0%,
                100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-3px);
                }
            }

            .step-active {
                animation: subtle-bounce 2s infinite ease-in-out;
            }
        </style>
    </head>


    <body class="bg-gray-100 min-h-screen" x-data="formEspecies">
        <x-header />
        <main class="max-w-7xl mx-auto p-6">
            <div>
                <div class="sticky top-0 z-50 bg-white border-b border-gray-200 rounded-t-2xl shadow-md">
                    <div class="pt-3 pb-1 max-w-5xl mx-auto">
                        <div class="p-3 relative" x-show="!isItemSelected">
                            <div class="max-w-xl mx-auto">
                                <div class="relative">
                                    <div
                                        class="flex items-center bg-gray-50 border-2 border-indigo-100 focus-within:border-indigo-500 rounded-xl px-4 py-2">
                                        <input type="text" x-model="search"
                                            @input.debounce.300ms="showResults = true; buscarEspecie()"
                                            placeholder="Escribe el nombre..."
                                            class="w-full focus:outline-none bg-transparent">
                                    </div>
                                    <div x-show="showResults && especies.length > 0" x-cloak
                                        class="absolute z-[100] w-full mt-2 bg-white shadow-2xl rounded-xl">
                                        <template x-for="item in especies">
                                            <div @click="seleccionar(item)"
                                                class="px-5 py-3 cursor-pointer hover:bg-indigo-600 hover:text-white"
                                                x-text="item.taxon"></div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div x-show="isItemSelected" x-cloak class="flex items-center justify-between w-full px-6 py-2">
                            <div class="flex items-baseline gap-2 overflow-hidden">
                                <h1 class="text-xl md:text-2xl font-black text-indigo-900 italic tracking-tight whitespace-nowrap"
                                    x-text="form.taxon"></h1>
                                <h1 class="text-xl md:text-2xl font-black text-indigo-900 tracking-tight whitespace-nowrap"
                                    x-text="form.AutorTaxon"></h1>
                            </div>
                            <button x-show="!isEdit && step === 1 && !yaAvanzo" @click="limpiarSeleccion()"
                                class="text-[10px] font-bold text-red-500 hover:text-red-700 uppercase transition-colors ml-4">✕
                                Cambiar especie</button>
                        </div>


                        <div class="max-w-6xl mx-auto px-4 mt-3 mb-1 overflow-x-auto no-scrollbar">
                            <div class="min-w-[1000px] lg:min-w-full relative p-4">
                                <div class="absolute top-[36px] left-[50px] right-[50px] h-0.5 bg-gray-200 z-0"></div>
                                <div class="relative flex justify-between z-10">
                                    @php $secciones = ['Clasificación', 'Distribución', 'Ambiente', 'Biología', 'Ecología', 'Genética', 'Importancia', 'Conservación', 'Prioritarias', 'Necesidades', 'Metadatos']; @endphp
                                    @foreach ($secciones as $index => $titulo)
                                        @php $n = $index + 1; @endphp
                                        <div class="flex flex-col items-center cursor-pointer" @click="if(isItemSelected || {{ $n }} == 1) navegarSeccion({{ $n }})">
                                            <div class="w-10 h-10 rounded-full border-2 flex items-center justify-center transition-all"
                                                :class="step == {{ $n }} ?
                                                    'bg-indigo-600 border-indigo-600 text-white' : (step >
                                                        {{ $n }} ?
                                                        'bg-green-500 border-green-500 text-white' :
                                                        'bg-white border-gray-300 text-gray-400')">
                                                <template x-if="step > {{ $n }}"><svg class="w-6 h-6"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                    </svg></template>
                                                <template x-if="step <= {{ $n }}"><span
                                                        class="text-xs font-bold">{{ $n }}</span></template>
                                            </div>
                                            <span class="mt-2 text-[10px] font-bold uppercase text-center w-20"
                                                :class="step == {{ $n }} ? 'text-indigo-900' : (step >
                                                    {{ $n }} ? 'text-green-600' : 'text-gray-400')">{{ $titulo }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div x-show="step === 1"><x-seccion1 /></div>
                <div x-show="step === 2"><x-seccion2 /></div>
                <div x-show="step === 3"><x-seccion3 /></div>
            </div>
        </main>

        <div class="fixed bottom-10 right-10 z-[9999]">
            <div class="flex flex-col-reverse items-center gap-4">
                <button @click="openMenu = !openMenu" type="button"
                    class="w-16 h-16 rounded-full text-white shadow-2xl flex items-center justify-center transition-all duration-300 transform active:scale-95 border-4 border-white z-[10000]"
                    :class="openMenu ? 'bg-red-500 rotate-45' : 'bg-indigo-600'">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                    </svg>
                </button>

                <div x-show="openMenu" x-cloak x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-10"
                    x-transition:enter-end="opacity-100 translate-y-0" class="flex flex-col gap-4">
                    <div class="flex items-center gap-3 group">
                        <span
                            class="bg-gray-800 text-white text-[10px] font-black px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity tracking-widest">Guardar
                            avance</span>
                        <button @click="guardarAvance(); openMenu = false" title="Guardar Avance"
                            class="w-12 h-12 bg-yellow-500 hover:bg-yellow-600 text-white rounded-full shadow-lg flex items-center justify-center transition-transform hover:scale-110 active:scale-95">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                                <polyline points="17 21 17 13 7 13 7 21" />
                                <polyline points="7 3 7 8 15 8" />
                                <line x1="10" y1="16" x2="14" y2="16" />
                                <line x1="10" y1="18" x2="14" y2="18" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center gap-3 group">
                        <span
                            class="bg-gray-900 text-white text-xs font-bold px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">Ir
                            a inicio</span>
                        <a href="/dashboard"
                            class="w-14 h-14 bg-slate-800 hover:bg-slate-900 text-white rounded-full shadow-xl flex items-center justify-center border-2 border-white transition-transform hover:scale-110">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </body>

    </html>
