<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\local;
use Illuminate\Support\Facades\DB;

class localController extends Controller
{
    public function Listar()
    {
        $data = local::all();
        return response()->json($data, 200);
    }    

    public function Buscar($Nombre)
    {
        $data = local::where('Nombre','LIKE','%'.$Nombre.'%')->get();
        return response()->json($data, 200);
    }

    public function BuscarCodigo($Codigo)
    {
        $data = local::find($Codigo);
        return response()->json($data, 200);
    }

    public function Nuevo(REQUEST $resquest)
    {
        $local = new local();
        $local->Nombre = $resquest->Nombre;
        $local->CodigoEmpresa = $resquest->CodigoEmpresa;
        $local->Direccion = $resquest->Direccion;
        $local->Telefono = $resquest->Telefono;
        $local->Vigencia = 1;
        $local->save();
        $data = [
            "Registro" => "EXITOSO"
        ];
        return response()->json($data, 200);
    }

    public function EditLocal(REQUEST $resquest, $Codigo)
    {
        $local = local ::find($Codigo);
        if ($local != null) {
            $local->Nombre = $resquest->Nombre;
            //$local->CodigoEmpresa = $resquest->CodigoEmpresa;
            $local->Direccion = $resquest->Direccion;
            $local->Telefono = $resquest->Telefono;
            $local->Vigencia = 1;
            $local->save();
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

    public function BuscarLocalEmpresa($CodigoEmpresa)
    {
        $data = local::where('codigoEmpresa','=',$CodigoEmpresa)->get();
        return response()->json($data, 200);
    }

    public function Eliminar($Codigo)
    {
        $data = local::find($Codigo);
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
        $local = local::findOrFail($Codigo);
        $local->Vigencia = !$local->Vigencia;
        $local->save();

        return response()->json($local, 200);
    }

}
