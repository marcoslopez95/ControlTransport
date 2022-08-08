<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdditionalRequest extends FormRequest
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
        if($this->isMethod('post')){
            return [
                'description'=> 'required|unique:additionals',
                'percent'    => 'nullable|numeric|required_without:quantity',
                'quantity'   => 'nullable|numeric|required_without:percent',
                'coin_id'    => 'required|integer|exists:coins,id',
                'type'       => 'required|in:Descuento,Retencion',
            ];
        }
        if($this->isMethod('put')){
            return [
                'description'=> ['required',Rule::unique('additionals')->ignore($this->additional)],
                'percent'    => 'nullable|numeric|required_without:quantity',
                'quantity'   => 'nullable|numeric|required_without:percent',
                'coin_id'    => 'required|integer|exists:coins,id',
                'type'       => 'required|in:Descuento,Retencion',
            ];
        }
    }

    public function messages()
    {
        return [
            'required'          => 'El campo :attribute es requerido',
            'numeric'           => 'El campo :attribute debe ser un número',
            'integer'           => 'El campo :attribute debe ser un número',
            'required_unless'   => 'El campo :attribute es requerido cuando no se envía :other',
            'exists'            => 'El campo :attribute NO existe en la base de datos',
            'in'                => 'El tipo debe ser: Descuento o Retencion'
        ];
    }
}
