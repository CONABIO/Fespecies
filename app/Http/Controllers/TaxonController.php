<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TaxonController extends Controller
{
     public function obtenerDatos(Request $request)
    {
        $rolUsuario = Auth::user()->rol;
        $query = DB::table('taxon');

        if ($rolUsuario !== 'admin') {
            $query->where('tipoficha', $rolUsuario);
        }

        if ($request->filled('q')) {
            $q = $request->query('q');
            $words = explode(' ', trim($q));

            $query->where(function($sub) use ($words) {
                foreach ($words as $word) {
                    if (empty($word)) continue;
                    $sub->where(function($q_inner) use ($word) {
                        $q_inner->where('especie', 'like', '%' . $word . '%')
                                ->orWhere('genero', 'like', '%' . $word . '%')
                                ->orWhere('familia', 'like', '%' . $word . '%')
                                ->orWhere('infraespecie', 'like', '%' . $word . '%')
                                ->orWhere('reino', 'like', '%' . $word . '%');
                    });
                }
            });
        }

        if ($request->filled('f_id')) $query->where('especieId', $request->f_id);
        if ($request->filled('f_tipo')) $query->where('tipoficha', 'like', '%' . $request->f_tipo . '%');
        if ($request->filled('f_familia')) $query->where('familia', 'like', '%' . $request->f_familia . '%');
        if ($request->filled('f_genero')) $query->where('genero', 'like', '%' . $request->f_genero . '%');
        if ($request->filled('f_especie')) $query->where('especie', 'like', '%' . $request->f_especie . '%');
        if ($request->filled('f_infraespecie')) $query->where('infraespecie', 'like', '%' . $request->f_infraespecie . '%');

        $taxones = $query->paginate(15)->withQueryString();
        return view('dashboard', compact('taxones'));
    }

    public function buscarEnTaxon(Request $request)
    {
        $q = $request->query('q');
        if (!$q) return response()->json([]);

        $rolUsuario = Auth::user()->rol;
        $query = DB::table('taxon');

        if ($rolUsuario !== 'admin') {
            $query->where('tipoficha', $rolUsuario);
        }

        $words = explode(' ', trim($q));

        $resultados = $query->where(function($sub) use ($words) {
                foreach ($words as $word) {
                    if (empty($word)) continue;
                    $sub->where(function($q_inner) use ($word) {
                        $q_inner->where('especie', 'like', '%' . $word . '%')
                                ->orWhere('genero', 'like', '%' . $word . '%')
                                ->orWhere('familia', 'like', '%' . $word . '%')
                                ->orWhere('infraespecie', 'like', '%' . $word . '%')
                                ->orWhere('reino', 'like', '%' . $word . '%');
                    });
                }
            })
            ->select(
                DB::raw("TRIM(CONCAT(TRIM(genero), ' ', TRIM(especie), ' ', IFNULL(TRIM(infraespecie), ''))) as taxon"),
                DB::raw("TRIM(autor) as AutorTaxon"),
                DB::raw("MAX(especieId) as especieId")
            )
            ->groupBy('taxon', 'AutorTaxon')
            ->orderBy('taxon', 'asc')
            ->limit(15)
            ->get();

        return response()->json($resultados);
    }
}
