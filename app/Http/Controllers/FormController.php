<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormController extends Controller{

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


        return view('form', compact('tempId', 'paises', 'estados', 'tiposSuelo', 'habitatsAntropicos', 'vegSecundaria','clima', 'geoforma','vegetacionCat','ecorregiones','ecosistemas','ecorregionesMarinasCat'));
    }

    public function obtenerMunicipios($nombreEdo) {
        $municipios = DB::table('municipio')
                        ->where('nombreEstado', $nombreEdo)
                        ->orderBy('nombreMunicipio')
                        ->get();
        return response()->json($municipios);
    }

    public function buscar(Request $request)
{
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

                $municipiosActuales = DB::table('reldistribucionmunicipio')
                    ->where('distribucionid', $distId)
                    ->pluck('municipioId')
                    ->toArray();

                $mInsertar = array_diff($municipiosNuevos, $municipiosActuales);
                $mEliminar = array_diff($municipiosActuales, $municipiosNuevos);

                if (!empty($mEliminar)) {
                    DB::table('reldistribucionmunicipio')
                        ->where('distribucionid', $distId)
                        ->whereIn('municipioId', $mEliminar)
                        ->delete();
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

            $saveMultiSelect(6, $f['suelo_tipo'] ?? []);
            $saveMultiSelect(1, $f['habitats_antropicos'] ?? []);
            $saveMultiSelect(2, $f['vegetacion_secundaria'] ?? []);
            $saveMultiSelect(4, $f['clima_tipo'] ?? [], 'descn2');
            $saveMultiSelect(7, $f['geoforma_tipo'] ?? []);
            $saveMultiSelect(44, $f['ecorregiones_marinas_ids'] ?? []);
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
    $munsS   = $distId ? DB::table('reldistribucionmunicipio')->join('municipio','reldistribucionmunicipio.municipioId','=','municipio.municipioId')->where('distribucionid', $distId)->pluck('nombreMunicipio')->toArray() : [];

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
