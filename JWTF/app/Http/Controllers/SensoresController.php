<?php

namespace App\Http\Controllers;


use App\Events\NuevaReparacion;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use App\Models\Sensores;
use App\Models\RelacionEstaciones;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SensoresController extends Controller
{
    public function store(Request $request)
    {
        try {
            $log = new Sensores();
            $log->nombre = $request->input('nombre');
            $log->unidad = $request->input('unidad');
            $log->clave = $request->input('clave');
            $log->descripcion = $request->input('descripcion');
            $log->isf = $request->input('isf');
            $log->save();

            event(New NuevaReparacion($log));
            return response()->json(['success' => true], 200);
        } catch(Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }

        
    }





    public function obtenerestaciones()
    {
        $usuarioAutenticado = auth()->user()->id; 
        $estaciones = RelacionEstaciones::where('user_id', $usuarioAutenticado)->get();
        return response()->json(["msg" => "Estaciones encontradas correctamente", "data" => $estaciones], 201);
    }

    public function guardarRelacionEstacion(Request $request)
    {
        $usuarioAutenticado = auth()->user()->id;
        $idEstacion = $request->input('id_estacion'); 
    
        $relacion = new RelacionEstaciones();
        $relacion->user_id = $usuarioAutenticado;
        $relacion->estacion_id = $idEstacion;
        $relacion->save();
    
        return response()->json(['success' => true, 'message' => 'Relación creada correctamente.']);
    }



public function obtenerRegistrosPorEstacion($id)
{
    $usuarioAutenticado = auth()->user()->id; 
    $datos = Sensores::all(); 
    Log::info($datos);
    foreach ($datos as $sensor) {
        $idEstacion = $sensor->isf['id_estacion'];
        $relacion = new RelacionEstaciones();
        $relacion->user_id = $usuarioAutenticado;
        $relacion->estacion_id = $idEstacion;
    }
    return response()->json(['success' => true, 'data' => $datos]);
}


}
