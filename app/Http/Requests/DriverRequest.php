<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DriverRequest extends FormRequest
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
        if($this->isMethod('POST')){
            return [
                'first_name' => 'required|string',
                'last_name'  => 'required|string',
                'ci'         => 'required|unique:drivers'
            ];
        }
        if($this->isMethod('PUT')){
            return [
                'first_name' => 'required|string',
                'last_name'  => 'required|string',
                'ci'         => ['required',Rule::unique('drivers')->ignore($this->drive)]
            ];
        }
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        custom_failed_validation($validator);
    }
}
