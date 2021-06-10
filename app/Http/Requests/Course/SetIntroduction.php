<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

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
        $chunk_get_resume = $this->header(('x-resumeable'));
        $cancel = $this->header(('x-cancel'));
        
        if($chunk_get_resume == true){
            return [
            'introduction_name' => 'required|string|max:400',
            ];
        }
        if($cancel == true){
            return [
                'introduction_name' => 'required|string|max:400',
            ];
        }
        return [
            'introduction_name' => 'required|string|max:200',
            'chunk_file' => 'required|string|max:6000000',
        ];
    }
}
