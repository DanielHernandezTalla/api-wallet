<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Services\ResponseService;
use App\Services\MessagesService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['unauthorized', 'login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unauthorized()
    {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }

    public function register(UserRequest $request)
    {
        // try {

        //     // Verificando que el usuario no exista en la base de datos
        //     $user = DB::select("exec Get_USER_BY_EMAIL :email", [$request->validated()['email']]);

        //     // Si se encuentra usuario en la base de datos, se manda mensaje de duplicado
        //     if ($user) {
        //         return ResponseService::failWithMessage(MessagesService::$isDuplicated);
        //     }

        //     // Ejecutando procedimiento para dar de alta un usuario
        //     $res = DB::update("exec Alta_USER :name, :email, :password", array_merge(
        //         $request->validated(),
        //         ['password' => bcrypt($request->validated()['password'])]
        //     ));

            

        //     // En caso de ser 0, se manda mensaje de error o mensaje de ok
        //     return $res == 1 ? ResponseService::ok() : ResponseService::fail();

        // } catch (Exception $e) {

        //     // Retornando mensaje de error
        //     return ResponseService::failWithMessage($e->getMessage());
        // }

        $user = User::create(array_merge(
            $request->validated(),
            ['password' => bcrypt($request->validated()['password'])]
        ));

        return response()->json([
            'message' => '¡Usuario registrado exitosamente!',
            'user' => $user
        ], 201);
    }
}
