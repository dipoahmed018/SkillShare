<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SetThumblin extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'thumblin' => ['required','mimes:jpg,png,jpeg', Rule::dimensions()->maxWidth(5000)->maxHeight(7000)->minWidth(400)->minHeight(600),'max:10000'],
        ];
    }
}
