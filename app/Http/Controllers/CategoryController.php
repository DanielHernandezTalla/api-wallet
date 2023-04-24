<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Services\MessagesService;
use App\Services\ResponseService;
use Exception;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = DB::select("EXEC View_CATEGORIES", [], false);

        return ResponseService::okWithData($categories);
    }

    public function store(CategoryRequest $request)
    {
        try {
            // Validar que la categoria no existe
            $category = DB::select("exec Get_CATEGORY_BY_ID_NAME :icon_id, :name", [
                $request->validated()['icon_id'],
                $request->validated()['name']]);

            if (sizeof($category) > 0) {
                return ResponseService::failWithMessage(MessagesService::$isDuplicated);
            }

            // Validar que existe el icono
            $icons = DB::select("EXEC Get_ICON_BY_ID :ID", ['ID' => $request->validated()['icon_id']]);
            if (sizeof($icons) == 0) {
                return ResponseService::failWithMessage('El id del icono es incorrecto', 404);
            }

            // Ejecutando procedimiento para dar de alta la categoria
            $res = DB::update("exec Alta_CATEGORY :icon_id, :name, :color", $request->validated());

            // En caso de ser 0, se manda mensaje de error o mensaje de ok
            return $res == 1 ? ResponseService::ok() : ResponseService::fail();

        } catch (Exception $e) {

            // Retornando mensaje de error
            return ResponseService::failWithMessage($e->getMessage());
        }
    }

    public function show($id)
    {
        $category = DB::select("EXEC Get_CATEGORY_BY_ID :ID", [$id]);

        if (sizeof($category) == 0) {
            return ResponseService::failWithMessage(MessagesService::$notFound, 404);
        }

        return ResponseService::okWithData($category);
    }

    public function update(CategoryRequest $request, $id)
    {
        try {

            // Validar que la categoria a editar existe
            $category = DB::select("EXEC Get_CATEGORY_BY_ID :ID", [$id]);

            if (sizeof($category) == 0) {
                return ResponseService::failWithMessage(MessagesService::$notFound, 404);
            }

            // Validar que existe el icono
            $icons = DB::select("EXEC Get_ICON_BY_ID :ID", ['ID' => $request->validated()['icon_id']]);
            if (sizeof($icons) == 0) {
                return ResponseService::failWithMessage('El id del icono es incorrecto', 404);
            }

            $res = DB::update("exec Actualizar_CATEGORY :id, :icon_id, :name, :color", array_merge(
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

            // Falta validar que no tenga ninguna movimiento
            $res = DB::select("exec Count_MOVEMENTS_BY_CATEGORY :ID", [$id]);

            if (!empty($res)) {
                return ResponseService::failWithMessage('La categoria cuenta con movimientos existentes');
            }

            $res = DB::update("exec Delete_CATEGORY_BY_ID :ID", [$id]);

            return $res == 1
            ? ResponseService::ok()
            : ResponseService::failWithMessage(MessagesService::$notFound);

        } catch (Exception $e) {
            return ResponseService::failWithMessage($e->getMessage());
        }
    }
}
