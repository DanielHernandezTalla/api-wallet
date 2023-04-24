<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionRequest;
use App\Services\MessagesService;
use App\Services\ResponseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{

    public function index()
    {
        $sections = DB::select("EXEC View_SECTIONS", [], false);

        return ResponseService::okWithData($sections);
    }

    public function store(SectionRequest $request)
    {

        try {
            // Verificando que el usuario exista en la base de datos
            $user = DB::select("exec Get_USER_BY_ID :ID", [$request->validated()['user_id']]);

            if (sizeof($user) == 0) {
                return ResponseService::failWithMessage('El usuario no existe', 404);
            }

            // Verificar si el usuario ya cuenta con una seccion de ese nombre
            $sectionByUser = DB::select("exec Get_SECTION_BY_ID_NAME :user_id, :name", $request->validated());

            if ($sectionByUser[0]->sections >= 1) {
                return ResponseService::failWithMessage(MessagesService::$isDuplicated);
            }

            // Ejecutando procedimiento para dar de alta un usuario
            $res = DB::update("exec Alta_SECTION :name, :user_id", $request->validated());

            // En caso de ser 0, se manda mensaje de error o mensaje de ok
            return $res == 1 ? ResponseService::ok() : ResponseService::fail();

        } catch (Exception $e) {

            // Retornando mensaje de error
            return ResponseService::failWithMessage($e->getMessage());
        }
    }

    public function show($id)
    {
        $section = DB::select("EXEC Get_SECTION_BY_ID :ID", [$id]);

        if (sizeof($section) == 0) {
            return ResponseService::failWithMessage(MessagesService::$notFound, 404);
        }

        return ResponseService::okWithData($section);
    }

    public function update(SectionRequest $request, $id)
    {
        try {
            
            $section = DB::select("EXEC Get_SECTION_BY_ID :ID", [$id]);
            if (sizeof($section) == 0) {
                return ResponseService::failWithMessage(MessagesService::$notFound, 404);
            }

            $res = DB::update("exec Actualizar_SECTION :id, :name", array_merge(
                ['id' => $id], 
                ['name' => $request->validated()['name']]));

            return $res == 1 ? ResponseService::ok() : ResponseService::fail();

        } catch (Exception $e) {
            return ResponseService::failWithMessage($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try{

            // Falta validar que no tenga ningun movimiento
            $res = DB::select("exec Count_MOVEMENTS_BY_SECTION :ID", [$id]);

            if (!empty($res)) {
                return ResponseService::failWithMessage('La seccion cuenta con movimientos existentes');
            }

            $res = DB::update("exec Delete_SECTION_BY_ID :ID", [$id]);
            
            return $res == 1 
                ? ResponseService::ok() 
                : ResponseService::failWithMessage(MessagesService::$notFound);

        }catch(Exception $e){
            return ResponseService::failWithMessage($e->getMessage());
        }  
    }
}
