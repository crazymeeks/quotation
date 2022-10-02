<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuotationRequest extends FormRequest
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
        $rules = [
            'customer' => 'required',
            'address' => 'required',
            'contact_no' => 'required',
        ];
        if ($this->has('customer_id')) {
            unset($rules['customer']);
        }

        // if ($this->has('id')) {
        //     $rules = [];
        // }
        return $rules;
    }
}
