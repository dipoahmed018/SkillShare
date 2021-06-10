<?php

namespace App\Http\Requests\Course;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        Log::channel('event')->info($this->course);
        return $this->user()->can('update', $this->course);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $chunk_get_resume = $this->header(('x-resumeable'));
        $cancel = $this->header(('x-cancel'));
        if($chunk_get_resume == true){
            return [
            'tutorial_name' => 'required|string|max:400',
            ];
        }
        if($cancel == true){
            return [
                'tutorial_name' => 'required|string|max:400',
            ];
        }
        return [
            'tutorial_name' => 'required|string|max:400',
            'chunk_file' => 'required|string|max:6000000',
        ];
    }
}
