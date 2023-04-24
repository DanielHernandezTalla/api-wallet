<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoryRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'icon_id' => ['required', 'numeric','min:0'],
            'name' => ['required', 'string', 'min:3', 'max:30'],
            'color' => ['required', 'string', 'size:6'],
        ];
    }

    public function messages()
    {
        return [
            'icon_id.required' => 'El icono es obligatorio',
            'icon_id.numeric' => 'El id del icono tiene que ser entero',
            'icon_id.min' => 'El id del icono tiene que ser positivo',
            'name.required' => 'El nombre es obligatorio',
            'name.string' => 'El nombre debe ser un string',
            'name.min' => 'El nombre tiene que tener minimo 3 caracteres',
            'name.max' => 'El nombre tiene que tener maximo 30 caracteres',
            'color.required' => 'El color es obligatorio',
            'color.string' => 'El color tiene que ser string',
            'color.size' => 'El color es de 6 caracteres',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors(),
        ]));
    }
}
