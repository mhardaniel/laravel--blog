<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => [
                'sometimes', 'string', 'max:255', 'unique:users,username',

            ],
            'email' => [
                'sometimes', 'string', 'email', 'max:255', 'unique:users,email',

            ],
            'bio' => 'sometimes|nullable|string',
            'image' => 'sometimes|nullable|string|url',
        ];
    }

    public function validationData()
    {
        return Arr::wrap($this->input('user'));
    }
}
