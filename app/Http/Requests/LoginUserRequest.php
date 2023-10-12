<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginUserRequest extends FormRequest
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
        return [
            'username' => "required|email|exists:users,email",
            'password' => "required|string|min:7|max:60",
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $errorMessages = $validator->errors()->messages();

        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => reset($errorMessages)[0],
        ], 422));
    }
}
