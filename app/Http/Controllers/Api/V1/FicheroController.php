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

            $urlDescarga = '1';

            $eliminado = ($fichero->eliminado == 1) ? true : false;

            $listadoFicheros[] = [
                'id' => $fichero->id,
                'nombre' => $fichero->nombre,
                'extension' => $fichero->extension,
                'fecha_registro' => date_format(date_create($fichero->created_at), 'd/m/Y'),
                'hora_registro' => date_format(date_create($fichero->created_at), 'H:i:s'),
                'archivo' => $urlDescarga,
                'eliminado' => $eliminado,
            ];
        }

        return response()->json($listadoFicheros);
    }

    public function descargarFichero($idFichero)
    {
        $fichero = Fichero::find($idFichero);

        if (!isset($fichero)) {
            return response()->json([
                'code' => 400,
                'status' => 'fail',
                'message' => 'Fichero no encontrado.',
            ]);
        }

        //$fichero->contenido
    }

    public function guardarFichero(Request $request)
    {
        if (!isset($request->archivo)) {
            return response()->json([
                'code' => 400,
                'status' => 'fail',
                'message' => 'Ningún fichero fue seleccionado.',
            ]);
        }

        if (count($request->archivo) == 0) {
            return response()->json([
                'code' => 400,
                'status' => 'fail',
                'message' => 'No hay ficheros para procesar.',
            ]);
        }

        $errorMessage = '';
        $exito = 0;
        foreach ($request->archivo as $archivo) {
            if (!isset($archivo)) {
                $errorMessage .= '(No se encontró el fichero ' . $archivo->getClientOriginalName() . ') ';
                continue;
                /* return response()->json([
                    'code' => 400,
                    'status' => 'fail',
                    'message' => 'Debe subir el archivo.',
                ]); */
            }

            if (!$archivo->isValid()) {
                $errorMessage .= '(No se encontró el fichero ' . $archivo->getClientOriginalName() . ') ';
                continue;
                /* return response()->json([
                    'code' => 400,
                    'status' => 'fail',
                    'message' => 'Error inesperado.',
                ]); */
            }

            if (filesize($archivo) > 500000) {
                $errorMessage .= '(El fichero ' . $archivo->getClientOriginalName() . ' excede el límite de 500kb) ';
                continue;
                /* return response()->json([
                    'code' => 400,
                    'status' => 'fail',
                    'message' => 'El archivo no debe pesar más de 500kb.',
                ]); */
            }

            $extension = $archivo->getClientOriginalExtension();

            if ($extension == '') {
                $errorMessage .= '(El fichero ' . $archivo->getClientOriginalName() . ' no tiene extensión) ';
                continue;
                /* return response()->json([
                    'code' => 400,
                    'status' => 'fail',
                    'message' => 'El archivo no tiene extensión.',
                ]); */
            }

            $nombre = substr($archivo->getClientOriginalName(), 0, -1 * (strlen($extension) + 1));

            try {
                $ficheros = Fichero::create([
                    'nombre' => $nombre,
                    'extension' => $extension,
                    'contenido' => $archivo->get(),
                ]);

                $exito++;
            } catch (\Throwable $th) {
                $errorMessage .= '(Error al guardar el fichero ' . $archivo->getClientOriginalName() . ') ';
                continue;
            }
        }

        return response()->json([
            'code' => 201,
            'status' => 'success',
            'message' => $exito . ' fichero(s) subidos con éxito ' . $errorMessage,
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
