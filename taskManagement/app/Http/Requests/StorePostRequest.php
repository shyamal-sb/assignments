<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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

        $rules = [];
        switch ($this->method()) {
            case 'POST' :
                $rules = [
                        'title' => 'required|max:300|unique:posts',
                        'content' => 'required|string',
                        'user_id' => 'required|exists:users,id'
                ];
                break;

            case 'PUT' :
                $rules = [
                    'title' => 'required|max:300',
                    'content' => 'required|string',
                    'user_id' => 'required|exists:users,id'
                ];
                break;
        }
        return $rules;
    }
}
