<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormController extends Controller{

   public function index(Request $request) {
    $tempId = $request->query('tempId');
    $paises = DB::table('pais')->orderBy('nombrepais')->get();
    $estados = DB::table('estado')
                ->orderBy('nombreEstado')
                ->get();

    return view('form', compact('tempId', 'paises', 'estados'));
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
                ->where('Nombre.IdNombre', $item->IdNombre)
                ->select('NomComun.NomComun as nombre', 'NomComun.Lengua as lengua')
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
                'descEspecie'          => $f['descEspecie'] ?? null,
                'origen'               => $f['origen'] ?? null,
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
            DB::table('legislacion')->insert([
                'especieId' => $especieId,
                'nombreLegislacion' => 'UICN',
                'estatusLegalProteccion' => $f['riesgoUICN'],
                'infoAdicional' => $f['infoUICN'] ?? 'EMPTY'
            ]);
        }

        if (!empty($f['cites'])) {
            DB::table('legislacion')->insert([
                'especieId' => $especieId,
                'nombreLegislacion' => 'CITES',
                'estatusLegalProteccion' => $f['cites'],
                'infoAdicional' => $f['infoCITES'] ?? 'EMPTY'
            ]);
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

        DB::table('distribucion')->updateOrInsert(
            ['especieId' => $especieId],
            [
                'distribucion'        => !empty($f['paises_seleccionados']) ? implode(', ', $f['paises_seleccionados']) : null,
                'InfoAdicionalPais'   => $f['dist_mundial_info'] ?? null,
                'InfoAdicionalEdo'    => $f['info_adicional_estado'] ?? null,
                'infoAdicionalMun'    => $f['info_adicional_municipio'] ?? null,
                'historicaPotencial'  => $f['potencial_info'] ?? null,
                'infoadicionalmexedo' => "Edo: " . ($f['dist_historica_estado'] ?? 'N/A') . " | Mun: " . ($f['dist_historica_municipio'] ?? 'N/A')
            ]
        );

        DB::table('endemica')->updateOrInsert(
            ['especieId' => $especieId],
            [
                'endemicaMexico'        => ($f['siNoEndemismo'] == '1') ? 'SÍ' : 'NO',
                'endemicaA'             => $f['endemica_a'] ?? null,
                'infoAdicionalEndemica' => $f['endemismo_info'] ?? null
            ]
        );

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
            ->where('Nombre.IdNombre', $datosCatalogo->IdNombre)
            ->select('NomComun.NomComun as nombre', 'NomComun.Lengua as lengua')
            ->get();

        foreach ($ncRelacionales as $nc) {
            $nombresCatalogo[] = [
                'nombre' => trim($nc->nombre),
                'lengua' => trim($nc->lengua),
                'bibliografia' => '',
                'editable' => false
            ];
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
                if (!$tieneInfoManual) {
                    $esCopiaDelCatalogo = true;
                }
                break;
            }
        }

        if (!$esCopiaDelCatalogo) {
            $nombresLocalesLimpios[] = [
                'nombre' => trim($n->nombre),
                'lengua' => trim($n->lenguaje),
                'bibliografia' => $n->citanomcomun,
                'editable' => true
            ];
        }
    }
    $sinonimosCatalogo = [];
    if ($datosCatalogo) {
        $sinonimosCatalogo = DB::connection('mysql_catalogo')
            ->table('_TransformaTablaNombre')
            ->select('Taxon as sinonimo', 'AutorTaxon as autor')
            ->where('IdNombreRel', '=', $datosCatalogo->IdNombre)
            ->get()
            ->map(function($s) {
                return [
                    'sinonimo' => trim($s->sinonimo),
                    'autor' => trim($s->autor),
                    'anio' => null,
                    'editable' => false
                ];
            })->toArray();
    }

    $sinonimosLocalesRaw = DB::table('sinonimo')->where('especieId', $id)->get();
    $sinonimosLocalesLimpios = [];
    foreach ($sinonimosLocalesRaw as $s) {
        $sinonimoL = strtolower(trim($s->nombreSimple));
        $esDuplicado = false;
        foreach ($sinonimosCatalogo as $cat) {
            if (strtolower(trim($cat['sinonimo'])) === $sinonimoL) {
                $esDuplicado = true;
                break;
            }
        }
        if (!$esDuplicado) {
            $sinonimosLocalesLimpios[] = [
                'sinonimo' => trim($s->nombreSimple),
                'autor' => $s->autoridad === 'EMPTY' ? '' : $s->autoridad,
                'anio' => $s->anio,
                'editable' => true
            ];
        }
    }

    $legislacionRows = DB::table('legislacion')->where('especieId', $id)->get();
    $legisProcessed = [
        'riesgoUICN' => '', 'infoUICN' => '', 'cites' => '', 'infoCITES' => '',
        'nom059' => ['2001' => ['categoria' => '', 'info' => ''], '2010' => ['categoria' => '', 'info' => ''], '2019' => ['categoria' => '', 'info' => '']]
    ];

    $distribucion = DB::table('distribucion')->where('especieId', $id)->first();
    $endemismo = DB::table('endemica')->where('especieId', $id)->first();
    $estados = DB::table('estado')->orderBy('nombreEstado')->get();
    $paises = DB::table('pais')->orderBy('nombrepais')->get();

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
        'infoUICN' => $legisProcessed['infoUICN'],
        'infoCITES' => $legisProcessed['infoCITES'],
        'nom059' => $legisProcessed['nom059'],
        'paises_seleccionados' => ($distribucion && $distribucion->distribucion) ? explode(', ', $distribucion->distribucion) : [],
        'dist_mundial_info'    => $distribucion->InfoAdicionalPais ?? '',
        'dist_historica_estado' => $distribucion->InfoAdicionalEdo ?? '',
        'info_adicional_estado' => $distribucion->InfoAdicionalEdo ?? '',
        'dist_historica_municipio' => $distribucion->infoAdicionalMun ?? '',
        'info_adicional_municipio' => $distribucion->infoAdicionalMun ?? '',
        'potencial_info'       => $distribucion->historicaPotencial ?? '',
        'siNoPotencial'        => !empty($distribucion->historicaPotencial) ? '1' : '0',
    ];

    $especie = (object)$especie;
    return view('form', [
        'especie' => (object)$especie,
        'estados' => $estados,
        'paises'  => $paises
    ]);
}

}
