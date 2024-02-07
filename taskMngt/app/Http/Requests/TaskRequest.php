<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //return false;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|unique:tasks|max:255', //'required', //
            'description' => 'required|string',
            'completed' => 'required|boolean' //'required'//
        ];
    }

    public function failedValidation(Validator $validator){

        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' =>'validation error',
            'errors'=>$validator->errors()
        ]));
    
    }

}
