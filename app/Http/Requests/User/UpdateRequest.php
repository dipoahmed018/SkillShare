<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return Auth::check();
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
            'profile_picture' => ['mimes:jpg,png,jpeg', Rule::dimensions()->maxWidth(5000)->maxHeight(7000)->minWidth(400)->minHeight(600),'max:10000'],
            'name' => 'max:100',
            'gender' => Rule::in(['male','female']),
            'birthdate' => 'date|before:'. now()->subYears(12) .'|after:' . now()->subYears(150)

        ];
    }
}
