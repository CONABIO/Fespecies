<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormController extends Controller {

    public function index(Request $request) {
        $tempId = $request->query('tempId');
        $paises = DB::table('pais')->orderBy('nombrepais')->get();
        $tiposSuelo = DB::table('cat_preguntas')->where('idpregunta', 6)->get();
        $habitatsAntropicos = DB::table('cat_preguntas')->where('idpregunta', 1)->get();
        $vegSecundaria = DB::table('cat_preguntas')->where('idpregunta', 2)->get();
        $clima = DB::table('cat_preguntas')->where('idpregunta', 4)->get();
        $geoforma = DB::table('cat_preguntas')->where('idpregunta', 7)->get();
        $ecorregionesMarinasCat = DB::table('cat_preguntas')->where('idpregunta', 44)->get();
        $vegetacionCat = DB::table('vegetacion')->orderBy('descripcionVegetacion')->get();
        $estados = DB::table('estado')->orderBy('nombreEstado')->get();
        $ecorregiones = DB::table('ecorregion')->get();
        $ecosistemas = DB::table('ecosistema')->get();
        $alimentacionOptions = DB::table('cat_preguntas')
            ->where('idpregunta', 9)
            ->orderBy('descn1')
            ->orderBy('idopcion')
            ->get()
            ->map(function($item) {
                $texto = $item->descn1;
                if (!empty($item->descn2)) {
                    $texto = !empty($item->descn3) ? "{$item->descn2}({$item->descn3})" : $item->descn2;
                }
                return ['v' => (string)$item->idopcion, 't' => $texto, 'g' => $item->descn1];
            });

        $habitoOptions = DB::table('cat_preguntas')->where('idpregunta', 45)->select('idopcion as v', 'descn1 as t')->get();
        $formaVidaOptions = DB::table('cat_preguntas')->where('idpregunta', 12)->select('idopcion as v', 'descn1 as t')->get();
        $expresionFloresOptions = DB::table('cat_preguntas')->where('idpregunta', 49)->select('idopcion as v', 'descn1 as t')->get();
        $expresionIndividuosOptions = DB::table('cat_preguntas')->where('idpregunta', 50)->select('idopcion as v', 'descn1 as t')->get();
        $expresionPoblacionesOptions = DB::table('cat_preguntas')->where('idpregunta', 51)->select('idopcion as v', 'descn1 as t')->get();
        $polinizacionOptions = DB::table('cat_preguntas')->whereIn('idpregunta', [48, 53])->select('idopcion as v', 'descn1 as t')->get();
        $caracFrutoOptions = DB::table('cat_caracfruto')->get()->map(function($item) {
            $arr = (array)$item;
            return [
                'v' => (string)array_values($arr)[0],
                't' => (string)array_values($arr)[1]
            ];
        });

       $tipoDispersionOptions = DB::table('cat_preguntas')
            ->where('idpregunta', 15)
            ->get()
            ->map(function($item) {
                return [
                    'v' => (string)$item->idopcion,
                    't' => (string)$item->descn1
                ];
            })->values()->all();

        $estructuraDispersionOptions = DB::table('cat_preguntas')
            ->where('idpregunta', 16)
            ->get()
            ->map(function($item) {
                return [
                    'v' => (string)$item->idopcion,
                    't' => (string)$item->descn1
                ];
            })->values()->all();


        $estrategiaTroficaOptions = DB::table('cat_estrategiatrofica')
            ->get()
            ->map(function($item) {
                $arr = (array)$item;
                return [
                    'v' => (string)array_values($arr)[0],
                    't' => (string)array_values($arr)[1]
                ];
            });

        return view('form', compact(
            'tempId', 'paises', 'estados', 'tiposSuelo', 'habitatsAntropicos',
            'vegSecundaria', 'clima', 'geoforma', 'vegetacionCat', 'ecorregiones',
            'ecosistemas', 'ecorregionesMarinasCat', 'alimentacionOptions', 'habitoOptions',
            'formaVidaOptions', 'estrategiaTroficaOptions', 'expresionFloresOptions',
            'expresionIndividuosOptions', 'expresionPoblacionesOptions', 'polinizacionOptions','caracFrutoOptions',
            'tipoDispersionOptions','estructuraDispersionOptions'
        ));
    }

    public function obtenerMunicipios($nombreEdo) {
        $municipios = DB::table('municipio')
            ->where('nombreEstado', $nombreEdo)
            ->orderBy('nombreMunicipio')
            ->get();
        return response()->json($municipios);
    }

    public function buscar(Request $request) {
        try {
            $query = $request->get('q');
            if (!$query) return response()->json([]);
            $resultados = DB::connection('mysql_catalogo')
                ->table('_TransformaTablaNombre')
                ->whereIn('EstatusTaxon', ['aceptado', 'válido'])
                ->where('taxon', 'LIKE', "%{$query}%")
                ->select(
                    'taxon', 'IdNombre', 'IdNombreRel', 'Reino', 'Clase', 'Orden',
                    'Familia', 'Genero', 'Categinfra', 'AutorTaxon', 'EstatusTaxon',
                    'Especie_epiteto', 'Nombreinfra', 'Divisionphylum', 'IdCAT',
                    'Cites', 'Iucn', 'Nom'
                )
                ->limit(10)
                ->get();

            $resultadosProcesados = $resultados->map(function ($item) {
                $nombresRelacionales = DB::connection('mysql_catalogo')
                    ->table('Nombre')
                    ->join('RelNomNomComunRegion', 'Nombre.IdNombre', '=', 'RelNomNomComunRegion.IdNombre')
                    ->join('NomComun', 'RelNomNomComunRegion.IdNomComun', '=', 'NomComun.IdNomComun')
                    ->join('Region', 'RelNomNomComunRegion.IdRegion', '=', 'Region.IdRegion')
                    ->where('Nombre.IdNombre', $item->IdNombre)
                    ->select('NomComun.NomComun as nombre', 'NomComun.Lengua as lengua')
                    ->groupBy('NomComun.NomComun', 'NomComun.Lengua')
                    ->get();

                $nombresListado = [];
                foreach ($nombresRelacionales as $nc) {
                    $nombresListado[] = [
                        'nombre' => $nc->nombre,
                        'lengua' => $nc->lengua,
                        'editable' => false
                    ];
                }
                $item->nombres_comunes_array = $nombresListado;
                return $item;
            });

            return response()->json($resultadosProcesados);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function guardarSeccion(Request $request, $id = null) {
        DB::beginTransaction();
        try {
            $f = $request->form;
            $especieId = $f['especieId'];
            $seccion = $request->seccion;
            if ($seccion == 1) {
                DB::table('taxon')->updateOrInsert(
                    ['especieId' => $especieId],
                    [
                        'reino' => $f['Reino'],
                        'divisionphylum' => $f['Divisionphylum'],
                        'clase' => $f['Clase'],
                        'orden' => $f['Orden'],
                        'familia' => $f['Familia'],
                        'genero' => $f['Genero'],
                        'especie' => $f['Especie_epiteto'],
                        'infraespecie' => $f['Nombreinfra'],
                        'categinfra' => $f['Categinfra'],
                        'estatus' => $f['EstatusTaxon'],
                        'autor' => $f['AutorTaxon'],
                        'IdCAT' => $f['IdCAT'],
                        'especiesSmilares' => $f['especiesSmilares'] ?? null,
                        'descripcionOrigen' => $f['descripcionOrigen'] ?? null,
                        'resumenEspecie' => $f['resumenEspecie'] ?? null,
                        'infoAddNombreCientifico' => $f['infoAddNombreCientifico'] ?? null,
                        'infoUICN' => $f['infoUICN'] ?? null,
                        'infoCITES' => $f['infoCITES'] ?? null,
                        'descEspecie' => $f['descEspecie'] ?? null,
                        'origen' => is_array($f['origen']) ? implode(', ', $f['origen']) : $f['origen'],
                        'largoinicialhembras' => $f['largoinicialhembras'] ?? null,
                        'largofinalhembras' => $f['largofinalhembras'] ?? null,
                        'largoinicialmachos' => $f['largoinicialmachos'] ?? null,
                        'largofinalmachos' => $f['largofinalmachos'] ?? null,
                        'pesoinicialhembras' => $f['pesoinicialhembras'] ?? null,
                        'pesofinalhembras' => $f['pesofinalhembras'] ?? null,
                        'pesoinicialmachos' => $f['pesoinicialmachos'] ?? null,
                        'pesofinalmachos' => $f['pesofinalmachos'] ?? null,
                        'toxicidad' => $f['toxicidad'] ?? null,
                        'siNoToxicidad' => $f['siNoToxicidad'] ?? null,
                        'tipoficha' => 'Prioritaria',
                        'promedioLargoHembras' => $f['promedioLargoHembras'] ?? null,
                        'unidadLargoHembras' => $f['unidadLargoHembras'] ?? null,
                        'promedioLargoMachos' => $f['promedioLargoMachos'] ?? null,
                        'unidadLargoMachos' => $f['unidadLargoMachos'] ?? null,
                        'promedioPesoHembras' => $f['promedioPesoHembras'] ?? null,
                        'unidadPesoHembras' => $f['unidadPesoHembras'] ?? null,
                        'promedioPesoMachos' => $f['promedioPesoMachos'] ?? null,
                        'unidadPesoMachos' => $f['unidadPesoMachos'] ?? null,
                    ]
                );

                DB::table('nombrecomun')->where('especieId', $especieId)->delete();
                if (!empty($f['nombres_comunes'])) {
                    $ncInsert = [];
                    foreach ($f['nombres_comunes'] as $n) {
                        if (isset($n['editable']) && $n['editable'] == true && !empty($n['nombre'])) {
                            $ncInsert[] = [
                                'especieId' => $especieId,
                                'nombre' => trim($n['nombre']),
                                'lenguaje' => !empty($n['lengua']) ? trim($n['lengua']) : 'EMPTY',
                                'citanomcomun' => !empty($n['bibliografia']) ? $n['bibliografia'] : '<br>'
                            ];
                        }
                    }
                    if(!empty($ncInsert)) DB::table('nombrecomun')->insert($ncInsert);
                }

                DB::table('sinonimo')->where('especieId', $especieId)->delete();
                if (!empty($f['sinonimos'])) {
                    $sinInsert = [];
                    foreach ($f['sinonimos'] as $s) {
                        if (isset($s['editable']) && $s['editable'] == true && !empty($s['sinonimo'])) {
                            $sinInsert[] = [
                                'especieId' => $especieId,
                                'nombreSimple' => trim($s['sinonimo']),
                                'autoridad' => $s['autor'] ?? 'EMPTY',
                                'anio' => $s['anio'] ?? null
                            ];
                        }
                    }
                    if(!empty($sinInsert)) DB::table('sinonimo')->insert($sinInsert);
                }

                DB::table('legislacion')->where('especieId', $especieId)->delete();
                $legisInsert = [];
                if (!empty($f['riesgoUICN'])) $legisInsert[] = ['especieId' => $especieId, 'nombreLegislacion' => 'UICN', 'estatusLegalProteccion' => $f['riesgoUICN'], 'infoAdicional' => $f['infoUICN'] ?? 'EMPTY'];
                if (!empty($f['cites'])) $legisInsert[] = ['especieId' => $especieId, 'nombreLegislacion' => 'CITES', 'estatusLegalProteccion' => $f['cites'], 'infoAdicional' => $f['infoCITES'] ?? 'EMPTY'];
                if (isset($f['nom059']) && is_array($f['nom059'])) {
                    foreach ($f['nom059'] as $year => $data) {
                        $categoria = $data['categoria'] ?? null;
                        $info = $data['info'] ?? null;
                        $infoLimpia = ($info === '<p>&nbsp;</p>' || $info === '&nbsp;' || empty($info)) ? 'EMPTY' : $info;
                        if (!empty($categoria) || $infoLimpia !== 'EMPTY') {
                            $legisInsert[] = [
                                'especieId' => $especieId,
                                'nombreLegislacion' => "NOM-059-SEMARNAT-{$year}",
                                'estatusLegalProteccion' => $categoria ?? '',
                                'infoAdicional' => $infoLimpia
                            ];
                        }
                    }
                }
                if(!empty($legisInsert)) DB::table('legislacion')->insert($legisInsert);
            }

            if ($seccion == 2) {
                $paisesArray = (array)($f['paises_seleccionados'] ?? []);
                $estadosArray = (array)($f['estados_seleccionados'] ?? []);
                $municipiosNuevos = array_filter((array)($f['municipios_seleccionados'] ?? []), 'is_numeric');
                $paisesTxt = !empty($paisesArray) ? implode(', ', $paisesArray) : null;

                DB::table('distribucion')->updateOrInsert(
                    ['especieId' => $especieId],
                    [
                        'distribucion'        => $paisesTxt,
                        'InfoAdicionalPais'   => $f['dist_mundial_info'] ?? null,
                        'InfoAdicionalEdo'    => $f['info_adicional_estado'] ?? null,
                        'infoAdicionalMun'    => $f['info_adicional_municipio'] ?? null,
                        'historicaPotencial'  => $f['potencial_info'] ?? null,
                        'infoadicionalmexedo' => "Registros: " . count($municipiosNuevos) . " municipios."
                    ]
                );

                $distId = DB::table('distribucion')->where('especieId', $especieId)->value('distribucionid');

                if ($distId) {
                    $pIdsNuevos = DB::table('pais')->whereIn('nombrepais', $paisesArray)->pluck('paisId')->toArray();
                    $pIdsActuales = DB::table('reldistribucionpais')->where('distribucionid', $distId)->pluck('paisId')->toArray();
                    $pInsertar = array_diff($pIdsNuevos, $pIdsActuales);
                    $pEliminar = array_diff($pIdsActuales, $pIdsNuevos);

                    if (!empty($pEliminar)) DB::table('reldistribucionpais')->where('distribucionid', $distId)->whereIn('paisId', $pEliminar)->delete();
                    if (!empty($pInsertar)) {
                        $insP = array_map(fn($id) => ['distribucionid' => $distId, 'paisId' => $id, 'tipopais' => 0], $pInsertar);
                        DB::table('reldistribucionpais')->insert($insP);
                    }

                    $eIdsNuevos = DB::table('estado')->whereIn('nombreEstado', $estadosArray)->pluck('estadoId')->toArray();
                    $eIdsActuales = DB::table('reldistribucionestado')->where('distribucionid', $distId)->pluck('estadoId')->toArray();
                    $eInsertar = array_diff($eIdsNuevos, $eIdsActuales);
                    $eEliminar = array_diff($eIdsActuales, $eIdsNuevos);

                    if (!empty($eEliminar)) DB::table('reldistribucionestado')->where('distribucionid', $distId)->whereIn('estadoId', $eEliminar)->delete();
                    if (!empty($eInsertar)) {
                        $insE = array_map(fn($id) => ['distribucionid' => $distId, 'estadoId' => $id], $eInsertar);
                        DB::table('reldistribucionestado')->insert($insE);
                    }

                    $municipiosActuales = DB::table('reldistribucionmunicipio')->where('distribucionid', $distId)->pluck('municipioId')->toArray();
                    $mInsertar = array_diff($municipiosNuevos, $municipiosActuales);
                    $mEliminar = array_diff($municipiosActuales, $municipiosNuevos);

                    if (!empty($mEliminar)) {
                        DB::table('reldistribucionmunicipio')->where('distribucionid', $distId)->whereIn('municipioId', $mEliminar)->delete();
                    }
                    if (!empty($mInsertar)) {
                        $dataM = [];
                        foreach ($mInsertar as $id) {
                            $dataM[] = ['distribucionid' => $distId, 'municipioId' => $id];
                        }
                        foreach (array_chunk($dataM, 1000) as $chunk) {
                            DB::table('reldistribucionmunicipio')->insert($chunk);
                        }
                    }
                }

                DB::table('endemica')->updateOrInsert(
                    ['especieId' => $especieId],
                    [
                        'endemicaMexico'        => (($f['siNoEndemismo'] ?? '0') == '1') ? 'SÍ' : 'NO',
                        'endemicaA'             => $f['endemica_a'] ?? null,
                        'infoAdicionalEndemica' => $f['endemismo_info'] ?? null
                    ]
                );
            }

            if ($seccion == 3) {
                $saveMultiSelect = function($idPregunta, $valores, $columna = 'descn1') use ($especieId) {
                    DB::table('caracteristicasespecie')->where('especieId', $especieId)->where('idpregunta', $idPregunta)->delete();
                    if (!empty($valores)) {
                        $opcionesIds = DB::table('cat_preguntas')
                            ->where('idpregunta', $idPregunta)
                            ->whereIn($columna, (array)$valores)
                            ->pluck('idopcion');
                        $data = $opcionesIds->map(fn($id) => [
                            'especieId' => $especieId,
                            'idpregunta' => $idPregunta,
                            'idopcion' => $id
                        ])->toArray();
                        if(!empty($data)) DB::table('caracteristicasespecie')->insert($data);
                    }
                };

                $saveObservacion = function($idPregunta, $texto) use ($especieId) {
                    if (!empty($texto)) {
                        DB::table('observacionescarac')->updateOrInsert(
                            ['especieId' => $especieId, 'idpregunta' => $idPregunta],
                            ['infoadicional' => $texto]
                        );
                    }
                };

                $saveMultiSelect(6, $f['suelo_tipo'] ?? []);
                $saveMultiSelect(1, $f['habitats_antropicos'] ?? []);
                $saveMultiSelect(2, $f['vegetacion_secundaria'] ?? []);
                $saveMultiSelect(4, $f['clima_tipo'] ?? [], 'descn2');
                $saveMultiSelect(7, $f['geoforma_tipo'] ?? []);
                $saveMultiSelect(44, $f['ecorregiones_marinas_ids'] ?? []);

                DB::table('caracteristicasespecie')->where('especieId', $especieId)->where('idpregunta', 3)->delete();
                if (!empty($f['tipo_vegetacion_a'])) {
                    $vegIds = DB::table('vegetacion')->whereIn('descripcionSubVegetacion', (array)$f['tipo_vegetacion_a'])->pluck('vegetacionId');
                    $vegData = $vegIds->map(fn($id) => ['especieId' => $especieId, 'idpregunta' => 3, 'idopcion' => $id])->toArray();
                    if(!empty($vegData)) DB::table('caracteristicasespecie')->insert($vegData);
                }

                $saveObservacion(6, $f['suelo_info'] ?? null);
                $saveObservacion(4, $f['clima_info'] ?? null);
                $saveObservacion(7, $f['geoforma_info'] ?? null);
                $saveObservacion(2, $f['especies_asociadas_info'] ?? null);
                $saveObservacion(3, $f['vegetacion_info_adicional_a'] ?? null);

                $vertical = is_array($f['habitat_marino_vertical'] ?? null)
                    ? implode(', ', $f['habitat_marino_vertical'])
                    : ($f['habitat_marino_vertical'] ?? null);
                $horizontal = is_array($f['habitat_marino_horizontal'] ?? null)
                    ? implode(', ', $f['habitat_marino_horizontal'])
                    : ($f['habitat_marino_horizontal'] ?? null);

                DB::table('habitat')->updateOrInsert(
                    ['especieId' => $especieId],
                    [
                        'tipoAmbiente' => $f['tipoAmbiente'],
                        'habitatAgropecuario' => $f['habitatAgropecuario'] ?? null,
                        'zonaUrbana' => $f['zonaUrbana']?? null,
                        'VegetacionSecundaria' => $f['VegetacionSecundaria']?? null,
                        'intervaloaltitudinalinicial' => $f['intervaloaltitudinalinicial']?? null,
                        'intervaloaltitudinalfinal' => $f['intervaloaltitudinalfinal']?? null,
                        'infoAddintervaloaltitudinal' => $f['infoAddintervaloaltitudinal']?? null,
                        'temperaturainicial' => $f['temperaturainicial']?? null,
                        'temperaturafinal' => $f['temperaturafinal']?? null,
                        'infoaddtemperatura' => $f['infoaddtemperatura']?? null,
                        'precipitacioninicial' => $f['precipitacioninicial']?? null,
                        'precipitacionfinal' => $f['precipitacionfinal']?? null,
                        'infoaddprecipitacion' => $f['infoaddprecipitacion']?? null,
                        'humedadinicial' => $f['humedadinicial']?? null,
                        'humedadfinal' => $f['humedadfinal']?? null,
                        'infoaddhumedad' => $f['infoaddhumedad']?? null,
                        'infoAddEcorregion' => $f['ecorregiones_info_adicional'] ?? null,
                        'vertical' => $vertical,
                        'horizontal' => $horizontal,
                        'infoAddVH' => $f['habitat_marino_infoAddVH']?? null,
                        'especiesAsociadas' => $f['habitat_marino_especiesAsociadas']?? null,
                        'disturbiosAntropicos' => $f['habitat_marino_disturbiosAntropicos']?? null,
                        'infoAddDisturbiosAntropicos'=> $f['habitat_marino_infoAddDisturbiosAntropicos'] ?? null,
                        'interbatimetricoinicial' => $f['interbatimetricoinicial'] ?? null,
                        'interbatimetricofinal' => $f['interbatimetricofinal'] ?? null,
                        'infoaddinterbatimetrico' => $f['infoaddinterbatimetrico'] ?? null,
                        'amplitudmareasinicial' => $f['amplitudmareasinicial'] ?? null,
                        'amplitudmareasfinal' => $f['amplitudmareasfinal'] ?? null,
                        'infoaddamplitudmareas' => $f['infoaddamplitudmareas'] ?? null,
                        'salinidadinicial' => $f['salinidadinicial'] ?? null,
                        'salinidadfinal' => $f['salinidadfinal'] ?? null,
                        'unidadsalinidad' => $f['unidadsalinidad'] ?? null,
                        'oxigenoinicial' => $f['oxigenoinicial'] ?? null,
                        'oxigenofinal' => $f['oxigenofinal'] ?? null,
                        'phinicial' => $f['phinicial'] ?? null,
                        'phfinal' => $f['phfinal'] ?? null,
                        'temeperaturainicial' => $f['temeperaturainicial'] ?? null,
                        'temeperaturafinal' => $f['temeperaturafinal'] ?? null,
                        'corrientes' => $f['corrientes'] ?? null,
                        'infoaddcaracagua' => $f['infoaddcaracagua'] ?? null,
                        'interbatimetricopromedio' => $f['interbatimetricopromedio'] ?? null,
                        'amplitudmareaspromedio' => $f['amplitudmareaspromedio'] ?? null,
                        'salinidadpromedio' => $f['salinidadpromedio'] ?? null,
                        'oxigenopromedio' => $f['oxigenopromedio'] ?? null,
                        'phpromedio' => $f['phpromedio'] ?? null,
                        'temeperaturapromedio' => $f['temeperaturapromedio'] ?? null,
                    ]
                );

                $habitatId = DB::table('habitat')->where('especieId', $especieId)->value('habitatId');

                if ($habitatId) {
                    DB::table('relecorregionhabitat')->where('habitatId', $habitatId)->delete();
                    if (!empty($f['ecorregiones_terrestres'])) {
                        $ecoIds = DB::table('ecorregion')->whereIn('descripcion', $f['ecorregiones_terrestres'])->pluck('ecorregionId');
                        $ecoInsert = $ecoIds->map(fn($id) => ['habitatId' => $habitatId, 'ecorregionId' => $id])->toArray();
                        DB::table('relecorregionhabitat')->insert($ecoInsert);
                    }

                    DB::table('relecosistemahabitat')->where('habitatId', $habitatId)->delete();
                    if (!empty($f['ecosistemas'])) {
                        $ecsIds = DB::table('ecosistema')->whereIn('tipoecosistema', $f['ecosistemas'])->pluck('ecosistemaid');
                        $ecsInsert = $ecsIds->map(fn($id) => ['habitatId' => $habitatId, 'ecosistemaid' => $id])->toArray();
                        DB::table('relecosistemahabitat')->insert($ecsInsert);
                    }
                }
            }

            if ($seccion == 4) {
                $reino = trim($f['Reino'] ?? '');
                DB::table('habitat')->updateOrInsert(
                    ['especieId' => $especieId],
                    [
                        'tipoCiclo' => $f['tipoCiclo'] ?? null,
                        'aspectos'  => $f['aspectos'] ?? null,
                        'uso'       => $f['uso_habitat'] ?? null,
                    ]
                );
                $saveCaracMulti = function($idPregunta, $valores) use ($especieId) {
                    DB::table('caracteristicasespecie')->where('especieId', $especieId)->where('idpregunta', $idPregunta)->delete();
                    $vals = array_unique(array_filter((array)$valores));
                    if (!empty($vals)) {
                        $inserts = [];
                        $yaInsertados = [];
                        foreach ($vals as $val) {
                            $opId = is_numeric($val) ? (int)$val : DB::table('cat_preguntas')->where('idpregunta', $idPregunta)->where('descn1', $val)->value('idopcion');
                            if ($opId && !in_array($opId, $yaInsertados)) {
                                $yaInsertados[] = $opId;
                                $inserts[] = [
                                    'especieId'  => $especieId,
                                    'idpregunta' => $idPregunta,
                                    'idopcion'   => $opId
                                ];
                            }
                        }
                        if (!empty($inserts)) {
                            DB::table('caracteristicasespecie')->insert($inserts);
                        }
                    }
                };

                $saveCaracMulti(9, $f['alimentacion'] ?? []);
                $saveCaracMulti(45, $f['habito_planta'] ?? []);
                $formaVidaVals = ($reino === 'Plantae') ? ($f['forma_vida_planta'] ?? []) : ($f['forma_vida_otros'] ?? []);
                $saveCaracMulti(12, $formaVidaVals);
                $saveCaracMulti(49, $f['expresion_flores'] ?? []);
                $saveCaracMulti(50, $f['expresion_individuos'] ?? []);
                $saveCaracMulti(51, $f['expresion_poblaciones'] ?? []);
                $saveCaracMulti(53, $f['tipo_polinizacion'] ?? []);
                $saveCaracMulti(15, $f['dispersion_tipo'] ?? []);
                $saveCaracMulti(16, $f['dispersion_estructura'] ?? []);
                $historia = DB::table('historianatural')->where('especieId', $especieId)->first();
                $idReproAnimal = $historia->reproduccionAnimalId ?? null;
                $idReproVeg = $historia->reproduccionVegetalId ?? null;
                if ($reino === 'Animalia') {
                    $datosAnimal = [
                        'descripcion'                => $f['descripcion_reproduccion'] ?? null,
                        'dimorfismoSexual'           => ($f['hay_dimorfismo'] ?? '') === 'si' ? 'Sí' : (($f['hay_dimorfismo'] ?? '') === 'no' ? 'no' : null),
                        'additionalInfoDimorfiasmo'  => $f['dimorfismo_ia'] ?? ($f['tipo_dimorfismo'] ?? null),
                        'tipoFecundacion'            => $f['tipo_fecundacion_animal'] ?? null,
                        'descripcionTipoFec'         => $f['sistema_repro_animal_ia'] ?? null,
                        'noEventos'                  => $f['estrategia_reproductiva_animal'] ?? null,
                        'tiempoentrecriasinicial'    => is_numeric($f['tiempo_eventos_min'] ?? '') ? $f['tiempo_eventos_min'] : null,
                        'tiempoentrecriasfinal'      => is_numeric($f['tiempo_eventos_max'] ?? '') ? $f['tiempo_eventos_max'] : null,
                        'edadPrimeraRepro'           => $f['edad_primera_reproduccion'] ?? null,
                        'duracionVidaRepro'          => $f['duracion_vida_reproductiva'] ?? null,
                        'noHuevosCrias'              => $f['crias_promedio'] ?? ($f['crias_min'] ?? null),
                        'cuidadoParental'            => ($f['cuidado_parental'] ?? '') === 'si' ? 'Sí' : (($f['cuidado_parental'] ?? '') === 'no' ? 'no' : null),
                        'cuidadoParentalPor'         => $f['cuidado_parental_vc'] ?? null,
                        'tiempoCuidadoParental'      => $f['tiempo_cuidado_parental'] ?? null,
                    ];

                    if ($idReproAnimal && DB::table('reproduccionanimal')->where('reproduccionAnimalId', $idReproAnimal)->exists()) {
                        DB::table('reproduccionanimal')->where('reproduccionAnimalId', $idReproAnimal)->update($datosAnimal);
                    } else {
                        $idReproAnimal = DB::table('reproduccionanimal')->insertGetId($datosAnimal);
                    }

                } elseif ($reino === 'Plantae') {

                        $desempaquetarValor = function($val) {
                            if (empty($val)) return null;
                            while (is_string($val) && (str_starts_with(trim($val), '[') || str_starts_with(trim($val), '{') || str_starts_with(trim($val), '"'))) {
                                $decoded = json_decode($val, true);
                                if (json_last_error() === JSON_ERROR_NONE) { $val = $decoded; } else { break; }
                            }
                            if (is_array($val)) {
                                $planos = [];
                                array_walk_recursive($val, function($item) use (&$planos) {
                                    if (!empty($item) && is_string($item)) {
                                        $itemLimpio = trim(stripslashes(str_replace(['"', '[', ']', '\\'], '', $item)));
                                        if (!empty($itemLimpio)) { $planos[] = $itemLimpio; }
                                    }
                                });
                                return !empty($planos) ? implode(', ', array_unique($planos)) : null;
                            }
                            return trim(stripslashes(str_replace(['"', '[', ']', '\\'], '', (string)$val)));
                        };

                        $florMesesArray = is_array($f['floracion_meses'] ?? null) ? $f['floracion_meses'] : [];
                        $fructiMesesArray = is_array($f['fructificacion_meses'] ?? null) ? $f['fructificacion_meses'] : [];

                        $stringFlorMeses = !empty($florMesesArray) ? implode(', ', $florMesesArray) : null;
                        $stringFructiMeses = !empty($fructiMesesArray) ? implode(', ', $fructiMesesArray) : null;

                        $mapMesesNum = [
                            'Ene' => 1, 'Feb' => 2, 'Mar' => 3, 'Abr' => 4,
                            'May' => 5, 'Jun' => 6, 'Jul' => 7, 'Ago' => 8,
                            'Sep' => 9, 'Oct' => 10, 'Nov' => 11, 'Dic' => 12
                        ];

                        $numerosFlor = array_map(fn($m) => $mapMesesNum[$m] ?? null, $florMesesArray);
                        $numerosFlor = array_filter($numerosFlor);
                        $cadenaMesesFlorNum = !empty($numerosFlor) ? implode(', ', $numerosFlor) : null;

                        $numerosFructi = array_map(fn($m) => $mapMesesNum[$m] ?? null, $fructiMesesArray);
                        $numerosFructi = array_filter($numerosFructi);
                        $cadenaMesesFructiNum = !empty($numerosFructi) ? implode(', ', $numerosFructi) : null;

                        $tamanoSemillaFinal = $f['semillas_tam_promedio'] ?? null;
                        if (empty($tamanoSemillaFinal) && (!empty($f['semillas_tam_min']) || !empty($f['semillas_tam_max']))) {
                            $tamanoSemillaFinal = "De " . ($f['semillas_tam_min'] ?? '') . " a " . ($f['semillas_tam_max'] ?? '');
                        }

                        $datosPlanta = [
                            'descripcion'            => $f['descripcion_reproduccion'] ?? null,
                            'aislamientoOrganos'     => $desempaquetarValor($f['aislamiento_temporal'] ?? null),
                            'descAislaOrganos'       => $f['aislamiento_temporal_ia'] ?? null,
                            'sistReproAsexuales'     => $desempaquetarValor($f['sistemas_reproductivos_asexuales'] ?? null),
                            'fecuandacion'           => $desempaquetarValor($f['tipo_fecundacion_plantae'] ?? null),
                            'aperturaFlor'           => $desempaquetarValor($f['flor_horario_apertura'] ?? null),
                            'tiempoFloracion'        => $desempaquetarValor($f['flor_longevidad'] ?? null),
                            'mesInicio'              => $cadenaMesesFlorNum,
                            'mesFinal'               => $cadenaMesesFlorNum,
                            'addinfotiempoflora'     => $f['floracion_ia'] ?? null,
                            'cantidadnectarinicial'  => is_numeric($f['cantidad_nectar_min'] ?? '') ? $f['cantidad_nectar_min'] : null,
                            'cantidadnectarfinal'    => is_numeric($f['cantidad_nectar_max'] ?? '') ? $f['cantidad_nectar_max'] : null,
                            'promedioCantidadNectar' => is_numeric($f['cantidad_nectar_promedio'] ?? '') ? $f['cantidad_nectar_promedio'] : null,
                            'addinfocantidadnectar'  => $f['cantidad_nectar_ia'] ?? null,
                            'cantidadpolen'          => $f['cantidad_polen'] ?? null,
                            'mesInicialFructi'       => $cadenaMesesFructiNum,
                            'mesFinalFructi'         => $cadenaMesesFructiNum,
                            'addinfotiempofructi'    => $f['fructificacion_ia'] ?? null,
                            'nofrutosinicial'        => is_numeric($f['frutos_min'] ?? '') ? $f['frutos_min'] : null,
                            'nofrutosfinal'          => is_numeric($f['frutos_max'] ?? '') ? $f['frutos_max'] : null,
                            'promNoFrutos'           => is_numeric($f['frutos_promedio'] ?? '') ? $f['frutos_promedio'] : null,
                            'caracFruto'             => is_numeric($f['fruto_caracteristicas'] ?? '') ? $f['fruto_caracteristicas'] : null,
                            'descCaracFruto'         => $f['frutos_ia'] ?? null,
                            'noEventos'              => $f['estrategia_reproductiva_planta'] ?? null,
                            'descNoEventos'          => $f['estrategia_reproductiva_planta_ia'] ?? null,
                            'nosemillasinicial'      => is_numeric($f['semillas_num_min'] ?? '') ? $f['semillas_num_min'] : null,
                            'nosemillasfinal'        => is_numeric($f['semillas_num_max'] ?? '') ? $f['semillas_num_max'] : null,
                            'tamanioSemilla'         => $tamanoSemillaFinal,
                            'germinacioninicial'     => is_numeric($f['germinacion_min'] ?? '') ? $f['germinacion_min'] : null,
                            'germinacionfinal'       => is_numeric($f['germinacion_max'] ?? '') ? $f['germinacion_max'] : null,
                            'plantulasinicial'       => is_numeric($f['supervivencia_min'] ?? '') ? $f['supervivencia_min'] : null,
                            'plantulasfinal'         => is_numeric($f['supervivencia_max'] ?? '') ? $f['supervivencia_max'] : null,
                            'infoaddplantulas'       => $f['supervivencia_ia'] ?? null,
                            'promNoSemillas'         => is_numeric($f['semillas_num_promedio'] ?? '') ? $f['semillas_num_promedio'] : null,
                            'porcentajeSuprePlantulas'   => is_numeric($f['supervivencia_promedio'] ?? '') ? $f['supervivencia_promedio'] : null,
                            'promTamanoSemillas'     => $f['semillas_tam_promedio'] ?? null,
                            'tamanoInicioSemilla'        => is_numeric($f['semillas_tam_min'] ?? '') ? $f['semillas_tam_min'] : null,
                            'tamamoFinalSemilla'         => is_numeric($f['semillas_tam_max'] ?? '') ? $f['semillas_tam_max'] : null,
                            'porcentajeGerminacion'  => is_numeric($f['germinacion_promedio'] ?? '') ? $f['germinacion_promedio'] : null,
                            'infoaddgerminacion'         => $f['germinacion_ia'] ?? null,
                             'caracToxica'            => (($f['semillas_toxicidad'] ?? '') === 'si') ? 'Sí' : 'no',
                            'caracLatente'           => (($f['semillas_latencia'] ?? '') === 'si') ? 'Sí' : 'no',
                            'infoAddSemillas'        => $f['semillas_caracteristicas_ia'] ?? null,
                        ];

                        if ($idReproVeg && DB::table('reproduccionvegetal')->where('reproduccionVegetalId', $idReproVeg)->exists()) {
                            DB::table('reproduccionvegetal')->where('reproduccionVegetalId', $idReproVeg)->update($datosPlanta);
                        } else {
                            $idReproVeg = DB::table('reproduccionvegetal')->insertGetId($datosPlanta);
                        }
                } elseif (in_array(strtolower($reino), ['fungi', 'hongos'])) {
                    try {
                        DB::table('reproduccionhongos')->updateOrInsert(
                            ['especieId' => $especieId],
                            ['descripcion' => $f['descripcion_reproduccion'] ?? null]
                        );
                    } catch (\Exception $e) {}
                }
                $estrategiaVal = is_array($f['estrategia_trofica'] ?? null) ? ($f['estrategia_trofica'][0] ?? null) : ($f['estrategia_trofica'] ?? null);

                DB::table('historianatural')->updateOrInsert(
                    ['especieId' => $especieId],
                    [
                        'reproduccionAnimalId'       => $idReproAnimal,
                        'reproduccionVegetalId'      => $idReproVeg,
                        'tipoReproduccion'           => $reino === 'Animalia' ? 'animal' : ($reino === 'Plantae' ? 'veget' : ''),
                        'conducta'                   => $f['caracteristicas_conductuales'] ?? null,
                        'tipopHabito'                => $f['periodo_actividad'] ?? null,
                        'infoaddperiodoactividad'    => $f['periodo_actividad'] ?? null,
                        'hibernacion'                => ($f['hibernacion'] ?? '') === 'si' ? 'Sí' : (($f['hibernacion'] ?? '') === 'no' ? 'no' : ($f['hibernacion'] ?? null)),
                        'infoaddhibernacion'         => $f['hibernacion_torpor_ia'] ?? null,
                        'ambitoHogareno'             => $f['ambito_hogareno_promedio'] ?? ($f['ambito_hogareno_min'] ?? null),
                        'mecanismosDefensa'          => $f['mecanismos_defensa'] ?? null,
                        'estrategiaTrofica'          => is_numeric($estrategiaVal) ? $estrategiaVal : null,
                        'descripcionEstrofica'       => $estrategiaVal,
                        'distanciadispercioninicial' => is_numeric($f['dispersion_dist_min'] ?? '') ? $f['dispersion_dist_min'] : null,
                        'distanciadispercionfinal'   => is_numeric($f['dispersion_dist_max'] ?? '') ? $f['dispersion_dist_max'] : null,
                    ]
                );

                try {
                    DB::table('demografiaamenazas')->updateOrInsert(
                        ['especieId' => $especieId],
                        ['organizacionSocial' => $f['organizacion_social'] ?? null]
                    );
                } catch (\Exception $e) {}
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'linea' => $e->getLine()
            ], 500);
        }
    }

    public function obtenerSinonimos(Request $request) {
        try {
            $id = $request->get('id');
            if (!$id) return response()->json([]);

            $sinonimos = DB::connection('mysql_catalogo')
                ->table('_TransformaTablaNombre')
                ->select('Taxon as sinonimo', 'AutorTaxon as autor')
                ->where('IdNombreRel', '=', $id)
                ->get();

            return response()->json($sinonimos);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function editarFicha($id) {
        $taxonRaw = DB::table('taxon')->where('especieId', $id)->first();
        if (!$taxonRaw) return redirect()->back()->with('error', 'No se encontró la ficha.');
        $t = (array)$taxonRaw;
        $getData = function($field) use ($t) {
            $val = $t[$field] ?? $t[strtolower($field)] ?? '';
            return ($val === 'EMPTY' || $val === '<p>&nbsp;</p>' || $val === '<br>' || $val === '&nbsp;') ? '' : $val;
        };

        $datosCatalogo = DB::connection('mysql_catalogo')
            ->table('_TransformaTablaNombre')
            ->where('IdCAT', $t['IdCAT'] ?? $t['idcat'] ?? '')
            ->first();

        $nombresCatalogo = [];
        if ($datosCatalogo) {
            $ncRelacionales = DB::connection('mysql_catalogo')
                ->table('Nombre')
                ->join('RelNomNomComunRegion', 'Nombre.IdNombre', '=', 'RelNomNomComunRegion.IdNombre')
                ->join('NomComun', 'RelNomNomComunRegion.IdNomComun', '=', 'NomComun.IdNomComun')
                ->join('Region', 'RelNomNomComunRegion.IdRegion', '=', 'Region.IdRegion')
                ->where('Nombre.IdNombre', $datosCatalogo->IdNombre)
                ->select('NomComun.NomComun as nombre', 'NomComun.Lengua as lengua')
                ->groupBy('NomComun.NomComun', 'NomComun.Lengua')->get();
            foreach ($ncRelacionales as $nc) {
                $nombresCatalogo[] = ['nombre' => trim($nc->nombre), 'lengua' => trim($nc->lengua), 'bibliografia' => '', 'editable' => false];
            }
        }
        $nombresLocalesLimpios = DB::table('nombrecomun')->where('especieId', $id)->get()->map(function($n) {
            return ['nombre' => trim($n->nombre), 'lengua' => trim($n->lenguaje), 'bibliografia' => ($n->citanomcomun === 'EMPTY' ? '' : $n->citanomcomun), 'editable' => true];
        })->toArray();

        $sinonimosCatalogo = [];
        if ($datosCatalogo) {
            $sinonimosCatalogo = DB::connection('mysql_catalogo')->table('_TransformaTablaNombre')
                ->select('Taxon as sinonimo', 'AutorTaxon as autor')
                ->where('IdNombreRel', '=', $datosCatalogo->IdNombre)->get()
                ->map(function($s) { return ['sinonimo' => trim($s->sinonimo), 'autor' => trim($s->autor), 'anio' => null, 'editable' => false]; })->toArray();
        }
        $sinonimosLocalesRaw = DB::table('sinonimo')->where('especieId', $id)->get();
        $sinonimosLocalesLimpios = [];
        foreach ($sinonimosLocalesRaw as $s) {
            $sinonimosLocalesLimpios[] = ['sinonimo' => trim($s->nombreSimple), 'autor' => $s->autoridad === 'EMPTY' ? '' : $s->autoridad, 'anio' => $s->anio, 'editable' => true];
        }

        $legisRows = DB::table('legislacion')->where('especieId', $id)->get();
        $nom059Processed = [
            '2001' => ['categoria' => '', 'info' => ''],
            '2010' => ['categoria' => '', 'info' => ''],
            '2019' => ['categoria' => '', 'info' => '']
        ];

        foreach ($legisRows as $row) {
            if (str_contains($row->nombreLegislacion, 'NOM-059')) {
                if (preg_match('/(2001|2010|2019)$/', $row->nombreLegislacion, $matches)) {
                    $year = $matches[1];
                    if (isset($nom059Processed[$year])) {
                        $nom059Processed[$year]['categoria'] = $row->estatusLegalProteccion ?? '';
                        $nom059Processed[$year]['info'] = ($row->infoAdicional === 'EMPTY' || empty($row->infoAdicional)) ? '' : $row->infoAdicional;
                    }
                }
            }
        }

        $dist = (array)DB::table('distribucion')->where('especieId', $id)->first();
        $distId = $dist['distribucionid'] ?? $dist['distribucionId'] ?? null;
        $paisesS = $distId ? DB::table('reldistribucionpais')->join('pais','reldistribucionpais.paisId','=','pais.paisId')->where('distribucionid', $distId)->pluck('nombrepais')->toArray() : [];
        $edosS   = $distId ? DB::table('reldistribucionestado')->join('estado','reldistribucionestado.estadoId','=','estado.estadoId')->where('distribucionid', $distId)->pluck('nombreEstado')->toArray() : [];

        $munsS   = $distId ? DB::table('reldistribucionmunicipio')->where('distribucionid', $distId)->pluck('municipioId')->toArray() : [];
        $endemismo = (array)DB::table('endemica')->where('especieId', $id)->first();
        $habitat = DB::table('habitat')->where('especieId', $id)->first();
        $bloquearAmbiente = ($habitat && !empty($habitat->tipoAmbiente) && strlen($habitat->tipoAmbiente) > 2) ? true : false;

        $getOpNombres = function($pId, $columna = 'descn1') use ($id) {
            return DB::table('caracteristicasespecie')
                ->join('cat_preguntas', 'caracteristicasespecie.idopcion', '=', 'cat_preguntas.idopcion')
                ->where('caracteristicasespecie.especieId', $id)
                ->where('caracteristicasespecie.idpregunta', $pId)
                ->pluck("cat_preguntas.$columna")
                ->toArray();
        };

        $ecorregionesTerrestresS = $habitat ? DB::table('relecorregionhabitat')
            ->join('ecorregion', 'relecorregionhabitat.ecorregionId', '=', 'ecorregion.ecorregionId')
            ->where('habitatId', $habitat->habitatId)->pluck('descripcion')->toArray() : [];

        $ecosistemasS = $habitat ? DB::table('relecosistemahabitat')
            ->join('ecosistema', 'relecosistemahabitat.ecosistemaid', '=', 'ecosistema.ecosistemaid')
            ->where('habitatId', $habitat->habitatId)->pluck('tipoecosistema')->toArray() : [];

        $tipoVegetacionS = DB::table('caracteristicasespecie')
            ->join('vegetacion', 'caracteristicasespecie.idopcion', '=', 'vegetacion.vegetacionId')
            ->where('especieId', $id)->where('idpregunta', 3)
            ->pluck('descripcionSubVegetacion')->toArray();

        $historia = DB::table('historianatural')->where('especieId', $id)->first();
        $reinoEspecie = trim($t['reino'] ?? '');
        $descripcionRepro = '';
        $reproAnimal = null;
        $reproVeg = null;

        if ($historia) {
            if ($reinoEspecie === 'Animalia' && !empty($historia->reproduccionAnimalId)) {
                $reproAnimal = DB::table('reproduccionanimal')->where('reproduccionAnimalId', $historia->reproduccionAnimalId)->first();
                $descripcionRepro = $reproAnimal->descripcion ?? '';
            } elseif ($reinoEspecie === 'Plantae' && !empty($historia->reproduccionVegetalId)) {
                $reproVeg = DB::table('reproduccionvegetal')->where('reproduccionVegetalId', $historia->reproduccionVegetalId)->first();
                $descripcionRepro = $reproVeg->descripcion ?? '';
            } elseif (in_array(strtolower($reinoEspecie), ['fungi', 'hongos'])) {
                try {
                    $descripcionRepro = DB::table('reproduccionhongos')->where('especieId', $id)->value('descripcion') ?? '';
                } catch (\Exception $e) {}
            }
        }

        $demografia = DB::table('demografiaamenazas')->where('especieId', $id)->first();

        $alimentacionOptions = DB::table('cat_preguntas')
            ->where('idpregunta', 9)
            ->orderBy('descn1')
            ->orderBy('idopcion')
            ->get()
            ->map(function($item) {
                $texto = $item->descn1;
                if (!empty($item->descn2)) {
                    $texto = !empty($item->descn3) ? "{$item->descn2}({$item->descn3})" : $item->descn2;
                }
                return ['v' => (string)$item->idopcion, 't' => $texto, 'g' => $item->descn1];
            });

        $habitoOptions = DB::table('cat_preguntas')->where('idpregunta', 45)->select('idopcion as v', 'descn1 as t')->get();
        $formaVidaOptions = DB::table('cat_preguntas')->where('idpregunta', 12)->select('idopcion as v', 'descn1 as t')->get();

        $expresionFloresOptions = DB::table('cat_preguntas')->where('idpregunta', 49)->select('idopcion as v', 'descn1 as t')->get();
        $expresionIndividuosOptions = DB::table('cat_preguntas')->where('idpregunta', 50)->select('idopcion as v', 'descn1 as t')->get();
        $expresionPoblacionesOptions = DB::table('cat_preguntas')->where('idpregunta', 51)->select('idopcion as v', 'descn1 as t')->get();
        $polinizacionOptions = DB::table('cat_preguntas')->whereIn('idpregunta', [48, 53])->select('idopcion as v', 'descn1 as t')->get();

        $estrategiaTroficaOptions = DB::table('cat_estrategiatrofica')->get()->map(function($item) {
            $arr = (array)$item;
            return ['v' => (string)array_values($arr)[0], 't' => (string)array_values($arr)[1]];
        });


        $sanitizarLista = function($campo) {
            if (empty($campo)) return [];
            $val = $campo;
            while (is_string($val) && (str_starts_with(trim($val), '[') || str_starts_with(trim($val), '{') || str_starts_with(trim($val), '"'))) {
                $dec = json_decode($val, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $val = $dec;
                } else {
                    break;
                }
            }
            if (is_array($val)) {
                $res = [];
                array_walk_recursive($val, function($item) use (&$res) {
                    $itemL = trim(stripslashes(str_replace(['"', '[', ']', '\\'], '', (string)$item)));
                    if (!empty($itemL)) $res[] = $itemL;
                });
                return array_values(array_unique($res));
            }
            $limpio = trim(stripslashes(str_replace(['"', '[', ']', '\\'], '', (string)$val)));
            return !empty($limpio) ? array_map('trim', explode(',', $limpio)) : [];
        };

        $alimentacionS = DB::table('caracteristicasespecie')->where('especieId', $id)->where('idpregunta', 9)->pluck('idopcion')->map(fn($v)=>(string)$v)->toArray();
        $habitoPlantaS = DB::table('caracteristicasespecie')->where('especieId', $id)->where('idpregunta', 45)->pluck('idopcion')->map(fn($v)=>(string)$v)->toArray();
        $formaVidaS    = DB::table('caracteristicasespecie')->where('especieId', $id)->where('idpregunta', 12)->pluck('idopcion')->map(fn($v)=>(string)$v)->toArray();

        $expresionFloresS      = DB::table('caracteristicasespecie')->where('especieId', $id)->where('idpregunta', 49)->pluck('idopcion')->map(fn($v)=>(string)$v)->toArray();
        $expresionIndividuosS  = DB::table('caracteristicasespecie')->where('especieId', $id)->where('idpregunta', 50)->pluck('idopcion')->map(fn($v)=>(string)$v)->toArray();
        $expresionPoblacionesS = DB::table('caracteristicasespecie')->where('especieId', $id)->where('idpregunta', 51)->pluck('idopcion')->map(fn($v)=>(string)$v)->toArray();
        $polinizacionS         = DB::table('caracteristicasespecie')->where('especieId', $id)->whereIn('idpregunta', [48, 53])->pluck('idopcion')->map(fn($v)=>(string)$v)->toArray();



        $dispersionTipoS = DB::table('caracteristicasespecie')->where('especieId', $id)->where('idpregunta', 15)->pluck('idopcion')->map(fn($v)=>(string)$v)->toArray();
        $dispersionEstructuraS = DB::table('caracteristicasespecie')->where('especieId', $id)->where('idpregunta', 16)->pluck('idopcion')->map(fn($v)=>(string)$v)->toArray();



        $estrategiaTroficaS = [];
        if ($historia && !empty($historia->estrategiaTrofica)) {
            $estrategiaTroficaS[] = (string)$historia->estrategiaTrofica;
        } elseif ($historia && !empty($historia->descripcionEstrofica)) {
            $estrategiaTroficaS[] = (string)$historia->descripcionEstrofica;
        }


        $caracFrutoOptions = DB::table('cat_caracfruto')->get()->map(function($item) {
            $arr = (array)$item;
            return [
                'v' => (string)array_values($arr)[0],
                't' => (string)array_values($arr)[1]
            ];
        });

        $numToMes = [
            1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr',
            5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
        ];
        $mesToNum = [
            'ene' => 1, 'enero' => 1, 'feb' => 2, 'febrero' => 2, 'mar' => 3, 'marzo' => 3,
            'abr' => 4, 'abril' => 4, 'may' => 5, 'mayo' => 5, 'jun' => 6, 'junio' => 6,
            'jul' => 7, 'julio' => 7, 'ago' => 8, 'agosto' => 8, 'sep' => 9, 'septiembre' => 9,
            'oct' => 10, 'octubre' => 10, 'nov' => 11, 'noviembre' => 11, 'dic' => 12, 'diciembre' => 12
        ];


        $procesarMesesParaVista = function($valorBD) use ($numToMes) {
            if (empty($valorBD)) return [];

            $elementos = array_map('trim', explode(',', $valorBD));
            $mesesRes = [];

            foreach ($elementos as $el) {
                if (is_numeric($el)) {
                    $num = (int)$el;
                    if (isset($numToMes[$num])) {
                        $mesesRes[] = $numToMes[$num];
                    }
                } else {
                    $mesesRes[] = ucfirst(strtolower($el));
                }
            }
            return $mesesRes;
        };

        $generarRangoMeses = function($mInicio, $mFin) use ($numToMes, $mesToNum) {
            if (empty($mInicio)) return [];
            $inicio = is_numeric($mInicio) ? (int)$mInicio : ($mesToNum[strtolower(trim($mInicio))] ?? null);
            $fin    = is_numeric($mFin) ? (int)$mFin : ($mesToNum[strtolower(trim($mFin))] ?? $inicio);

            if (!$inicio) return [];
            if (!$fin) $fin = $inicio;

            $resultado = [];
            if ($inicio <= $fin) {
                for ($i = $inicio; $i <= $fin; $i++) {
                    if (isset($numToMes[$i])) $resultado[] = $numToMes[$i];
                }
            } else {
                for ($i = $inicio; $i <= 12; $i++) {
                    if (isset($numToMes[$i])) $resultado[] = $numToMes[$i];
                }
                for ($i = 1; $i <= $fin; $i++) {
                    if (isset($numToMes[$i])) $resultado[] = $numToMes[$i];
                }
            }
            return $resultado;
        };

        $florMesesArray = ($reproVeg) ? $procesarMesesParaVista($reproVeg->mesInicio) : [];
        $fructiMesesArray = ($reproVeg) ? $procesarMesesParaVista($reproVeg->mesInicialFructi) : [];

        $especie = [
            'id'        => $id,
            'especieId' => $id,
            'bloquearAmbiente' => $bloquearAmbiente,
            'tipoAmbiente' => $habitat->tipoAmbiente ?? '',
            'taxon'     => trim(($t['genero'] ?? '') . ' ' . ($t['especie'] ?? '') . ' ' . ($t['infraespecie'] ?? '')),
            'Reino'     => $t['reino'] ?? '',
            'Divisionphylum' => $t['divisionphylum'] ?? '',
            'Clase'     => $t['clase'] ?? '',
            'Orden'     => $t['orden'] ?? '',
            'Familia'   => $t['familia'] ?? '',
            'Genero'    => $t['genero'] ?? '',
            'Especie_epiteto' => $t['especie'] ?? '',
            'Nombreinfra'     => $t['infraespecie'] ?? '',
            'Categinfra'      => $t['categinfra'] ?? '',
            'EstatusTaxon'    => $t['estatus'] ?? '',
            'AutorTaxon'      => $t['autor'] ?? '',
            'IdCAT'           => $t['IdCAT'] ?? $t['idcat'] ?? '',
            'Nom'             => $t['Nom'] ?? $t['nom'] ?? '',
            'resumenEspecie'          => $getData('resumenEspecie'),
            'infoAddNombreCientifico' => $getData('infoAddNombreCientifico'),
            'descEspecie'             => $getData('descEspecie'),
            'especiesSmilares'        => $getData('especiesSmilares'),
            'descripcionOrigen'       => $getData('descripcionOrigen'),
            'toxicidad'               => $getData('toxicidad'),
            'siNoToxicidad'           => (string)($t['siNoToxicidad'] ?? $t['sinotoxicidad'] ?? '0'),
            'riesgoUICN' => $datosCatalogo->Iucn ?? $getData('infoUICN'),
            'infoUICN'   => $getData('infoUICN'),
            'cites'      => $datosCatalogo->Cites ?? $getData('infoCITES'),
            'infoCITES'  => $getData('infoCITES'),
            'nom059'     => $nom059Processed,
            'origen'     => ($t['origen'] ?? '') ? explode(', ', $t['origen']) : [],
            'paises_seleccionados'     => $paisesS,
            'estados_seleccionados'    => $edosS,
            'municipios_seleccionados' => $munsS,
            'suelo_tipo'            => $getOpNombres(6, 'descn1'),
            'habitats_antropicos'   => $getOpNombres(1, 'descn1'),
            'vegetacion_secundaria' => $getOpNombres(2, 'descn1'),
            'clima_tipo'            => $getOpNombres(4, 'descn2'),
            'geoforma_tipo'         => $getOpNombres(7, 'descn1'),
            'ecorregiones_marinas_ids' => $getOpNombres(44, 'descn1'),
            'ecorregiones_terrestres' => $ecorregionesTerrestresS,
            'ecosistemas'           => $ecosistemasS,
            'tipo_vegetacion_a'     => $tipoVegetacionS,
            'suelo_info' => DB::table('observacionescarac')->where('especieId', $id)->where('idpregunta', 6)->value('infoadicional') ?? '',
            'clima_info' => DB::table('observacionescarac')->where('especieId', $id)->where('idpregunta', 4)->value('infoadicional') ?? '',
            'geoforma_info' => DB::table('observacionescarac')->where('especieId', $id)->where('idpregunta', 7)->value('infoadicional') ?? '',
            'especies_asociadas_info' => DB::table('observacionescarac')->where('especieId', $id)->where('idpregunta', 2)->value('infoadicional') ?? '',
            'vegetacion_info_adicional_a' => DB::table('observacionescarac')->where('especieId', $id)->where('idpregunta', 3)->value('infoadicional') ?? '',
            'habitatAgropecuario' => $habitat->habitatAgropecuario ?? '',
            'zonaUrbana' => $habitat->zonaUrbana ?? '',
            'VegetacionSecundaria' => $habitat->VegetacionSecundaria ?? '',
            'intervaloaltitudinalinicial' => $habitat->intervaloaltitudinalinicial ?? '',
            'intervaloaltitudinalfinal' => $habitat->intervaloaltitudinalfinal ?? '',
            'infoAddintervaloaltitudinal' => $getData('infoAddintervaloaltitudinal'),
            'temperaturainicial' => $habitat->temperaturainicial ?? '',
            'temperaturafinal' => $habitat->temperaturafinal ?? '',
            'infoaddtemperatura' => $habitat->infoaddtemperatura ?? '',
            'precipitacioninicial' => $habitat->precipitacioninicial ?? '',
            'precipitacionfinal' => $habitat->precipitacionfinal ?? '',
            'infoaddprecipitacion' => $habitat->infoaddprecipitacion ?? '',
            'humedadinicial' => $habitat->humedadinicial ?? '',
            'humedadfinal' => $habitat->humedadfinal ?? '',
            'infoaddhumedad' => $habitat->infoaddhumedad ?? '',
            'habitat_marino_vertical'  => ($habitat && $habitat->vertical) ? explode(', ', $habitat->vertical) : [],
            'habitat_marino_horizontal' => ($habitat && $habitat->horizontal) ? explode(', ', $habitat->horizontal) : [],
            'habitat_marino_infoAddVH' => $habitat->infoAddVH ?? '',
            'habitat_marino_especiesAsociadas' => $habitat->especiesAsociadas ?? '',
            'habitat_marino_disturbiosAntropicos' => $habitat->disturbiosAntropicos ?? 'NO',
            'habitat_marino_infoAddDisturbiosAntropicos'=> $habitat->infoAddDisturbiosAntropicos ?? '',
            'interbatimetricoinicial' => $habitat->interbatimetricoinicial ?? '',
            'interbatimetricofinal'   => $habitat->interbatimetricofinal ?? '',
            'interbatimetricopromedio' => $habitat->interbatimetricopromedio ?? '',
            'infoaddinterbatimetrico' => $habitat->infoaddinterbatimetrico ?? '',
            'amplitudmareasinicial'   => $habitat->amplitudmareasinicial ?? '',
            'amplitudmareasfinal'     => $habitat->amplitudmareasfinal ?? '',
            'amplitudmareaspromedio'   => $habitat->amplitudmareaspromedio ?? '',
            'infoaddamplitudmareas'   => $habitat->infoaddamplitudmareas ?? '',
            'salinidadinicial' => $habitat->salinidadinicial ?? '', 'salinidadfinal' => $habitat->salinidadfinal ?? '', 'salinidadpromedio' => $habitat->salinidadpromedio ?? '', 'unidadsalinidad' => $habitat->unidadsalinidad ?? '',
            'oxigenoinicial' => $habitat->oxigenoinicial ?? '', 'oxigenofinal' => $habitat->oxigenofinal ?? '', 'oxigenopromedio' => $habitat->oxigenopromedio ?? '',
            'phinicial' => $habitat->phinicial ?? '', 'phfinal' => $habitat->phfinal ?? '', 'phpromedio' => $habitat->phpromedio ?? '',
            'temeperaturainicial' => $habitat->temeperaturainicial ?? '', 'temeperaturafinal' => $habitat->temeperaturafinal ?? '', 'temeperaturapromedio' => $habitat->temeperaturapromedio ?? '',
            'corrientes' => $habitat->corrientes ?? '',
            'infoaddcaracagua' => $habitat->infoaddcaracagua ?? '',
            'ecorregiones_info_adicional' => $habitat->infoAddEcorregion ?? '',
            'nombres_comunes' => array_merge($nombresCatalogo, $nombresLocalesLimpios),
            'sinonimos' => array_merge($sinonimosCatalogo, $sinonimosLocalesLimpios),
            'largoinicialhembras' => $t['largoinicialhembras'] ?? '',
            'largofinalhembras'   => $t['largofinalhembras'] ?? '',
            'largoinicialmachos'  => $t['largoinicialmachos'] ?? '',
            'largofinalmachos'    => $t['largofinalmachos'] ?? '',
            'pesoinicialhembras'  => $t['pesoinicialhembras'] ?? '',
            'pesofinalhembras'    => $t['pesofinalhembras'] ?? '',
            'pesoinicialmachos'   => $t['pesoinicialmachos'] ?? '',
            'pesofinalmachos'     => $t['pesofinalmachos'] ?? '',
            'promedioLargoHembras' => $t['promedioLargoHembras'] ?? '',
            'unidadLargoHembras'   => $t['unidadLargoHembras'] ?? 'mm',
            'promedioLargoMachos'  => $t['promedioLargoMachos'] ?? '',
            'unidadLargoMachos'    => $t['unidadLargoMachos'] ?? 'mm',
            'promedioPesoHembras'  => $t['promedioPesoHembras'] ?? '',
            'unidadPesoHembras'    => $t['unidadPesoHembras'] ?? 'g',
            'promedioPesoMachos'   => $t['promedioPesoMachos'] ?? '',
            'unidadPesoMachos'     => $t['unidadPesoMachos'] ?? 'g',
            'dist_mundial_info'        => $dist['InfoAdicionalPais'] ?? '',
            'info_adicional_estado'    => $dist['InfoAdicionalEdo'] ?? '',
            'info_adicional_municipio' => $dist['infoAdicionalMun'] ?? '',
            'potencial_info'           => $dist['historicaPotencial'] ?? '',
            'siNoPotencial'            => (!empty($dist['historicaPotencial'])) ? '1' : '0',
            'siNoEndemismo'            => ($endemismo['endemicaMexico'] ?? 'NO') === 'SÍ' ? '1' : '0',
            'endemica_a'               => $endemismo['endemicaA'] ?? '',
            'endemismo_info'           => $endemismo['infoAdicionalEndemica'] ?? '',
            'tipoCiclo'                    => $habitat->tipoCiclo ?? '',
            'aspectos'                     => $habitat->aspectos ?? '',
            'uso_habitat'                  => $habitat->uso ?? '',
            'descripcion_reproduccion'     => $descripcionRepro,

            'alimentacion'                 => $alimentacionS,
            'habito_planta'                => $habitoPlantaS,
            'forma_vida_planta'            => ($reinoEspecie === 'Plantae') ? $formaVidaS : [],
            'forma_vida_otros'             => ($reinoEspecie !== 'Animalia' && $reinoEspecie !== 'Plantae') ? $formaVidaS : [],
            'estrategia_trofica'           => $estrategiaTroficaS,

            'expresion_flores'             => $expresionFloresS,
            'expresion_individuos'         => $expresionIndividuosS,
            'expresion_poblaciones'        => $expresionPoblacionesS,
            'tipo_polinizacion'            => $polinizacionS,

            'caracteristicas_conductuales' => $historia->conducta ?? '',
            'periodo_actividad'            => $historia->tipopHabito ?? ($historia->infoaddperiodoactividad ?? ''),
            'hibernacion'                  => ($historia->hibernacion ?? '') === 'Sí' ? 'si' : (($historia->hibernacion ?? '') === 'no' ? 'no' : ''),
            'hibernacion_torpor_ia'        => $historia->infoaddhibernacion ?? '',
            'ambito_hogareno_promedio'     => $historia->ambitoHogareno ?? '',
            'mecanismos_defensa'           => $historia->mecanismosDefensa ?? '',
            'dispersion_dist_min'          => $historia->distanciadispercioninicial ?? '',
            'dispersion_dist_max'          => $historia->distanciadispercionfinal ?? '',
            'organizacion_social'          => $demografia->organizacionSocial ?? '',

            'hay_dimorfismo'               => ($reproAnimal->dimorfismoSexual ?? '') === 'Sí' ? 'si' : (($reproAnimal->dimorfismoSexual ?? '') === 'no' ? 'no' : ''),
            'dimorfismo_ia'                => $reproAnimal->additionalInfoDimorfiasmo ?? '',
            'tipo_fecundacion_animal'      => $reproAnimal->tipoFecundacion ?? '',
            'sistema_repro_animal_ia'      => $reproAnimal->descripcionTipoFec ?? '',
            'estrategia_reproductiva_animal' => $reproAnimal->noEventos ?? '',
            'tiempo_eventos_min'           => $reproAnimal->tiempoentrecriasinicial ?? '',
            'tiempo_eventos_max'           => $reproAnimal->tiempoentrecriasfinal ?? '',
            'edadPrimeraRepro'             => $reproAnimal->edadPrimeraRepro ?? '',
            'duracionVidaRepro'            => $reproAnimal->duracionVidaRepro ?? '',
            'crias_promedio'               => $reproAnimal->noHuevosCrias ?? '',
            'cuidadoParental'             => ($reproAnimal->cuidadoParental ?? '') === 'Sí' ? 'si' : (($reproAnimal->cuidadoParental ?? '') === 'no' ? 'no' : ''),
            'cuidadoParentalPor'         => $reproAnimal->cuidadoParentalPor ?? '',
            'tiempoCuidadoParental'      => $reproAnimal->tiempoCuidadoParental ?? '',

            'aislamiento_temporal_ia'      => $reproVeg->descAislaOrganos ?? '',
            'aislamiento_temporal'             => is_array($reproVeg->aislamientoOrganos ?? null) ? ($reproVeg->aislamientoOrganos[0] ?? '') : (string)($reproVeg->aislamientoOrganos ?? ''),
            'sistemas_reproductivos_asexuales' => $sanitizarLista($reproVeg->sistReproAsexuales ?? null),
            'tipo_fecundacion_plantae'         => $sanitizarLista($reproVeg->fecuandacion ?? null),
            'flor_horario_apertura'        => $reproVeg->aperturaFlor ?? '',
            'flor_longevidad'              => $reproVeg->tiempoFloracion ?? '',
            'floracion_meses'              => $florMesesArray,
            'floracion_ia'                 => $reproVeg->addinfotiempoflora ?? '',
            'cantidad_nectar'              => $reproVeg->cantidadnectarinicial ?? '',
            'cantidad_polen'               => $reproVeg->cantidadpolen ?? '',
            'fructificacion_meses'         => $fructiMesesArray,
            'fructificacion_ia'            => $reproVeg->addinfotiempofructi ?? '',
            'frutos_min'                   => $reproVeg->nofrutosinicial ?? '',
            'frutos_max'                   => $reproVeg->nofrutosfinal ?? '',
            'descCaracFruto'               => $reproVeg->descCaracFruto ?? '',
            'estrategia_reproductiva_planta' => $reproVeg->noEventos ?? '',
            'estrategia_reproductiva_planta_ia' => $reproVeg->descNoEventos ?? '',
            'semillas_num_min'             => $reproVeg->nosemillasinicial ?? '',
            'semillas_num_max'             => $reproVeg->nosemillasfinal ?? '',
            'semillas_num_promedio'     => $reproVeg->promNoSemillas ?? '',
            'semillas_latencia'           => ($reproVeg && strtolower($reproVeg->caracLatente ?? '') === 'sí') ? 'si' : 'no',
            'semillas_toxicidad'          => ($reproVeg && strtolower($reproVeg->caracToxica ?? '') === 'sí') ? 'si' : 'no',
            'semillas_caracteristicas_ia' => ($reproVeg && $reproVeg->infoAddSemillas !== 'EMPTY') ? ($reproVeg->infoAddSemillas ?? '') : '',
            'germinacion_min'              => $reproVeg->germinacioninicial ?? '',
            'germinacion_max'              => $reproVeg->germinacionfinal ?? '',
            'germinacion_ia'               => $reproVeg->infoaddgerminacion ?? '',
            'supervivencia_min'            => $reproVeg->plantulasinicial ?? '',
            'supervivencia_max'            => $reproVeg->plantulasfinal ?? '',
            'supervivencia_ia'             => $reproVeg->infoaddplantulas ?? '',

            'flor_horario_apertura' => $reproVeg->aperturaFlor ?? '',
            'flor_horario_apertura' => $reproVeg->aperturaFlor ?? '',
            'flor_longevidad'       => $reproVeg->tiempoFloracion ?? '',
            'floracion_meses'       => $florMesesArray,
            'fructificacion_meses'  => $fructiMesesArray,

            'cantidad_nectar_min'   => $reproVeg->cantidadnectarinicial ?? '',
            'cantidad_nectar_max'   => $reproVeg->cantidadnectarfinal ?? '',
            'cantidad_nectar_promedio' => $reproVeg->promedioCantidadNectar ?? '',
            'cantidad_nectar_ia'    => ($reproVeg && $reproVeg->addinfocantidadnectar !== 'EMPTY') ? ($reproVeg->addinfocantidadnectar ?? '') : '',
            'cantidad_polen'        => ($reproVeg && $reproVeg->cantidadpolen !== 'EMPTY') ? ($reproVeg->cantidadpolen ?? '') : '',


            'frutos_min'             => $reproVeg->nofrutosinicial ?? '',
            'frutos_max'             => $reproVeg->nofrutosfinal ?? '',
            'frutos_promedio'        => $reproVeg->promNoFrutos ?? '',
            'frutos_ia'              => ($reproVeg && $reproVeg->descCaracFruto !== 'EMPTY') ? ($reproVeg->descCaracFruto ?? '') : '',
            'fruto_caracteristicas'  => (string)($reproVeg->caracFruto ?? ''),

            'semillas_latencia'        => ($reproVeg && strtolower($reproVeg->caracLatente ?? '') === 'sí') ? 'si' : 'no',
            'semillas_toxicidad'       => ($reproVeg && strtolower($reproVeg->caracToxica ?? '') === 'sí') ? 'si' : 'no',
            'semillas_caracteristicas_ia' => ($reproVeg && $reproVeg->infoAddSemillas !== 'EMPTY') ? ($reproVeg->infoAddSemillas ?? '') : '',

            'semillas_num_min'            => $reproVeg->nosemillasinicial ?? '',
            'semillas_num_max'            => $reproVeg->nosemillasfinal ?? '',
            'semillas_tam_min'          => $reproVeg->tamanoInicioSemilla ?? '',
            'semillas_tam_max'          => $reproVeg->tamamoFinalSemilla ?? '',
            'semillas_tam_promedio'     => $reproVeg->promTamanoSemillas ?? '',
            'semillas_tam_ia'           => $reproVeg->tamanioSemilla ?? '',
            'germinacion_promedio'      => $reproVeg->porcentajeGerminacion ?? '',

            'supervivencia_min'         => $reproVeg->plantulasinicial ?? '',
            'supervivencia_max'         => $reproVeg->plantulasfinal ?? '',
            'supervivencia_promedio'    => $reproVeg->porcentajeSuprePlantulas ?? '',

            'dispersion_tipo'          => $dispersionTipoS,
            'dispersion_estructura'    => $dispersionEstructuraS,

            'dispersion_dist_min'      => $historia->distanciadispercioninicial ?? '',
            'dispersion_dist_max'      => $historia->distanciadispercionfinal ?? '',
            'dispersion_dist_promedio' => (!empty($historia->distanciadispercioninicial) && !empty($historia->distanciadispercionfinal))
                                            ? number_format(($historia->distanciadispercioninicial + $historia->distanciadispercionfinal) / 2, 2, '.', '')
                                            : '',


        ];

        return view('form', [
            'especie' => (object)$especie,
            'ecorregiones' => DB::table('ecorregion')->get(),
            'estados' => DB::table('estado')->orderBy('nombreEstado')->get(),
            'paises'  => DB::table('pais')->orderBy('nombrepais')->get(),
            'tiposSuelo' => DB::table('cat_preguntas')->where('idpregunta', 6)->get(),
            'habitatsAntropicos' => DB::table('cat_preguntas')->where('idpregunta', 1)->get(),
            'vegSecundaria' => DB::table('cat_preguntas')->where('idpregunta', 2)->get(),
            'clima' => DB::table('cat_preguntas')->where('idpregunta', 4)->get(),
            'geoforma' => DB::table('cat_preguntas')->where('idpregunta', 7)->get(),
            'vegetacionCat' => DB::table('vegetacion')->orderBy('descripcionVegetacion')->get(),
            'ecosistemasCat' => DB::table('ecosistema')->get(),
            'ecorregionesMarinasCat' => DB::table('cat_preguntas')->where('idpregunta', 44)->get(),
            'alimentacionOptions' => $alimentacionOptions,
            'habitoOptions' => $habitoOptions,
            'formaVidaOptions' => $formaVidaOptions,
            'estrategiaTroficaOptions' => $estrategiaTroficaOptions,
            'expresionFloresOptions' => $expresionFloresOptions,
            'expresionIndividuosOptions' => $expresionIndividuosOptions,
            'expresionPoblacionesOptions' => $expresionPoblacionesOptions,
            'polinizacionOptions' => $polinizacionOptions,
            'caracFrutoOptions' => $caracFrutoOptions,
            'tipoDispersionOptions' => DB::table('cat_preguntas')->where('idpregunta', 15)->get()->map(fn($item) => ['v' => (string)$item->idopcion, 't' => (string)$item->descn1])->values()->all(),
            'estructuraDispersionOptions' => DB::table('cat_preguntas')->where('idpregunta', 16)->get()->map(fn($item) => ['v' => (string)$item->idopcion, 't' => (string)$item->descn1])->values()->all(),
        ]);
    }

    public function verificarExistencia($idCAT) {
        $fichas = DB::table('taxon')->where('IdCAT', $idCAT)->get();
        return response()->json([
            'count' => $fichas->count(),
            'fichas' => $fichas
        ]);
    }

    public function obtenerMunicipiosMultiple(Request $request) {
        $estados = $request->input('estados', []);
        if (empty($estados)) return response()->json([]);

        return DB::table('municipio')
            ->whereIn('nombreEstado', $estados)
            ->select('municipioId as v', 'nombreMunicipio as t', 'nombreEstado as g')
            ->orderBy('g')
            ->orderBy('t')
            ->get();
    }
}
