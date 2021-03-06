<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KeyEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'newKeyName' => ['required',
                      'string',
                      'max:45',
                      'min:4', 
                      ]
           
        ];
    }

    public function messages()
    {
        return [
            'newKeyName.required' => 'El nuevo nombre no puede estar vacio',
            'newKeyName.min' => 'El nombre debe de tener minimo 4 caracteres',
            'newKeyName.max' => 'El nombre no puede ser tan largo'
        ];
    }
}
