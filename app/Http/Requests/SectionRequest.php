<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SectionRequest extends FormRequest
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
            'user_id' => ['required', 'numeric','min:0'],
        ];
    }

    // Agregando mensajes a cada tipo de validacion 
    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.string' => 'El nombre debe ser un string',
            'name.max' => 'El nombre tiene que tener maximo 100 caracteres',
            'user_id.required' => 'El usuario es obligatorio',
            'user_id.numeric' => 'El id del usuario tiene que ser entero',
            'user_id.min' => 'El id del usuario no puede ser negativo',
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
