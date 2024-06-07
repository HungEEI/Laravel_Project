<?php

namespace Modules\Lessons\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|max:255',
            'slug' => 'required|max:255|',
            'parent_id' => 'required|integer',
            'is_trial' => 'required|integer',
            'position' => 'required|integer',
            'video' => 'required'           
        ];

        return $rules;
    }

    public function messages() {
        return [
            'required' => __('lessons::validation.required'),
            'slug' => __('lessons::validation.slug'),
            'integer' => __('lessons::validation.integer')
        ];
    }

    public function attributes() {
        return __('lessons::validation.attributes');
    }
}
