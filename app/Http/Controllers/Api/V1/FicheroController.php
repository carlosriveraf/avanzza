<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Api\V1\Fichero;
use Illuminate\Http\Request;

class FicheroController extends Controller
{
    public function listarFicheros()
    {
        $rsFicheros = Fichero::all();

        $listadoFicheros = [];
        foreach ($rsFicheros as $fichero) {
            $listadoFicheros[] = [
                'nombre' => $fichero->nombre,
                'extension' => $fichero->extension,
            ];
        }

        echo '<pre>';
        var_dump($listadoFicheros);
        echo '</pre>';
    }

    public function guardarFichero(Request $request)
    {
        if (!isset($request->archivo)) {
            return response()->json([
                'code' => 400,
                'status' => 'fail',
                'message' => 'Debe subir el archivo.',
            ]);
        }

        if (!$request->archivo->isValid()) {
            return response()->json([
                'code' => 400,
                'status' => 'fail',
                'message' => 'Error inesperado.',
            ]);
        }

        if (filesize($request->archivo) > 500000) {
            return response()->json([
                'code' => 400,
                'status' => 'fail',
                'message' => 'El archivo no debe pesar más de 500kb.',
            ]);
        }

        $extension = $request->archivo->getClientOriginalExtension();

        if ($extension == '') {
            return response('El archivo no tiene extensión.', 400);
        }

        $nombre = substr($request->archivo->getClientOriginalName(), 0, -1 * (strlen($extension) + 1));

        $fichero = new Fichero();
        $fichero->nombre = $nombre;
        $fichero->extension = $extension;
        $fichero->contenido = $request->archivo->get();
        $fichero->save();

        if ($fichero->save()) {
            return response()->json([
                'code' => 201,
                'status' => 'success',
                'message' => 'Archivo subido con éxito.',
            ]);
        }

        return response()->json([
            'code' => 500,
            'status' => 'fail',
            'message' => 'Hubo problemas al subir el archivo.',
        ]);
    }

    public function eliminarFichero($idFichero)
    {
        $fichero = Fichero::find($idFichero);

        if (!isset($fichero)) {
            return response()->json([
                'code' => 400,
                'status' => 'fail',
                'message' => 'Fichero no encontrado.',
            ]);
        }

        if ($fichero->eliminado == 1) {
            return response()->json([
                'code' => 400,
                'status' => 'fail',
                'message' => 'Este fichero ya se encuentra eliminado.',
            ]);
        }

        $fichero->eliminado = 1;

        if ($fichero->save()) {
            return response()->json([
                'code' => 201,
                'status' => 'success',
                'message' => 'Fichero eliminado.',
            ]);
        }

        return response()->json([
            'code' => 500,
            'status' => 'fail',
            'message' => 'Hubo problemas al eliminar el fichero.',
        ]);
    }

    public function eliminarFicheroFisico($idFichero)
    {
        $fichero = Fichero::find($idFichero);

        if (!isset($fichero)) {
            return response()->json([
                'code' => 400,
                'status' => 'fail',
                'message' => 'Fichero no encontrado.',
            ]);
        }

        if ($fichero->delete()) {
            return response()->json([
                'code' => 201,
                'status' => 'success',
                'message' => 'Fichero eliminado de la BD.',
            ]);
        }

        return response()->json([
            'code' => 500,
            'status' => 'fail',
            'message' => 'Hubo problemas al eliminar el fichero de la BD.',
        ]);
    }
}
