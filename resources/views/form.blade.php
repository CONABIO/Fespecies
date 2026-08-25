    @php
        $defaultForm = [
            'id' => null,
            'especieId' => null,
            'taxon' => '',
            'Nom' => '',
            'Iucn' => '',
            'Cites' => '',
            'tipoAmbiente' => '',
            'habitatAgropecuario' => '',
            'zonaUrbana' => '',
            'VegetacionSecundaria' => '',
            'intervaloaltitudinalinicial' => '',
            'intervaloaltitudinalfinal' => '',
            'infoAddintervaloaltitudinal' => '',
            'temperaturainicial' => '',
            'temperaturafinal' => '',
            'infoaddtemperatura' => '',
            'precipitacioninicial' => '',
            'precipitacionfinal' => '',
            'infoaddprecipitacion' => '',
            'humedadinicial' => '',
            'humedadfinal' => '',
            'infoaddhumedad' => '',
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
            'suelo_tipo' => [],
            'ecorregiones_terrestres' => [],
            'ecosistemas' => [],
            'ecorregiones_marinas_ids' => [],
            'ecorregiones_info_adicional' => '',

            'tipo_vegetacion_a' => [],
            'habitats_antropicos' => [],
            'vegetacion_secundaria' => [],
            'clima_tipo' => [],
            'geoforma_tipo' => [],
            'vegetacion_info_adicional_a' => '',
            'especies_asociadas_info' => '',
            'suelo_info' => '',
            'clima_info' => '',
            'geoforma_info' => '',

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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
                    suelosOptions: @json($tiposSuelo ?? []),
                    habitatsAntropicosOptions: @json($habitatsAntropicos ?? []),
                    vegSecundariaOptions: @json($vegSecundaria ?? []),
                    openSuelo: false,
                    filterSuelo: '',
                    climaOptions: @json($clima ?? []),
                    openClima: false,
                    filterClima: '',
                    geoformaOptions: @json($geoforma ?? []),
                    openGeo: false,
                    filterGeo: '',
                    vegetacionOptions: @json($vegetacionCat ?? []),
                    openVeg: false,
                    filterVeg: '',
                    ecorregionOptions: @json($ecorregiones ?? []),
                    openEcorregion: false,
                    filterEcorregion: '',
                    ecosistemaOptions: @json($ecosistemasCat ?? ($ecosistemas ?? [])),
                    marinasOptions: @json($ecorregionesMarinasCat ?? []),
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
                            this.initEditor('#vegetacion_info_adicional_a_editor',
                                'vegetacion_info_adicional_a');
                            this.initEditor('#especies_asociadas_info_editor',
                                'especies_asociadas_info');
                            this.initEditor('#ecorregiones_info_adicional_editor',
                                'ecorregiones_info_adicional');
                            this.initEditor('#resumenEspecie_editor', 'resumenEspecie');
                            this.initEditor('#infoAddNombreCientifico',
                                'infoAddNombreCientifico');
                            this.initEditor('#infoAddDistribucionMundialPais',
                                'dist_mundial_info');
                            this.initEditor('#infoAddDistribucionMundialEstado',
                                'info_adicional_estado');
                            this.initEditor('#infoAddDistribucionMundialMunicipio',
                                'info_adicional_municipio');
                            this.initEditor('#infoAddDistPotMex', 'potencial_info');
                            this.initEditor('#infoAddEndemismo', 'endemismo_info');
                            this.initEditor('#descripcionEspecie_editor', 'descEspecie');
                            this.initEditor('#toxicidad_editor', 'toxicidad');
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
                            if (index >= 0 && this.$refs.especiesContainer) {
                                this.$nextTick(() => {
                                    const container = this.$refs.especiesContainer;
                                    // Buscamos el elemento hijo basado en el índice
                                    const activeItem = container.querySelectorAll('.especie-item')[index];
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
                            plugins: 'lists link contextmenu paste',
                            toolbar: 'bold italic | link',
                            height: 200,
                            menubar: false,
                            branding: false,
                            statusbar: false,
                            contextmenu: false,
                            content_style: `
                                body {
                                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                                    font-size: 18px;
                                    margin: 4px; /* Margen pequeño para que no pegue al borde */
                                }
                                p { margin: 0; padding: 0; } /* Quitamos el margen a los párrafos */
                            `,

                            setup: (editor) => {
                                editor.on('init', () => {
                                    editor.setContent(this[parent][field] || '');
                                });
                                editor.on('change input undo redo SetContent', () => {
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
                        try {
                            const response = await fetch(`/verificar-existencia/${item.IdCAT}`);
                            const data = await response.json();
                            if (data.count === 1) {
                                Swal.fire({
                                    title: 'Especie ya registrada',
                                    html: `La especie <b>${item.taxon}</b> ya cuenta con una ficha técnica.<br>¿Deseas editarla?`,
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#4f46e5',
                                    cancelButtonColor: '#64748b',
                                    confirmButtonText: 'Ir a editar ficha',
                                    cancelButtonText: 'Cerrar',
                                    reverseButtons: true
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = `/editar-ficha/${data.fichas[0].especieId}`;
                                    } else {
                                        this.limpiarSeleccion();
                                    }
                                });
                                return;
                            }

                            if (data.count > 1) {
                                Swal.fire({
                                    title: 'Múltiples fichas encontradas',
                                    html: `Se encontraron <b>${data.count}</b> registros para <b>${item.taxon}</b>.<br><br>Selecciona cuál deseas gestionar desde el listado general.`,
                                    icon: 'info',
                                    showCancelButton: true,
                                    confirmButtonColor: '#4f46e5',
                                    cancelButtonColor: '#64748b',
                                    confirmButtonText: 'Ver todos los registros',
                                    cancelButtonText: 'Cerrar',
                                    reverseButtons: true
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = `/dashboard?q=${encodeURIComponent(item.taxon)}`;
                                    } else {
                                        this.limpiarSeleccion();
                                    }
                                });
                                return;
                            }

                        } catch (error) {
                            console.error("Error verificando existencia:", error);
                        }

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
                            origen: item.origen ? (typeof item.origen === 'string' ? item.origen.split(', ') : item.origen) : [],
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
                                'infoUICN_editor', 'infoCITES_editor', 'toxicidad_editor',
                                'ecorregiones_info_adicional_editor',
                                'vegetacion_info_adicional_a_editor',
                                'especies_asociadas_info_editor'
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
                                    },
                                    seccion: this.step
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
                                'infoUICN_editor', 'infoCITES_editor', 'toxicidad_editor',
                                'ecorregiones_info_adicional_editor',
                                'vegetacion_info_adicional_a_editor',
                                'especies_asociadas_info_editor'
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
                                text: `El valor final no puede ser menor al inicial.`,
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
                                    },
                                    seccion: this
                                        .step
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
                                this.step = proximoPaso;
                                if (this.step >= 2) this.yaAvanzo = true;
                                window.scrollTo({
                                    top: 0,
                                    behavior: 'smooth'
                                });

                            } else {
                                throw new Error(result.error || 'Error al guardar');
                            }
                        } catch (error) {
                            console.error(error);
                            Swal.fire('Error',
                                'No se pudo guardar la información antes de cambiar de sección.',
                                'error');
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

            .tox-tinymce {
                border-radius: 12px !important;
                border: 1px solid #e2e8f0 !important;
                overflow: hidden;
            }

            .tox .tox-toolbar__group {
                padding: 0px 4px !important;
            }

            .tox .tox-tbtn {
                height: 18px !important;
                width: 18px !important;
            }
        </style>
    </head>


    <body class="bg-gray-100 min-h-screen" x-data="formEspecies">
        <x-header />
        <main class="relative">
            <div class="sticky top-0 z-[1000] w-full bg-white border-b shadow-md">
                <div class="max-w-5xl mx-auto px-4 md:px-6 py-3">
                    <div class="min-h-[50px] flex items-center">
                        <div x-show="!isItemSelected" class="w-full" x-transition:enter="duration-200">
                            <div class="max-w-xl mx-auto relative">
                                <div
                                    class="flex items-center bg-gray-50 border-2 border-indigo-100 focus-within:border-indigo-500 rounded-xl px-4 py-2 shadow-sm">
                                    <svg class="w-5 h-5 text-indigo-400 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <input type="text" x-model="search"
                                        @input.debounce.300ms="showResults = true; buscarEspecie()"
                                        @keydown.down.prevent="if(especies.length > 0) selectedIndex = (selectedIndex + 1) % especies.length"
                                        @keydown.up.prevent="if(especies.length > 0) selectedIndex = (selectedIndex - 1 + especies.length) % especies.length"
                                        @keydown.enter.prevent="if(selectedIndex >= 0) seleccionar(especies[selectedIndex])"
                                        placeholder="Escribe el nombre de la especie..."
                                        class="w-full focus:outline-none bg-transparent font-medium text-slate-700">
                                </div>

                                <div x-show="showResults && especies.length > 0" x-cloak
                                    x-ref="especiesContainer"
                                    class="absolute z-[1001] w-full mt-2 bg-white shadow-2xl rounded-xl border border-slate-100 overflow-hidden max-h-60 overflow-y-auto">
                                    <template x-for="(item, index) in especies" :key="index">
                                        <div @click="seleccionar(item)"
                                            @mouseenter="selectedIndex = index"
                                            :class="{ 'bg-indigo-600 text-white': selectedIndex === index, 'text-slate-700': selectedIndex !== index }"
                                            class="px-5 py-3 cursor-pointer transition-colors !border-none">
                                            <span class="font-bold italic" x-text="item.taxon"></span>
                                            <span class="text-xs ml-2 opacity-70" x-text="item.AutorTaxon"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div x-show="isItemSelected" class="w-full flex items-center justify-between"
                            x-transition:enter="duration-200">
                            <div class="flex items-baseline gap-3 overflow-hidden">
                                <h1 class="text-[38px]  font-black text-indigo-900 italic truncate tracking-tight"
                                    x-text="form.taxon"></h1>
                                <span class="text-sm md:text-lg font-bold text-slate-400 truncate opacity-80"
                                    x-text="form.AutorTaxon"></span>
                            </div>

                            <button x-show="step === 1 && !isEdit" @click="limpiarSeleccion()"
                                class="flex items-center gap-1 text-[10px] font-black text-rose-500 bg-rose-50 px-3 py-1.5 rounded-full hover:bg-rose-100 transition-all border border-rose-100">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M6 18L18 6M6 6l12 12" stroke-width="3" />
                                </svg>
                                CAMBIAR ESPECIE
                            </button>
                        </div>
                    </div>

                    <div class="mt-3 overflow-x-auto no-scrollbar">
                        <div class="min-w-[1000px] lg:min-w-full relative py-2 px-2">

                            <div class="relative flex justify-between z-10">
                                @php
                                    $secciones = [
                                        'Clasificación',
                                        'Distribución',
                                        'Ambiente',
                                        'Biología',
                                        'Ecología',
                                        'Genética',
                                        'Importancia',
                                        'Conservación',
                                        'Prioritarias',
                                        'Necesidades',
                                        'Metadatos',
                                    ];
                                @endphp
                                @foreach ($secciones as $index => $titulo)
                                    @php $n = $index + 1; @endphp
                                    <div class="flex flex-col items-center cursor-pointer group"
                                        @click="if(isItemSelected || {{ $n }} == 1) navegarSeccion({{ $n }})">
                                        <div class="w-12 h-12 rounded-full border-2 flex items-center justify-center transition-all duration-300"
                                            :class="step == {{ $n }} ?
                                                'bg-indigo-600 border-indigo-600 text-white scale-110 shadow-md' : (
                                                    step > {{ $n }} ?
                                                    'bg-emerald-500 border-emerald-500 text-white' :
                                                    'bg-white border-slate-300 text-slate-400 group-hover:border-indigo-400'
                                                )">

                                            <template x-if="step > {{ $n }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </template>
                                            <template x-if="step <= {{ $n }}">
                                                <span class="text-[16px] font-black"
                                                    x-text="{{ $n }}"></span>
                                            </template>
                                        </div>
                                        <span class="mt-1 text-[14px] font-bold text-center w-20 transition-colors"
                                            :class="step == {{ $n }} ? 'text-indigo-900' : (step >
                                                {{ $n }} ? 'text-emerald-600' : 'text-slate-400')">
                                            {{ $titulo }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <main class="relative">
                <div class="sticky top-0 z-[1000] w-full bg-white shadow-md">
                </div>
                <div class="px-6">
                    <div x-show="step === 1" class="pt-10">
                        <x-seccion1 />
                    </div>
                    <div x-show="step === 2" x-cloak class="pt-10">
                        <x-seccion2 />
                    </div>
                    <div x-show="step === 3" x-cloak class="pt-10">
                        <x-seccion3 :tipos-suelo="$tiposSuelo" />
                    </div>
                </div>
            </main>
        </main>

        <div class="fixed right-6 top-1/2 -translate-y-1/2 z-[9999] flex flex-col gap-4">

            <div class="flex items-center justify-end group">
                <span class="opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-4 group-hover:translate-x-0 bg-slate-900 text-white text-[10px] font-black px-3 py-1.5 rounded-lg mr-3 tracking-widest shadow-xl pointer-events-none whitespace-nowrap">
                    Ir a inicio
                </span>
                <a href="/dashboard"
                    class="w-16 h-16 bg-slate-900 border-2 border-slate-900 text-white rounded-2xl shadow-lg flex items-center justify-center hover:bg-black transition-all duration-300 hover:scale-110 active:scale-95">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </a>
            </div>

            <div class="flex items-center justify-end group">
                <span class="opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-4 group-hover:translate-x-0 bg-amber-500 text-white text-[10px] font-black px-3 py-1.5 rounded-lg mr-3 tracking-widest shadow-xl pointer-events-none whitespace-nowrap">
                    Guardar avance
                </span>
                <button @click="guardarAvance()" type="button"
                    class="w-16 h-16 bg-amber-500 border-2 border-amber-500 text-white rounded-2xl shadow-lg flex items-center justify-center hover:bg-amber-600 transition-all duration-300 hover:scale-110 active:scale-95">
                    <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                </button>
            </div>

            <div class="flex items-center justify-end group" x-show="step > 1" x-cloak x-transition>
                <span class="opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-4 group-hover:translate-x-0 bg-slate-600 text-white text-[10px] font-black px-3 py-1.5 rounded-lg mr-3 tracking-widest shadow-xl pointer-events-none whitespace-nowrap">
                    Sección anterior
                </span>
                <button @click="navegarSeccion(step - 1)" type="button"
                    class="w-16 h-16 bg-slate-600 border-2 border-slate-600 text-white rounded-2xl shadow-lg flex items-center justify-center hover:bg-slate-700 transition-all duration-300 hover:scale-110 active:scale-95">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                </button>
            </div>

            <div class="flex items-center justify-end group">
                <span class="opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-4 group-hover:translate-x-0 bg-indigo-600 text-white text-[10px] font-black px-3 py-1.5 rounded-lg mr-3 tracking-widest shadow-xl pointer-events-none whitespace-nowrap">
                    Siguiente sección
                </span>
                <button @click="avanzarSeccion()" type="button"
                    class="w-16 h-16 bg-indigo-600 text-white rounded-2xl shadow-[0_10px_25px_rgba(79,70,229,0.3)] flex items-center justify-center hover:bg-indigo-700 transition-all duration-300 hover:scale-110 active:scale-95 border-2 border-white/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </div>
        </div>
    </body>

    </html>
