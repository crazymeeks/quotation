<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'role' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'username' => 'required|unique:users,username,' . $this->id,
            'password' => 'required|min:6',
        ];

        if ($this->has('id')) {
            if (!$this->has('password')) {
                unset($rules['password']);
            }
        }
        
        return $rules;
    }
}
