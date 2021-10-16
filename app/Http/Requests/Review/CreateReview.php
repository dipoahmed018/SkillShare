<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CreateReview extends FormRequest
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
            'reviewable_id' => 'required|integer',
            'reviewable_type' => ['required', Rule::in(['course', 'review_reply'])],
            'content' => 'required|string|min:5|max:200',
            'stars' => 'integer|min:1|max:10',
        ];
    }
}
