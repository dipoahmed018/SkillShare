<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UpdateDetails extends FormRequest
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
        Log::channel('event')->info('update-request',[$this->title]);
        return [
            'title' => 'string|min:10|max:200',
            'description' => 'string|min:10|max:500',
            'catagories' => 'array',
            'price' => 'numeric|max:99999.00|min:1|regex:/^\d+(\.\d{1,2})?$/',
        ];
    }
}
