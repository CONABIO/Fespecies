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
                        'reino'                => $f['Reino'],
                        'divisionphylum'       => $f['Divisionphylum'],
                        'clase'                => $f['Clase'],
                        'orden'                => $f['Orden'],
                        'familia'              => $f['Familia'],
                        'genero'               => $f['Genero'],
                        'especie'              => $f['Especie_epiteto'],
                        'infraespecie'         => $f['Nombreinfra'],
                        'categinfra'           => $f['Categinfra'],
                        'estatus'              => $f['EstatusTaxon'],
                        'autor'                => $f['AutorTaxon'],
                        'IdCAT'                => $f['IdCAT'],
                        'especiesSmilares'    => $f['especiesSmilares'] ?? null,
                        'descripcionOrigen'    => $f['descripcionOrigen'] ?? null,
                        'resumenEspecie'       => $f['resumenEspecie'] ?? null,
                        'infoAddNombreCientifico' => $f['infoAddNombreCientifico'] ?? null,
                        'infoUICN' => $f['infoUICN'] ?? null,
                        'infoCITES' => $f['infoCITES'] ?? null,
                        'descEspecie'          => $f['descEspecie'] ?? null,
                        'origen'               => is_array($f['origen']) ? implode(', ', $f['origen']) : $f['origen'],
                        'largoinicialhembras'  => $f['largoinicialhembras'] ?? null,
                        'largofinalhembras'    => $f['largofinalhembras'] ?? null,
                        'largoinicialmachos'   => $f['largoinicialmachos'] ?? null,
                        'largofinalmachos'     => $f['largofinalmachos'] ?? null,
                        'pesoinicialhembras'   => $f['pesoinicialhembras'] ?? null,
                        'pesofinalhembras'     => $f['pesofinalhembras'] ?? null,
                        'pesoinicialmachos'    => $f['pesoinicialmachos'] ?? null,
                        'pesofinalmachos'      => $f['pesofinalmachos'] ?? null,
                        'toxicidad'            => $f['toxicidad'] ?? null,
                        'siNoToxicidad'        => $f['siNoToxicidad'] ?? null,
                        'tipoficha'            => 'Prioritaria',
                        'promedioLargoHembras' => $f['promedioLargoHembras'] ?? null,
                        'unidadLargoHembras'   => $f['unidadLargoHembras'] ?? null,
                        'promedioLargoMachos'  => $f['promedioLargoMachos'] ?? null,
                        'unidadLargoMachos'    => $f['unidadLargoMachos'] ?? null,
                        'promedioPesoHembras'  => $f['promedioPesoHembras'] ?? null,
                        'unidadPesoHembras'    => $f['unidadPesoHembras'] ?? null,
                        'promedioPesoMachos'   => $f['promedioPesoMachos'] ?? null,
                        'unidadPesoMachos'     => $f['unidadPesoMachos'] ?? null,
                    ]
                );

                DB::table('nombrecomun')->where('especieId', $especieId)->delete();
                if (!empty($f['nombres_comunes'])) {
                    foreach ($f['nombres_comunes'] as $n) {
                        if (isset($n['editable']) && $n['editable'] == true) {
                            if (empty($n['nombre'])) continue;
                            DB::table('nombrecomun')->insert([
                                'especieId'    => $especieId,
                                'nombre'       => trim($n['nombre']),
                                'lenguaje'     => !empty($n['lengua']) ? trim($n['lengua']) : 'EMPTY',
                                'citanomcomun' => !empty($n['bibliografia']) ? $n['bibliografia'] : '<br>'
                            ]);
                        }
                    }
                }

                DB::table('sinonimo')->where('especieId', $especieId)->delete();
                if (!empty($f['sinonimos'])) {
                    foreach ($f['sinonimos'] as $s) {
                        if (isset($s['editable']) && $s['editable'] == true) {
                            if (empty($s['sinonimo'])) continue;
                            DB::table('sinonimo')->insert([
                                'especieId'     => $especieId,
                                'nombreSimple'  => trim($s['sinonimo']),
                                'autoridad'     => $s['autor'] ?? 'EMPTY',
                                'anio'          => $s['anio'] ?? null
                            ]);
                        }
                    }
                }

                DB::table('legislacion')->where('especieId', $especieId)->delete();
                if (!empty($f['riesgoUICN'])) {
                    DB::table('legislacion')->insert(['especieId' => $especieId, 'nombreLegislacion' => 'UICN', 'estatusLegalProteccion' => $f['riesgoUICN'], 'infoAdicional' => $f['infoUICN'] ?? 'EMPTY']);
                }
                if (!empty($f['cites'])) {
                    DB::table('legislacion')->insert(['especieId' => $especieId, 'nombreLegislacion' => 'CITES', 'estatusLegalProteccion' => $f['cites'], 'infoAdicional' => $f['infoCITES'] ?? 'EMPTY']);
                }
                if (isset($f['nom059']) && is_array($f['nom059'])) {
                    foreach ($f['nom059'] as $year => $data) {
                        if (!empty($data['categoria'])) {
                            DB::table('legislacion')->insert([
                                'especieId' => $especieId,
                                'nombreLegislacion' => "NOM-059-SEMARNAT-{$year}",
                                'estatusLegalProteccion' => $data['categoria'],
                                'infoAdicional' => (!empty($data['info']) && $data['info'] !== '<p>&nbsp;</p>') ? $data['info'] : 'EMPTY'
                            ]);
                        }
                    }
                }
            }

            if ($seccion == 2) {
                $paisesTxt = !empty($f['paises_seleccionados']) ? implode(', ', (array)$f['paises_seleccionados']) : null;
                $edosTxt = !empty($f['estados_seleccionados']) ? implode(', ', (array)$f['estados_seleccionados']) : 'N/A';
                $munsTxt = !empty($f['municipios_seleccionados']) ? implode(', ', (array)$f['municipios_seleccionados']) : 'N/A';

                DB::table('distribucion')->updateOrInsert(
                    ['especieId' => $especieId],
                    [
                        'distribucion'        => $paisesTxt,
                        'InfoAdicionalPais'   => $f['dist_mundial_info'] ?? null,
                        'InfoAdicionalEdo'    => $f['info_adicional_estado'] ?? null,
                        'infoAdicionalMun'    => $f['info_adicional_municipio'] ?? null,
                        'historicaPotencial'  => $f['potencial_info'] ?? null,
                        'infoadicionalmexedo' => "Edos: " . $edosTxt . " | Muns: " . $munsTxt
                    ]
                );

                $distRow = DB::table('distribucion')->where('especieId', $especieId)->first();
                $distId = $distRow->distribucionid ?? $distRow->distribucionId ?? $distRow->id ?? null;

                if ($distId) {
                    DB::table('reldistribucionpais')->where('distribucionid', $distId)->delete();
                    if (!empty($f['paises_seleccionados'])) {
                        foreach ((array)$f['paises_seleccionados'] as $pVal) {
                            $pId = DB::table('pais')->where('nombrepais', $pVal)->value('paisId');
                            if ($pId) DB::table('reldistribucionpais')->insert(['distribucionid' => $distId, 'paisId' => $pId, 'tipopais' => 0]);
                        }
                    }

                    DB::table('reldistribucionestado')->where('distribucionid', $distId)->delete();
                    if (!empty($f['estados_seleccionados'])) {
                        foreach ((array)$f['estados_seleccionados'] as $edoNombre) {
                            $eId = DB::table('estado')->where('nombreEstado', $edoNombre)->value('estadoId');
                            if ($eId) DB::table('reldistribucionestado')->insert(['distribucionid' => $distId, 'estadoId' => $eId]);
                        }
                    }

                    DB::table('reldistribucionmunicipio')->where('distribucionid', $distId)->delete();
                    if (!empty($f['municipios_seleccionados'])) {
                        foreach ((array)$f['municipios_seleccionados'] as $munNombre) {
                            $mId = DB::table('municipio')->where('nombreMunicipio', $munNombre)->value('municipioId');
                            if ($mId) DB::table('reldistribucionmunicipio')->insert(['distribucionid' => $distId, 'municipioId' => $mId]);
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
                DB::table('observacionescarac')->updateOrInsert(
                    ['especieId' => $especieId, 'idpregunta' => 6],
                    ['infoadicional' => $f['suelo_info'] ?? 'EMPTY']
                );
                DB::table('caracteristicasespecie')->where('especieId', $especieId)->where('idpregunta', 6)->delete();
                if (!empty($f['suelo_tipo'])) {
                    foreach ($f['suelo_tipo'] as $idOpcion) {
                        DB::table('caracteristicasespecie')->insert(['especieId' => $especieId, 'idpregunta' => 6, 'idopcion' => $idOpcion]);
                    }
                }

                DB::table('caracteristicasespecie')->where('especieId', $especieId)->where('idpregunta', 1)->delete();
                if (!empty($f['habitats_antropicos'])) {
                    foreach ($f['habitats_antropicos'] as $idOpcion) {
                        DB::table('caracteristicasespecie')->insert(['especieId' => $especieId, 'idpregunta' => 1, 'idopcion' => $idOpcion]);
                    }
                }

                DB::table('caracteristicasespecie')->where('especieId', $especieId)->where('idpregunta', 2)->delete();
                if (!empty($f['vegetacion_secundaria'])) {
                    foreach ($f['vegetacion_secundaria'] as $idOpcion) {
                        DB::table('caracteristicasespecie')->insert(['especieId' => $especieId, 'idpregunta' => 2, 'idopcion' => $idOpcion]);
                    }
                }

                DB::table('observacionescarac')->updateOrInsert(
                    ['especieId' => $especieId, 'idpregunta' => 4],
                    ['infoadicional' => $f['clima_info'] ?? 'EMPTY']
                );
                DB::table('caracteristicasespecie')->where('especieId', $especieId)->where('idpregunta', 4)->delete();
                if (!empty($f['clima_tipo'])) {
                    foreach ($f['clima_tipo'] as $idOpcion) {
                        DB::table('caracteristicasespecie')->insert(['especieId' => $especieId, 'idpregunta' => 4, 'idopcion' => $idOpcion]);
                    }
                }

                DB::table('observacionescarac')->updateOrInsert(
                    ['especieId' => $especieId, 'idpregunta' => 7],
                    ['infoadicional' => $f['geoforma_info'] ?? 'EMPTY']
                );
                DB::table('caracteristicasespecie')->where('especieId', $especieId)->where('idpregunta', 7)->delete();
                if (!empty($f['geoforma_tipo'])) {
                    foreach ($f['geoforma_tipo'] as $idOpcion) {
                        DB::table('caracteristicasespecie')->insert(['especieId' => $especieId, 'idpregunta' => 7, 'idopcion' => $idOpcion]);
                    }
                }

                DB::table('observacionescarac')->updateOrInsert(
                    ['especieId' => $especieId, 'idpregunta' => 2],
                    ['infoadicional' => $f['especies_asociadas_info'] ?? 'EMPTY']
                );

                DB::table('observacionescarac')->updateOrInsert(
                    ['especieId' => $especieId, 'idpregunta' => 3],
                    ['infoadicional' => $f['vegetacion_info_adicional_a'] ?? 'EMPTY']
                );
                DB::table('caracteristicasespecie')->where('especieId', $especieId)->where('idpregunta', 3)->delete();
                if (!empty($f['tipo_vegetacion_a'])) {
                    foreach ($f['tipo_vegetacion_a'] as $idOpcion) {
                        DB::table('caracteristicasespecie')->insert(['especieId' => $especieId, 'idpregunta' => 3, 'idopcion' => $idOpcion]);
                    }
                }

                DB::table('habitat')->updateOrInsert(
                    ['especieId' => $especieId],
                    [
                        'tipoAmbiente'         => $f['tipoAmbiente'],
                        'habitatAgropecuario'  => $f['habitatAgropecuario'],
                        'zonaUrbana'           => $f['zonaUrbana'],
                        'VegetacionSecundaria' => $f['VegetacionSecundaria'],
                        'intervaloaltitudinalinicial'  => $f['intervaloaltitudinalinicial'],
                        'intervaloaltitudinalfinal'    => $f['intervaloaltitudinalfinal'],
                        'infoAddintervaloaltitudinal'  => $f['infoAddintervaloaltitudinal'],
                        'temperaturainicial'    => $f['temperaturainicial'],
                        'temperaturafinal'      => $f['temperaturafinal'],
                        'infoaddtemperatura'    => $f['infoaddtemperatura'],
                        'precipitacioninicial'  => $f['precipitacioninicial'],
                        'precipitacionfinal'         => $f['precipitacionfinal'],
                        'infoaddprecipitacion'         => $f['infoaddprecipitacion'],
                        'humedadinicial'         => $f['humedadinicial'],
                        'humedadfinal'         => $f['humedadfinal'],
                        'infoaddhumedad'         => $f['infoaddhumedad'],
                        'vertical'                   => $f['habitat_marino_vertical'],
                        'horizontal'                 => $f['habitat_marino_horizontal'],
                        'infoAddVH'                  => $f['habitat_marino_infoAddVH'],
                        'especiesAsociadas'          => $f['habitat_marino_especiesAsociadas'],
                        'disturbiosAntropicos'       => $f['habitat_marino_disturbiosAntropicos'],
                        'infoAddDisturbiosAntropicos'=> $f['habitat_marino_infoAddDisturbiosAntropicos'],
                        'interbatimetricoinicial'         => $f['interbatimetricoinicial'] ?? null,
                        'interbatimetricofinal'           => $f['interbatimetricofinal'] ?? null,
                        'infoaddinterbatimetrico'         => $f['infoaddinterbatimetrico'] ?? null,
                        'amplitudmareasinicial'           => $f['amplitudmareasinicial'] ?? null,
                        'amplitudmareasfinal'             => $f['amplitudmareasfinal'] ?? null,
                        'infoaddamplitudmareas'           => $f['infoaddamplitudmareas'] ?? null,
                        'salinidadinicial'                => $f['salinidadinicial'] ?? null,
                        'salinidadfinal'                  => $f['salinidadfinal'] ?? null,
                        'unidadsalinidad'                 => $f['unidadsalinidad'] ?? null,
                        'oxigenoinicial'                  => $f['oxigenoinicial'] ?? null,
                        'oxigenofinal'                    => $f['oxigenofinal'] ?? null,
                        'phinicial'                       => $f['phinicial'] ?? null,
                        'phfinal'                         => $f['phfinal'] ?? null,
                        'temeperaturainicial'             => $f['temeperaturainicial'] ?? null,
                        'temeperaturafinal'               => $f['temeperaturafinal'] ?? null,
                        'corrientes'                      => $f['corrientes'] ?? null,
                        'infoaddcaracagua'                => $f['infoaddcaracagua'] ?? null,
                        'interbatimetricopromedio' => $f['interbatimetricopromedio'] ?? null,
                        'amplitudmareaspromedio'   => $f['amplitudmareaspromedio'] ?? null,
                        'salinidadpromedio'        => $f['salinidadpromedio'] ?? null,
                        'oxigenopromedio'          => $f['oxigenopromedio'] ?? null,
                        'phpromedio'               => $f['phpromedio'] ?? null,
                        'temeperaturapromedio'     => $f['temeperaturapromedio'] ?? null,
                    ]
                );


                $habitatId = DB::table('habitat')->where('especieId', $especieId)->value('habitatId');

                if ($habitatId) {
                    DB::table('relecorregionhabitat')->where('habitatId', $habitatId)->delete();

                    if (!empty($f['ecorregiones_terrestres'])) {
                        foreach ($f['ecorregiones_terrestres'] as $ecoId) {
                            DB::table('relecorregionhabitat')->insert([
                                'habitatId'    => $habitatId,
                                'ecorregionId' => $ecoId
                            ]);
                        }
                    }


                    DB::table('relecosistemahabitat')->where('habitatId', $habitatId)->delete();
                    if (!empty($f['ecosistemas'])) {
                        foreach ($f['ecosistemas'] as $ecoSId) {
                            DB::table('relecosistemahabitat')->insert([
                                'habitatId'    => $habitatId,
                                'ecosistemaid' => $ecoSId
                            ]);
                        }
                    }
                }

                DB::table('caracteristicasespecie')->where('especieId', $especieId)->where('idpregunta', 44)->delete();
                if (!empty($f['ecorregiones_marinas_ids'])) {
                    foreach ($f['ecorregiones_marinas_ids'] as $idOpcion) {
                        DB::table('caracteristicasespecie')->insert([
                            'especieId' => $especieId,
                            'idpregunta' => 44,
                            'idopcion' => $idOpcion
                        ]);
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
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
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
            ->groupBy('NomComun.NomComun', 'NomComun.Lengua')
            ->get();
        foreach ($ncRelacionales as $nc) {
            $nombresCatalogo[] = ['nombre' => trim($nc->nombre), 'lengua' => trim($nc->lengua), 'bibliografia' => '', 'editable' => false];
        }
    }

    $nombresLocalesLimpios = DB::table('nombrecomun')->where('especieId', $id)->get()->map(function($n) {
        return [
            'nombre' => trim($n->nombre),
            'lengua' => trim($n->lenguaje),
            'bibliografia' => ($n->citanomcomun === 'EMPTY' ? '' : $n->citanomcomun),
            'editable' => true
        ];
    })->toArray();

    $nom059Processed = [
        '2001' => ['categoria' => '', 'info' => ''],
        '2010' => ['categoria' => '', 'info' => ''],
        '2019' => ['categoria' => '', 'info' => '']
    ];
    $legisRows = DB::table('legislacion')->where('especieId', $id)->get();
    foreach ($legisRows as $row) {
        if (str_contains($row->nombreLegislacion, 'NOM-059')) {
            $year = substr($row->nombreLegislacion, -4);
            if (isset($nom059Processed[$year])) {
                $nom059Processed[$year]['categoria'] = $row->estatusLegalProteccion;
                $nom059Processed[$year]['info'] = ($row->infoAdicional === 'EMPTY' ? '' : $row->infoAdicional);
            }
        }
    }

    $dist = (array)DB::table('distribucion')->where('especieId', $id)->first();
    $endemismo = (array)DB::table('endemica')->where('especieId', $id)->first();
    $habitat = (array)DB::table('habitat')->where('especieId', $id)->first();

   $especie = [
    'id'        => $id,
    'especieId' => $id,
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
    'largoinicialmachos'   => $t['largoinicialmachos'] ?? '',
    'largofinalmachos'     => $t['largofinalmachos'] ?? '',
    'largoinicialhembras'  => $t['largoinicialhembras'] ?? '',
    'largofinalhembras'    => $t['largofinalhembras'] ?? '',
    'pesoinicialmachos'    => $t['pesoinicialmachos'] ?? '',
    'pesofinalmachos'      => $t['pesofinalmachos'] ?? '',
    'pesoinicialhembras'   => $t['pesoinicialhembras'] ?? '',
    'pesofinalhembras'     => $t['pesofinalhembras'] ?? '',
    'promedioLargoMachos'  => $t['promedioLargoMachos'] ?? '',
    'unidadLargoMachos'    => $t['unidadLargoMachos'] ?? 'mm',
    'promedioLargoHembras' => $t['promedioLargoHembras'] ?? '',
    'unidadLargoHembras'   => $t['unidadLargoHembras'] ?? 'mm',
    'promedioPesoMachos'   => $t['promedioPesoMachos'] ?? '',
    'unidadPesoMachos'     => $t['unidadPesoMachos'] ?? 'g',
    'promedioPesoHembras'  => $t['promedioPesoHembras'] ?? '',
    'unidadPesoHembras'    => $t['unidadPesoHembras'] ?? 'g',
    'origen' => ($t['origen'] ?? '') ? explode(', ', $t['origen']) : [],
    'nombres_comunes' => array_merge($nombresCatalogo, $nombresLocalesLimpios),
    'sinonimos' => DB::table('sinonimo')->where('especieId', $id)->get()->map(function($s){
        return ['sinonimo' => $s->nombreSimple, 'autor' => $s->autoridad, 'anio' => $s->anio, 'editable' => true];
    })->toArray(),
    'dist_mundial_info'      => $getData('InfoAdicionalPais'),
    'info_adicional_estado'  => $getData('InfoAdicionalEdo'),
    'siNoEndemismo'          => ($endemismo && ($endemismo['endemicaMexico'] ?? $endemismo['endemicamexico'] ?? '') == 'SÍ') ? '1' : '0',
    'endemismo_info'         => ($endemismo['infoAdicionalEndemica'] ?? $endemismo['infoadicionalendemica'] ?? ''),
    'tipoAmbiente'           => $habitat['tipoAmbiente'] ?? $habitat['tipoambiente'] ?? '',
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

}
