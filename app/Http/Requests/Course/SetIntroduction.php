<?php

namespace App\Http\Requests\Course;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class SetIntroduction extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->course);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'introduction_name' => 'required|string|max:400',
            'introduction_type' => ['required', Rule::in('video/mp4')],
            'chunk_file' => 'required|string|max:6000000',
            'last_chunk' => ['required', Rule::in(true, false)],
            'full_file_size' => 'required|integer|max:' . 1024  * 1024 * 500, 
            'cancel' => [Rule::in(true, false)]
        ];
    }
}
