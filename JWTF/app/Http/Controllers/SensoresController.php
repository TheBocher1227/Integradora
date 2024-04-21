<?php

namespace App\Http\Controllers;


use App\Events\NewSensores;
use App\Events\NuevaReparacion;
use App\Events\obtenervalores;
use Illuminate\Http\Request;
use App\Events\Event;
use Carbon\Carbon;
use Exception;
use App\Models\Sensores;
use App\Models\RelacionEstaciones;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Value;

class SensoresController extends Controller
{

    protected $colecction = 'data-sensores';
    

    public function store(Request $request)
    {
        try {
           
            $sensores = $request->all(); 
    
            foreach ($sensores as $sensorData) {
                $log = new Sensores();
                $log->nombre = $sensorData['nombre'];
                $log->unidad = $sensorData['unidad'];
                $log->clave = $sensorData['clave'];
                $log->descripcion = $sensorData['descripcion'];
                $log->isf = $sensorData['isf']; 

                event(new NewSensores($log));
                $log->save();
            }
    
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
    
        return response()->json(['success' => true, 'message' => 'Relación creada correctamente.'],200);
    }
    public function obtenerRegistrosPorEstacion($id)
    {
        $datos = Sensores::all();
        $registrosMasRecientesPorSensor = [];
    
        foreach ($datos as $dato) {
            if (!empty($dato->isf) && is_array($dato->isf)) {
                foreach ($dato->isf as $isf) {
                    if (isset($isf['id_estacion']) && $isf['id_estacion'] == $id && !empty($isf['Data']) && is_array($isf['Data'])) {
                        foreach ($isf['Data'] as $data) {
                            $fechaHora = $data['fecha'] . ' ' . $data['hora'];
                            $fechaHoraCarbon = Carbon::createFromFormat('d-m-Y H:i:s', $fechaHora);
                            $tipoSensor = $data['tipo_sensor'];
    
                            // Verificar si ya existe un registro para este tipo de sensor y si la fecha es más reciente
                            if (!isset($registrosMasRecientesPorSensor[$tipoSensor]) || $fechaHoraCarbon->gt($registrosMasRecientesPorSensor[$tipoSensor]['data']['fechaHora'])) {
                                $registrosMasRecientesPorSensor[$tipoSensor] = [
                                    'nombre' => $dato->nombre,
                                    'unidad' => $dato->unidad,
                                    'clave' => $dato->clave,
                                    'descripcion' => $dato->descripcion,
                                    'data' => [
                                        'uuid' => $data['uuid'],
                                        'tipo_sensor' => $tipoSensor,
                                        'numero_serie' => $data['numero_serie'],
                                        'data' => $data['data'],
                                        'fecha' => $data['fecha'],
                                        'hora' => $data['hora'],
                                        'fechaHora' => $fechaHoraCarbon->toDateTimeString()
                                    ]
                                ];
                            }
                        }
                    }
                }
            }
        }
    
        // Preparar la respuesta final extrayendo solo los valores de los registros más recientes por tipo de sensor
        $registrosFinales = array_values($registrosMasRecientesPorSensor);
    
        return response()->json(['success' => true, 'data' => $registrosFinales]);
    }





public function guardarValor(Request $request)
{
    $value = $request->input('value') == 1 ? 1 : 0; 
    $valor = new Value(); 
    $valor->value = $value; 
    $valor->save(); 
    event(New obtenervalores($valor));
    return response()->json(['success' => true, 'message' => 'Valor guardado correctamente.']);
    
}



public function obtenervalores()
{
    $ultimoValor = Value::latest()->first(); 

    if ($ultimoValor) { 
        $resultado = $ultimoValor->value == 1; 
    } else {
        $resultado = false;
    }
    return response()->json(['success' => $resultado]);
}






}
