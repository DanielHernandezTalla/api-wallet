<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class IconRequest extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string','min:3', 'max:30'],
            'path' => ['required', 'url'],
        ];
    }

    // Agregando mensajes a cada tipo de validacion 
    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.string' => 'El nombre debe ser un string',
            'name.min' => 'El nombre tiene que tener minimo 3 caracteres',
            'name.max' => 'El nombre tiene que tener maximo 30 caracteres',
            'path.required' => 'La url es obligatoria',
            'path.url' => 'Tiene que ser una url',
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
