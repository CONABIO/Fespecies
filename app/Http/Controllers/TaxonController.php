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

        if ($request->filled('f_id')) {
            $query->where('especieId', $request->f_id);
        }
        if ($request->filled('f_tipo')) {
            $query->where('tipoficha', 'like', '%' . $request->f_tipo . '%');
        }
        if ($request->filled('f_familia')) {
            $query->where('familia', 'like', '%' . $request->f_familia . '%');
        }
        if ($request->filled('f_genero')) {
            $query->where('genero', 'like', '%' . $request->f_genero . '%');
        }
        if ($request->filled('f_especie')) {
            $query->where('especie', 'like', '%' . $request->f_especie . '%');
        }
        if ($request->filled('f_infraespecie')) {
            $query->where('infraespecie', 'like', '%' . $request->f_especie . '%');
        }

        $taxones = $query->paginate(15)->withQueryString();

        return view('dashboard', compact('taxones'));
    }
}
