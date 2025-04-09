<?php

namespace App\Http\Controllers;

use App\Models\categoria_cliente;
use App\Models\categoria_producto;
use App\Models\categoria_servicio;
use App\Models\cliente;
use App\Models\etiquetas_cita;
use App\Models\marca;
use App\Models\servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{

    function getCategories(Request $request)
    {
        $text2Search = $request->get('q');

        $salon_id = Auth::user()->salon->id;

        $results = categoria_producto::where('salon_id', $salon_id)
            ->where(function ($query) use ($text2Search) {
                $query->where('name', 'like', "%{$text2Search}%");
            })
            ->orderBy('name', 'asc')
            ->take(10)
            ->get();
            
        return response()->json($results);
    }
    function getTags(Request $request)
    {
        $text2Search = $request->get('q');

        $salon_id = Auth::user()->salon->id;

        $results = etiquetas_cita::where('salon_id', $salon_id)
            ->where(function ($query) use ($text2Search) {
                $query->where('name', 'like', "%{$text2Search}%");
            })
            ->orderBy('name', 'asc')
            ->take(10)
            ->get();
            
        return response()->json($results);
    }
    function getBrands(Request $request)
    {
        $text2Search = $request->get('q');

        $salon_id = Auth::user()->salon->id;

        $results = marca::where('salon_id', $salon_id)
            ->where(function ($query) use ($text2Search) {
                $query->where('rfc', 'like', "%{$text2Search}%");
            })
            ->orderBy('name', 'asc')
            ->get();
            
        return response()->json($results);
    }
    function getCategoriesS(Request $request)
    {
        $text2Search = $request->get('q');

        $salon_id = Auth::user()->salon->id;

        $results = categoria_servicio::where('salon_id', $salon_id)
            ->where(function ($query) use ($text2Search) {
                $query->where('name', 'like', "%{$text2Search}%");
            })
            ->orderBy('name', 'asc')
            ->take(10)
            ->get();

        return response()->json($results);
    }
    function getCategoriesCustomer(Request $request)
    {
        $salon_id = Auth::user()->salon->id;
        $text2Search = $request->get('q');

        $results = categoria_cliente::where('salon_id', $salon_id)
            ->where('name', 'like', "%{$text2Search}%")
            ->orderBy('name', 'asc')
            ->take(10)
            ->get();

        return response()->json($results);
    }

    function getCustomers(Request $request)
    {
        $text2Search = $request->get('q');
        $salon_id = Auth::user()->salon->id;

        $results = cliente::where('salon_id', $salon_id)
            ->where(function ($query) use ($text2Search) {
                $query->where('first_name', 'like', "%{$text2Search}%")
                    ->orWhere('last_name', 'like', "%{$text2Search}%")
                    ->orWhere('phone', 'like', "%{$text2Search}%");
            })
            ->select('id', 'first_name', 'last_name', 'phone', DB::raw("concat(first_name, ' ', last_name) as text"))
            ->orderBy('first_name', 'asc')
            ->get();


        return response()->json($results);
    }
    function getServices(Request $request)
    {
        $text2Search = $request->get('q');
        $salon_id = Auth::user()->salon->id;

        $results = servicio::where('salon_id', $salon_id)
            ->where('name', 'like', "%{$text2Search}%")
            ->where('name','!=','Servicio eliminado')
            ->orderBy('name', 'asc')
            ->get();
        return response()->json($results);
    }
}

