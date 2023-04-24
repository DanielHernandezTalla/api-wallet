<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MovementRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'section_id' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'min:1'],
            'type' => ['required', 'in:gasto,ingreso'],
        ];
    }

    // Agregando mensajes a cada tipo de validacion
    public function messages()
    {
        return [
            'section_id.required' => 'La seccion es obligatoria',
            'section_id.numeric' => 'El id de la seccion tiene que ser entero',
            'section_id.min' => 'El id de la seccion no puede ser negativo',

            'category_id.required' => 'La categoria es obligatoria',
            'category_id.numeric' => 'El id de la categoria tiene que ser entero',
            'category_id.min' => 'El id de la categoria no puede ser negativo',
            
            'description.max' => 'La descripcion tiene que tener maximo 100 caracteres',
            'description.required' => 'La descripcion es obligatoria',
            'description.string' => 'La descripcion debe ser un string',

            'amount.required' => 'La catidad es obligatoria',
            'amount.string' => 'La cantidad debe ser un string',

            'type.required' => 'El tipo es obligatorio',
            'type.in' => 'El tipo solo puede ser ingreso o gasto',
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
