<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CoinRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        if($this->isMethod('post'))
            return [
                'name'  => 'required|unique:coins|string',
                'symbol'=> 'required|unique:coins|string'
            ];
        if($this->isMethod('put'))
            return [
                'name'  => ['required',Rule::unique('coins')->ignore($this->coin),'string'],
                'symbol'=> ['required',Rule::unique('coins')->ignore($this->coin),'string'],
            ];
    }

    public function messages()
    {
        return [
            'required'  => 'El campo :attribute es requerido',
            'unique'    => 'El campo :attribute ya se encuentra registrado',
            'string'    => 'El campo :attribute debe ser un texto'
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        custom_failed_validation($validator);
    }
}
