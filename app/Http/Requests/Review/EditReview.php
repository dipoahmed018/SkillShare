<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

class EditReview extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('delete', $this->review);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content' => 'string|min:10|max:200',
            'stars' => 'integer|min:1|max:10',
        ];
    }
}
