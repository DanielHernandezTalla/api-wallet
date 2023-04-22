<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    // Agregando reglas de validacion
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:75'],
            'password' => ['required', 'string', 'min:4', 'max:50'],
        ];
    }

    // Agregando mensajes a cada tipo de validacion 
    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.string' => 'El nombre debe ser un string',
            'name.max' => 'El nombre tiene que tener maximo 100 caracteres',
            'email.required' => 'El email es obligatorio',
            'email.string' => 'El email tiene que ser un string',
            'email.email' => 'El email tiene que ser un email',
            'email.max' => 'El email debe ser maximo 75 caracteres',
            'password.require' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña acepta como minimo 4 caracteres',
            'password.max' => 'La contraseña acepta como maximo 50 caracteres',
        ];
    }

    // Haciendo que regrese lista de errores 
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors(),
        ]));

    }
}
