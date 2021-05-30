<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AddVideo extends FormRequest
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
            'tutorial_name' => 'required|string|max:400',
            'tutorial_type' => ['required', Rule::in('video/mp4')],
            'chunk_file' => 'required|string|max:6000000',
            'last_chunk' => ['required', Rule::in(true, false)],
            'full_file_size' => 'required|integer|max:'. 1024 * 1024 * 500,
        ];
    }
}
