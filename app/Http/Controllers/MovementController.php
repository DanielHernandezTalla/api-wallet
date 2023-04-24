<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovementRequest;
use App\Services\MessagesService;
use App\Services\ResponseService;
use Exception;
use Illuminate\Support\Facades\DB;

class MovementController extends Controller
{

    public function index()
    {

        $sections = DB::select("EXEC View_MOVEMENTS", [], false);

        return ResponseService::okWithData($sections);
    }

    public function store(MovementRequest $request)
    {
        try {
            // Validar que se agrege a una categoria existente
            $category = DB::select("EXEC Get_CATEGORY_BY_ID :ID", ['ID' => $request->validated()['category_id']]);

            if (sizeof($category) == 0) {
                return ResponseService::failWithMessage('Categoria invalida', 404);
            }

            // Validar que se agrege a una seccion existente
            $section = DB::select("EXEC Get_SECTION_BY_ID :ID", ['ID' => $request->validated()['section_id']]);

            if (sizeof($section) == 0) {
                return ResponseService::failWithMessage('Seccion invalida', 404);
            }

            // Ejecutando procedimiento para dar de alta
            $res = DB::update("exec Alta_MOVEMENT :section_id, :category_id, :description, :amount, :type", $request->validated());

            // En caso de ser 0, se manda mensaje de error o mensaje de ok
            return $res == 1 ? ResponseService::ok() : ResponseService::fail();

        } catch (Exception $e) {

            // Retornando mensaje de error
            return ResponseService::failWithMessage($e->getMessage());
        }
    }

    public function show($id)
    {
        $movement = DB::select("EXEC Get_MOVEMENT_BY_ID :ID", [$id]);

        if (sizeof($movement) == 0) {
            return ResponseService::failWithMessage(MessagesService::$notFound, 404);
        }

        return ResponseService::okWithData($movement);
    }

    public function update(MovementRequest $request, $id)
    {
        try {
            // Validar que exista ese movimiento
            $movement = DB::select("EXEC Get_MOVEMENT_BY_ID :ID", [$id]);

            if (sizeof($movement) == 0) {
                return ResponseService::failWithMessage(MessagesService::$notFound, 404);
            }

            // Validar que se agrege a una categoria existente
            $category = DB::select("EXEC Get_CATEGORY_BY_ID :ID", ['ID' => $request->validated()['category_id']]);

            if (sizeof($category) == 0) {
                return ResponseService::failWithMessage('Categoria invalida', 404);
            }

            // Validar que se agrege a una seccion existente
            $section = DB::select("EXEC Get_SECTION_BY_ID :ID", ['ID' => $request->validated()['section_id']]);

            if (sizeof($section) == 0) {
                return ResponseService::failWithMessage('Seccion invalida', 404);
            }

            $res = DB::update("exec Actualizar_MOVEMENT :id, :section_id, :category_id, :description, :amount, :type", array_merge(
                ['id' => $id],
                $request->validated()));

            return $res == 1 ? ResponseService::ok() : ResponseService::fail();

        } catch (Exception $e) {
            return ResponseService::failWithMessage($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {

            $res = DB::update("exec Delete_MOVEMENT_BY_ID :ID", [$id]);

            return $res == 1
            ? ResponseService::ok()
            : ResponseService::failWithMessage(MessagesService::$notFound);

        } catch (Exception $e) {
            return ResponseService::failWithMessage($e->getMessage());
        }
    }
}
