<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\ResponseService;
use App\Services\MessagesService;
use Illuminate\Support\Facades\DB;
use Exception;

class UserController extends Controller
{
    public function index()
    {
        // $users = DB::select('EXEC View_USERS');
        $users = DB::select("EXEC View_USERS", [], false);

        return ResponseService::okWithData($users);
    }

    public function store(UserRequest $request)
    {
        // Aqui ya esta validado por el UserRequest

        // Envio de datos a la base de datos
        try {

            // Verificando que el usuario no exista en la base de datos
            $user = DB::select("exec Get_USER_BY_EMAIL :email", [$request->validated()['email']]);

            // Si se encuentra usuario en la base de datos, se manda mensaje de duplicado
            if ($user) 
                return ResponseService::failWithMessage(MessagesService::$isDuplicated);
        
            // Ejecutando procedimiento para dar de alta un usuario
            $res = DB::update("exec Alta_USER :name, :email, :password", $request->validated());

            // En caso de ser 0, se manda mensaje de error o mensaje de ok
            return $res == 1 ? ResponseService::ok() : ResponseService::fail();

        } catch (Exception  $e) {

            // Retornando mensaje de error
            return ResponseService::failWithMessage($e->getMessage());
        }
    }

    public function show($id)
    {
        $user = DB::select("EXEC Get_USER_BY_ID :ID", [$id]);
        
        if(sizeof($user) == 0 )
            return ResponseService::failWithMessage(MessagesService::$notFound, 404);

        return ResponseService::okWithData($user);
    }

    public function update($id, UserRequest $request)
    {
        try{

            $user = DB::select("EXEC Get_USER_BY_ID :ID", [$id]);
        
            if(sizeof($user) == 0 )
                return ResponseService::failWithMessage(MessagesService::$notFound);

            $res = DB::update("exec Actualizar_USER :id, :name, :email, :password", array_merge(['id'=>$id], $request->validated()));
            
            return $res == 1 ? ResponseService::ok() : ResponseService::fail();

        }catch(Exception $e){
            return ResponseService::failWithMessage($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try{
            $res = DB::update("exec Delete_USER_BY_ID :ID", [$id]);
            
            return $res == 1 
                ? ResponseService::ok() 
                : ResponseService::failWithMessage(MessagesService::$notFound);

        }catch(Exception $e){
            return ResponseService::failWithMessage($e->getMessage());
        }        
    }
}
