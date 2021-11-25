<?php

namespace App\Http\Requests\Forum;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ForumUpdate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->forum);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string|min:5|max:200',
            'description' => 'string|min:10|max:500',
            'cover' => ['mimes:jpg, png, jpeg', Rule::dimensions()->maxWidth(10000)->maxHeight(10000)->minWidth(400)->minHeight(600),'max:10000'],
        ];
    }
}
