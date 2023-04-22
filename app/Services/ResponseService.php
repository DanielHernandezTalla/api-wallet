<?php

namespace App\Services;

class ResponseService
{
    /**
     * Response a general message
     *
     * @return \Illuminate\Http\Response
     */
    static public function ok($message = 'Operacion realizada con exito')
    {
        return response()->json([
            'success' => true,
            'route' => ResponseService::getRoute(),
            'message' => $message,
            'data' => [],
        ]);
    }

    /**
     * Response a with data, route and message is optional 
     *
     * @return \Illuminate\Http\Response
     */
    static public function okWithData($data, $message = 'Operacion realizada con exito')
    {
        return response()->json([
            'success' => true,
            'route' => ResponseService::getRoute(),
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * General fail message
     *
     * @return \Illuminate\Http\Response
     */
    static public function fail()
    {
        return response()->json([
            'success' => false,
            'route' => ResponseService::getRoute(),
            'message' => 'Operacion fallida, habla con el administrador',
            'data' => [],
        ], 404);
    }

    /**
     * Specific fail message
     *
     * @return \Illuminate\Http\Response
     */
    static public function failWithMessage($message, $status = 400)
    {
        return response()->json([
            'success' => false,
            'route' => ResponseService::getRoute(),
            'message' => $message,
            'data' => [],
        ], $status);
    }

    /**
     * Specific fail message
     *
     * @return \Illuminate\Http\Response
     */
    static public function failWithError($error, $message = 'Error, habla con el administrador', $status = 400)
    {
        return response()->json([
            'success' => false,
            'route' => ResponseService::getRoute(),
            'message' => $message,
            'data' => $error,
        ], $status);
    }

    static protected function getRoute(){

        // Obteniendo el metodo y convirtiendolo en string
        $method = strtolower(request()->method());

        // Obteniendo el path actual y convirtiendolo en array
        $path = explode('/',request()->path());

        // Quitar la parte de api de la url
        unset($path[0]);

        // si el ultimo elemento es un id, no se toma en cuenta y se elimina
        if(is_numeric(end($path)))
            array_pop($path);

        // concatenando el array con un punto en medio
        $path = implode('.',$path);

        // retornando el path con el metodo para formar la ruta
        return $path . '.' . $method;
    }

}
