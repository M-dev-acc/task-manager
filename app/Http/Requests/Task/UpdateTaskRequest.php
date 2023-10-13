<?php

namespace App\Http\Requests\Task;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => "required|string|min:2|max:255",
            'status' => "required|boolean",
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
