<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class createCourse extends FormRequest
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
            'title' => 'required|string|max:250|min:20',
            'description' => 'required|max:1500|min:20',
            'price' => 'numeric|max:99999.00|min:1|regex:/^\d+(\.\d{1,2})?$/',
            'forum_name' => 'required|string|max:250',
            'forum_description' => 'required|max:1500|min:20',
        ];
    }
}
