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
                ->where('taxon', 'LIKE', "%{$query}%")
                ->select('taxon', 'IdNombre', 'IdNombreRel', 'Reino', 'Clase', 'Orden', 'Familia', 'Genero', 'Categinfra', 'AutorTaxon', 'EstatusTaxon', 'Especie_epiteto', 'Nombreinfra', 'Divisionphylum', 'IdCAT','Cites','Iucn','Nom')
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
    $taxon = DB::table('taxon')->where('especieId', $id)->first();
    if (!$taxon) return redirect()->back()->with('error', 'No se encontró la ficha.');

    $datosCatalogo = DB::connection('mysql_catalogo')
        ->table('_TransformaTablaNombre')
        ->where('IdCAT', $taxon->IdCAT)
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
            ->groupBy('NomComun.NomComun', 'NomComun.Lengua')
            ->get();
        foreach ($ncRelacionales as $nc) {
            $nombresCatalogo[] = ['nombre' => trim($nc->nombre), 'lengua' => trim($nc->lengua), 'bibliografia' => '', 'editable' => false];
        }
    }
    $nombresLocalesRaw = DB::table('nombrecomun')->where('especieId', $id)->get();
    $nombresLocalesLimpios = [];
    foreach ($nombresLocalesRaw as $n) {
        $nombreL = strtolower(trim($n->nombre));
        $lenguaL = strtolower(trim($n->lenguaje));
        $textoBibliografo = trim(strip_tags($n->citanomcomun));
        $tieneInfoManual = !empty($textoBibliografo) && $textoBibliografo !== '&nbsp;';
        $esCopiaDelCatalogo = false;
        foreach ($nombresCatalogo as $cat) {
            if (strtolower(trim($cat['nombre'])) === $nombreL && strtolower(trim($cat['lengua'])) === $lenguaL) {
                if (!$tieneInfoManual) $esCopiaDelCatalogo = true;
                break;
            }
        }
        if (!$esCopiaDelCatalogo) {
            $nombresLocalesLimpios[] = ['nombre' => trim($n->nombre), 'lengua' => trim($n->lenguaje), 'bibliografia' => $n->citanomcomun, 'editable' => true];
        }
    }

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
        $sinonimoL = strtolower(trim($s->nombreSimple));
        $autorL = strtolower(trim($s->autoridad));
        $esDuplicado = false;
        foreach ($sinonimosCatalogo as $cat) {
        if (strtolower(trim($cat['sinonimo'])) === $sinonimoL &&
            strtolower(trim($cat['autor'])) === $autorL) {
            $esDuplicado = true;
            break;
        }
    }
        if (!$esDuplicado) {
            $sinonimosLocalesLimpios[] = ['sinonimo' => trim($s->nombreSimple), 'autor' => $s->autoridad === 'EMPTY' ? '' : $s->autoridad, 'anio' => $s->anio, 'editable' => true];
        }
    }

    $legislacionRows = DB::table('legislacion')->where('especieId', $id)->get();
    $legisProcessed = [
        'riesgoUICN' => '', 'infoUICN' => '', 'cites' => '', 'infoCITES' => '',
        'nom059' => ['2001' => ['categoria' => '', 'info' => ''], '2010' => ['categoria' => '', 'info' => ''], '2019' => ['categoria' => '', 'info' => '']]
    ];
    foreach ($legislacionRows as $row) {
        if ($row->nombreLegislacion == 'UICN') {
            $legisProcessed['riesgoUICN'] = $row->estatusLegalProteccion;
            $legisProcessed['infoUICN'] = $row->infoAdicional === 'EMPTY' ? '' : $row->infoAdicional;
        } elseif ($row->nombreLegislacion == 'CITES') {
            $legisProcessed['cites'] = $row->estatusLegalProteccion;
            $legisProcessed['infoCITES'] = $row->infoAdicional === 'EMPTY' ? '' : $row->infoAdicional;
        } elseif (str_contains($row->nombreLegislacion, 'NOM-059')) {
            $year = substr($row->nombreLegislacion, -4);
            if (isset($legisProcessed['nom059'][$year])) {
                $legisProcessed['nom059'][$year]['categoria'] = $row->estatusLegalProteccion;
                $legisProcessed['nom059'][$year]['info'] = $row->infoAdicional === 'EMPTY' ? '' : $row->infoAdicional;
            }
        }
    }

    $distribucion = DB::table('distribucion')->where('especieId', $id)->first();
    $endemismo = DB::table('endemica')->where('especieId', $id)->first();
    $paises_seleccionados = [];
    $estados_seleccionados = [];
    $municipios_seleccionados = [];
    if ($distribucion) {
        $distId = $distribucion->distribucionid ?? $distribucion->distribucionId ?? $distribucion->id;
        if ($distId) {
            $paises_seleccionados = DB::table('reldistribucionpais')
                ->join('pais', 'reldistribucionpais.paisId', '=', 'pais.paisId')
                ->where('distribucionid', $distId)
                ->pluck('pais.nombrepais')
                ->toArray();

            $estados_seleccionados = DB::table('reldistribucionestado')
                ->join('estado', 'reldistribucionestado.estadoId', '=', 'estado.estadoId')
                ->where('distribucionid', $distId)->pluck('estado.nombreEstado')->toArray();

            $municipios_seleccionados = DB::table('reldistribucionmunicipio')
                ->join('municipio', 'reldistribucionmunicipio.municipioId', '=', 'municipio.municipioId')
                ->where('distribucionid', $distId)->pluck('municipio.nombreMunicipio')->toArray();
        }
    }

    $habitat = DB::table('habitat')->where('especieId', $id)->first();
    $ecorregiones = DB::table('ecorregion')->get();
    $ecosistemasCat = DB::table('ecosistema')->get();
    $ecosistemasSeleccionados = [];
    $ecorregionesSeleccionadas = [];


    $marinasCat = DB::table('cat_preguntas')->where('idpregunta', 44)->get();
    $marinasSeleccionadas = DB::table('caracteristicasespecie')
        ->where('especieId', $id)
        ->where('idpregunta', 44)
        ->pluck('idopcion')
        ->toArray();


    if ($habitat) {
        $ecorregionesSeleccionadas = DB::table('relecorregionhabitat')
            ->where('habitatId', $habitat->habitatId)
            ->pluck('ecorregionId')
            ->toArray();

        $ecosistemasSeleccionados = DB::table('relecosistemahabitat')
            ->where('habitatId', $habitat->habitatId)
            ->pluck('ecosistemaid')
            ->toArray();
    }

    $getOp = function($pId) use ($id) {
        return DB::table('caracteristicasespecie')->where('especieId', $id)->where('idpregunta', $pId)->pluck('idopcion')->toArray();
    };
    $getObs = function($pId) use ($id) {
        return DB::table('observacionescarac')->where('especieId', $id)->where('idpregunta', $pId)->value('infoadicional');
    };

    $suelosSeleccionados = $getOp(6);
    $infoSuelo = $getObs(6);
    $habitatsAntropicosSeleccionados = $getOp(1);
    $vegSecundariaSeleccionada = $getOp(2);
    $climaSeleccionado = $getOp(4);
    $infoClima = $getObs(4);
    $geoformaSeleccionada = $getOp(7);
    $infoGeoforma = $getObs(7);
    $infoEspeciesAsociadas = $getObs(2);
    $vegetacionSeleccionada = $getOp(3);
    $infoVegetacion = $getObs(3);
    $tiposSuelo = DB::table('cat_preguntas')->where('idpregunta', 6)->get();
    $habitatsAntropicos = DB::table('cat_preguntas')->where('idpregunta', 1)->get();
    $vegSecundaria = DB::table('cat_preguntas')->where('idpregunta', 2)->get();
    $clima = DB::table('cat_preguntas')->where('idpregunta', 4)->get();
    $geoforma = DB::table('cat_preguntas')->where('idpregunta', 7)->get();
    $vegetacionCat = DB::table('vegetacion')->orderBy('descripcionVegetacion')->get();

    $tipo = $habitat->tipoAmbiente ?? '';

    if ($tipo == 'terrestre') $tipo = 'Ambiente terrestre';
    if ($tipo == 'acuático') $tipo = 'Ambiente acuático';
    if ($tipo == 'terrestre-acuático') $tipo = 'Ambiente terrestre-acuático';

    $especie = [
        'id' => $taxon->especieId,
        'especieId' => $taxon->especieId,
        'taxon' => trim($taxon->genero . ' ' . $taxon->especie . ' ' . ($taxon->infraespecie ?? '')),
        'Reino' => $datosCatalogo->Reino ?? $taxon->reino,
        'Divisionphylum' => $datosCatalogo->Divisionphylum ?? $taxon->divisionphylum,
        'Clase' => $datosCatalogo->Clase ?? $taxon->clase,
        'Orden' => $datosCatalogo->Orden ?? $taxon->orden,
        'Familia' => $datosCatalogo->Familia ?? $taxon->familia,
        'Genero' => $datosCatalogo->Genero ?? $taxon->genero,
        'Especie_epiteto' => $datosCatalogo->Especie_epiteto ?? $taxon->especie,
        'Nombreinfra' => $datosCatalogo->Nombreinfra ?? $taxon->infraespecie,
        'Categinfra' => $datosCatalogo->Categinfra ?? $taxon->categinfra,
        'EstatusTaxon' => $datosCatalogo->EstatusTaxon ?? $taxon->estatus,
        'AutorTaxon' => $datosCatalogo->AutorTaxon ?? $taxon->autor,
        'IdCAT' => $taxon->IdCAT,
        'Nom' => $datosCatalogo->Nom ?? '',
        'riesgoUICN' => $datosCatalogo->Iucn ?? $legisProcessed['riesgoUICN'],
        'cites' => $datosCatalogo->Cites ?? $legisProcessed['cites'],
        'resumenEspecie' => $taxon->resumenEspecie,
        'infoAddNombreCientifico' => $taxon->infoAddNombreCientifico,
        'infoUICN' => $taxon->infoUICN,
        'infoCITES' => $taxon->infoCITES,
        'descEspecie' => $taxon->descEspecie,
        'especiesSmilares' => $taxon->especiesSmilares,
        'descripcionOrigen' => $taxon->descripcionOrigen,
        'origen' => $taxon->origen ? explode(', ', $taxon->origen) : [],
        'largoinicialmachos' => $taxon->largoinicialmachos,
        'largofinalmachos' => $taxon->largofinalmachos,
        'largoinicialhembras' => $taxon->largoinicialhembras,
        'largofinalhembras' => $taxon->largofinalhembras,
        'pesoinicialmachos' => $taxon->pesoinicialmachos,
        'pesofinalmachos' => $taxon->pesofinalmachos,
        'pesoinicialhembras' => $taxon->pesoinicialhembras,
        'pesofinalhembras' => $taxon->pesofinalhembras,
        'siNoToxicidad' => (string)($taxon->siNoToxicidad ?? '0'),
        'toxicidad' => $taxon->toxicidad,
        'promedioLargoMachos' => $taxon->promedioLargoMachos,
        'unidadLargoMachos' => $taxon->unidadLargoMachos,
        'promedioLargoHembras' => $taxon->promedioLargoHembras,
        'unidadLargoHembras' => $taxon->unidadLargoHembras,
        'promedioPesoMachos' => $taxon->promedioPesoMachos,
        'unidadPesoMachos' => $taxon->unidadPesoMachos,
        'promedioPesoHembras' => $taxon->promedioPesoHembras,
        'unidadPesoHembras' => $taxon->unidadPesoHembras,
        'nombres_comunes' => array_merge($nombresCatalogo, $nombresLocalesLimpios),
        'sinonimos' => array_merge($sinonimosCatalogo, $sinonimosLocalesLimpios),
        'infoUICN' => $taxon->infoUICN,
        'infoCITES' => $taxon->infoCITES,
        'nom059' => $legisProcessed['nom059'],

        'paises_seleccionados' => $paises_seleccionados,
        'dist_mundial_info'    => $distribucion->InfoAdicionalPais ?? '',
        'estados_seleccionados'    => $estados_seleccionados,
        'municipios_seleccionados' => $municipios_seleccionados,
        'info_adicional_estado'    => $distribucion->InfoAdicionalEdo ?? '',
        'info_adicional_municipio' => $distribucion->infoAdicionalMun ?? '',
        'potencial_info'       => $distribucion->historicaPotencial ?? '',
        'siNoPotencial'        => !empty($distribucion->historicaPotencial) ? '1' : '0',
        'siNoEndemismo' => ($endemismo && $endemismo->endemicaMexico == 'SÍ') ? '1' : '0',
        'endemica_a'    => $endemismo->endemicaA ?? '',
        'endemismo_info' => $endemismo->infoAdicionalEndemica ?? '',

        'tipoAmbiente' => $tipo,
        'habitatAgropecuario' => $habitat->habitatAgropecuario ?? '',
        'zonaUrbana' => $habitat->zonaUrbana ?? '',
        'VegetacionSecundaria' => $habitat->VegetacionSecundaria ?? '',
        'intervaloaltitudinalinicial' => $habitat->intervaloaltitudinalinicial ?? '',
        'intervaloaltitudinalfinal' => $habitat->intervaloaltitudinalfinal ?? '',
        'infoAddintervaloaltitudinal' => $habitat->infoAddintervaloaltitudinal ?? '',
        'temperaturainicial' => $habitat->temperaturainicial ?? '',
        'temperaturafinal' => $habitat->temperaturafinal ?? '',
        'infoaddtemperatura' => $habitat->infoaddtemperatura ?? '',
        'precipitacioninicial' => $habitat->precipitacioninicial ?? '',
        'precipitacionfinal' => $habitat->precipitacionfinal ?? '',
        'infoaddprecipitacion' => $habitat->infoaddprecipitacion ?? '',
        'humedadinicial' => $habitat->humedadinicial ?? '',
        'humedadfinal' => $habitat->humedadfinal ?? '',
        'infoaddhumedad' => $habitat->infoaddhumedad ?? '',
        'suelo_tipo' => $suelosSeleccionados,
        'suelo_info' => $infoSuelo ?? '',
        'habitats_antropicos' => $habitatsAntropicosSeleccionados,
        'vegetacion_secundaria' => $vegSecundariaSeleccionada,
        'clima_tipo' => $climaSeleccionado,
        'clima_info' => $infoClima ?? '',
        'geoforma_tipo' => $geoformaSeleccionada,
        'geoforma_info' => $infoGeoforma ?? '',
        'especies_asociadas_info' => $infoEspeciesAsociadas ?? '',
        'tipo_vegetacion_a' => $vegetacionSeleccionada,
        'vegetacion_info_adicional_a' => $infoVegetacion ?? '',
        'ecorregiones_terrestres' => $ecorregionesSeleccionadas,
        'ecosistemas' => $ecosistemasSeleccionados,
        'ecorregiones_marinas_ids' => $marinasSeleccionadas,
        'habitat_marino_vertical'  => $habitat->vertical ?? '',
        'habitat_marino_horizontal' => $habitat->horizontal ?? '',
        'habitat_marino_infoAddVH' => $habitat->infoAddVH ?? '',
        'habitat_marino_especiesAsociadas' => $habitat->especiesAsociadas ?? '',
        'habitat_marino_disturbiosAntropicos' => $habitat->disturbiosAntropicos ?? 'NO',
        'habitat_marino_infoAddDisturbiosAntropicos'=> $habitat->infoAddDisturbiosAntropicos ?? '',
        'interbatimetricoinicial' => $habitat->interbatimetricoinicial ?? '',
        'interbatimetricofinal'   => $habitat->interbatimetricofinal ?? '',
        'infoaddinterbatimetrico' => $habitat->infoaddinterbatimetrico ?? '',
        'amplitudmareasinicial'   => $habitat->amplitudmareasinicial ?? '',
        'amplitudmareasfinal'     => $habitat->amplitudmareasfinal ?? '',
        'infoaddamplitudmareas'   => $habitat->infoaddamplitudmareas ?? '',
        'salinidadinicial'        => $habitat->salinidadinicial ?? '',
        'salinidadfinal'          => $habitat->salinidadfinal ?? '',
        'unidadsalinidad'         => $habitat->unidadsalinidad ?? '',
        'oxigenoinicial'          => $habitat->oxigenoinicial ?? '',
        'oxigenofinal'            => $habitat->oxigenofinal ?? '',
        'phinicial'               => $habitat->phinicial ?? '',
        'phfinal'                 => $habitat->phfinal ?? '',
        'temeperaturainicial'     => $habitat->temeperaturainicial ?? '',
        'temeperaturafinal'       => $habitat->temeperaturafinal ?? '',
        'corrientes'              => $habitat->corrientes ?? '',
        'infoaddcaracagua'        => $habitat->infoaddcaracagua ?? '',
        'interbatimetricopromedio' => $habitat->interbatimetricopromedio ?? '',
        'amplitudmareaspromedio'   => $habitat->amplitudmareaspromedio ?? '',
        'salinidadpromedio'        => $habitat->salinidadpromedio ?? '',
        'oxigenopromedio'          => $habitat->oxigenopromedio ?? '',
        'phpromedio'               => $habitat->phpromedio ?? '',
        'temeperaturapromedio'     => $habitat->temeperaturapromedio ?? '',
    ];

    return view('form', [
        'especie' => (object)$especie,
        'ecorregiones' => $ecorregiones,
        'estados' => DB::table('estado')->orderBy('nombreEstado')->get(),
        'paises'  => DB::table('pais')->orderBy('nombrepais')->get(),
        'tiposSuelo' => $tiposSuelo,
        'habitatsAntropicos' => $habitatsAntropicos,
        'vegSecundaria' => $vegSecundaria,
        'clima' => $clima,
        'geoforma' => $geoforma,
        'vegetacionCat' => $vegetacionCat,
        'ecosistemasCat' => $ecosistemasCat,
        'ecorregionesMarinasCat' => $marinasCat,
    ]);
}

}
