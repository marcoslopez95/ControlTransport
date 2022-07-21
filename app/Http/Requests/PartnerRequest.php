<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PartnerRequest extends FormRequest
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
                'ci'         => 'required|unique:partners'
            ];
        }
        if($this->isMethod('PUT')){
            return [
                'first_name' => 'required|string',
                'last_name'  => 'required|string',
                'ci'         => ['required',Rule::unique('partners')->ignore($this->partner)]
            ];
        }
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        custom_failed_validation($validator);
    }
}
