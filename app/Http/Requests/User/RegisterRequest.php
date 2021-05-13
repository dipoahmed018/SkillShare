<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
        return [
            'name' => 'required|max:100',
            'email' => 'required|unique:users|email:rfc',
            'password' => 'required|min:8|max:50|confirmed',
            'gender' => ['required', Rule::in(['male','female'])],
            'birthdate' => 'required|date|before:'. now()->subYears(12),
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'please provide your name',
            'birthdate.required' => 'please provide your birthdate',
            'gender.required' => 'please provide your gender',
            'birthdate.before' => 'you must be at least 12 years old',
        ];
    }
}
