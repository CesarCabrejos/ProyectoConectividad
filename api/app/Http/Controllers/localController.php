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
        $data = [
            "Registro" => "EXITOSO"
        ];
        return response()->json($data, 200);
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
            $data = [
                "Modificacion" => "EXITOSO"
            ];
            return response()->json($data, 200);
        }else{
            $data = [
                "Modificacion" => "FALLO"
            ];
            return response()->json($data, 500);
        }
        
    }

    public function Listar()
    {
        $data = Local::all();
        return response()->json($data, 200);
    }    

    public function Buscar($Nombre)
    {
        $data = Local::where('Nombre','LIKE','%'.$Nombre.'%')->get();
        return response()->json($data, 200);
    }

    public function BuscarCodigo($Codigo)
    {
        $data = Local::find($Codigo);
        return response()->json($data, 200);
    }

    public function BuscarLocalEmpresa($CodigoEmpresa)
    {
        $data = Local::where('codigoEmpresa','=',$CodigoEmpresa)->get();
        return response()->json($data, 200);
    }

    public function Eliminar($Codigo)
    {
        $data = Local::find($Codigo);
        $data->delete();
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
        $Local = Local::findOrFail($Codigo);
        $Local->Vigencia = !$Local->Vigencia;
        $Local->save();

        return response()->json($Local, 200);
    }

}
