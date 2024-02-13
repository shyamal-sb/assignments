<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        switch ($this->method()) {
            case 'POST' :
                $rules = [
                        'email' => 'required|email|unique:users',
                        'name' => 'required',
                        'password' => 'required'
                ];
                break;

            case 'PUT' :
                $rules = [
                    'email' => 'required|email',
                    'name' => 'required',
                    'password' => 'required'
                ];
                break;
        }
        return $rules;
    }
}
