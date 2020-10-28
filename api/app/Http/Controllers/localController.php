<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Local;
use Illuminate\Support\Facades\DB;

class LocalController extends Controller
{
    public function registrar(REQUEST $resquest)
    {
        $Local = new Local();
        $Local->Nombre = $resquest->Nombre;
        $Local->CodigoEmpresa = (!empty($resquest->CodigoEmpresa) && $resquest->CodigoEmpresa != "undefined")?$resquest->CodigoEmpresa:1;
        $Local->Direccion = $resquest->Direccion;
        $Local->Telefono = $resquest->Telefono;
        $Local->Vigencia = 1;
        $Local->save();
        $dato = [
            "Registro" => "EXITOSO"
        ];
        return response()->json($dato, 200);
    }

    public function actualizar(REQUEST $resquest, $Codigo)
    {
        $Local = Local ::find($Codigo);
        if ($Local != null) {
            $Local->Nombre = $resquest->Nombre;
            //$local->CodigoEmpresa = $resquest->CodigoEmpresa;
            $Local->Direccion = $resquest->Direccion;
            $Local->Telefono = $resquest->Telefono;
            $Local->Vigencia = 1;
            $Local->save();
            $dato = [
                "Modificacion" => "EXITOSO"
            ];
            return response()->json($dato, 200);
        }else{
            $dato = [
                "Modificacion" => "FALLO"
            ];
            return response()->json($dato, 500);
        }
        
    }

    public function Listar()
    {
        $dato = Local::all();
        return response()->json($dato, 200);
    }    

    public function Buscar($Nombre)
    {
        $dato = Local::where('Nombre','LIKE','%'.$Nombre.'%')->get();
        return response()->json($dato, 200);
    }

    public function BuscarCodigo($Codigo)
    {
        $dato = Local::find($Codigo);
        return response()->json($dato, 200);
    }

    public function BuscarLocalEmpresa($CodigoEmpresa)
    {
        $dato = Local::where('codigoEmpresa','=',$CodigoEmpresa)->get();
        return response()->json($dato, 200);
    }

    public function Eliminar($Codigo)
    {
        $dato = Local::find($Codigo);
        $dato->delete();
        return response()->json(true, 200);
    }

    public function mostrarEmpresas()
    {
        $respuesta = [];
        $empresa = DB::table('empresa as e')
            ->select('e.Codigo', 'e.RazonSocial')
            ->where('e.Vigencia', '=', 1)
            ->get();
        foreach ($empresa as $e) {
            array_push($respuesta, array("value" => $e->Codigo, "text" => $e->RazonSocial));
        }
        return response()->json($respuesta, 200);
    }

    public function cambiarEstadoLocal($Codigo)
    {
        $vigencia = -1;
        $Local = Local::findOrFail($Codigo);
        $Local->Vigencia = !$Local->Vigencia;
        $vigencia =!$Local->Vigencia;
        $Local->save();

        return response()->json($vigencia, 200);
    }

}
