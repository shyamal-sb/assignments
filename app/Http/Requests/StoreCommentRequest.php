<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
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
                        //'comment' => 'required|max:1000|unique:comments',
                        'comment' => 'required|max:1000',
                        'post_id' => 'required|exists:posts,id',
                        'user_id' => 'required|exists:users,id'
                ];
                break;

            case 'PUT' :
                $rules = [
                    'comment' => 'required|max:1000',
                    'post_id' => 'required|exists:posts,id',
                    'user_id' => 'required|exists:users,id'
                ];
                break;
        }
        return $rules;

    }
}
