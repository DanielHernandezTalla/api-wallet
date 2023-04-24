<?php

namespace App\Http\Controllers;

use App\Http\Requests\IconRequest;
use App\Services\MessagesService;
use App\Services\ResponseService;
use Exception;
use Illuminate\Support\Facades\DB;

class IconController extends Controller
{

    public function index()
    {
        $icons = DB::select("EXEC View_ICONS", [], false);

        return ResponseService::okWithData($icons);
    }

    public function store(IconRequest $request)
    {
        try {
            $icon = DB::select("exec Get_ICON_BY_ID_NAME :ID", [$request->validated()['name']]);

            if ($icon[0]->icons >= 1) {
                return ResponseService::failWithMessage(MessagesService::$isDuplicated);
            }

            // Ejecutando procedimiento para dar de alta un usuario
            $res = DB::update("exec Alta_ICON :name, :path", $request->validated());

            // En caso de ser 0, se manda mensaje de error o mensaje de ok
            return $res == 1 ? ResponseService::ok() : ResponseService::fail();

        } catch (Exception $e) {

            // Retornando mensaje de error
            return ResponseService::failWithMessage($e->getMessage());
        }
    }

    public function show($id)
    {
        $icon = DB::select("EXEC Get_ICON_BY_ID :ID", [$id]);

        if (sizeof($icon) == 0) {
            return ResponseService::failWithMessage(MessagesService::$notFound, 404);
        }

        return ResponseService::okWithData($icon);
    }

    public function update(IconRequest $request, $id)
    {
        try {

            $icons = DB::select("EXEC Get_ICON_BY_ID :ID", [$id]);

            if (sizeof($icons) == 0) {
                return ResponseService::failWithMessage(MessagesService::$notFound, 404);
            }

            $res = DB::update("exec Actualizar_ICON :id, :name, :path", array_merge(
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

            $res = DB::select("exec Count_CATEGORIES_BY_ICON :ID", [$id]);

            if (!empty($res)) {
                return ResponseService::failWithMessage('El icono cuenta con categorias existentes');
            }

            $res = DB::affectingStatement("exec Delete_ICON_BY_ID :ID", [$id]);

            return $res == 1
            ? ResponseService::ok()
            : ResponseService::failWithMessage(MessagesService::$notFound);

        } catch (Exception $e) {
            return ResponseService::failWithMessage($e->getMessage());
        }
    }
}
