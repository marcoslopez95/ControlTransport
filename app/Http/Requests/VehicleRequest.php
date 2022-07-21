<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehicleRequest extends FormRequest
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
                'plate' => 'required|string|unique:vehicles',
                'num_control' => 'required|string|unique:vehicles',
                'description' => 'nullable|string',
                'status'    => 'nullable|in:Operativo,En Reparación,Averiado'
            ];
        }
        if($this->isMethod('put')){
            return [
                'plate' => ['required','string',Rule::unique('vehicles')->ignore($this->vehicle)],
                'num_control' => ['required','string',Rule::unique('vehicles')->ignore($this->vehicle)],
                'description' => 'nullable|string',
                'status'    => 'nullable|in:Operativo,En Reparación,Averiado'
            ];
        }

    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        custom_failed_validation($validator);
    }
}
