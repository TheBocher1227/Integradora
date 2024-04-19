<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use App\Models\Sensores;

class SensoresController extends Controller
{
  

    public function store(Request $request,$nombre,$unidad,$clave,$descripcion,$isf)
    {
        try
        {
            $log = new Sensores();
            $log->nombre = $nombre;
            $log->unidad=$unidad;
            $log->clave=$clave;
            $log->descripcion=$descripcion;
            $log->isf=$isf;
            $log->save();
            return true;
        }

        catch(Exception $e)
        {
        return false;
        }
    }
}
