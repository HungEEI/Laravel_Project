<?php

namespace Modules\Courses\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CoursesRequest extends FormRequest
{
    /**
     * Determine if the courses is authorized to make this request.
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
        $id = $this->route()->course;

        $uiqueRule = 'unique:courses,code';
        if ($id) {
            $uiqueRule.=','.$id;
        }
;
        $rules = [
            'name' => 'required|max:255',
            'slug' => 'required|max:255|',
            'detail' => 'required',
            'teacher_id' => ['required', 'integer', function($attribute, $value, $fail) {
                if ($value == 0) {
                    $fail(__('courses::validation.select'));
                }
            }],
            'thumbnail' => 'required|max:255',
            'code' => 'required|max:255|'.$uiqueRule,
            'is_document' => 'required|integer',
            'supports' => 'required',
            'status' => 'required|integer',
            'categories' => 'required'
        ];

        return $rules;
    }

    public function messages() {
        return [
            'required' => __('courses::validation.required'),
            'slug' => __('courses::validation.slug'),
            'unique' => __('courses::validation.unique'),
            'min' => __('courses::validation.min'),
            'max' => __('courses::validation.max'),
            'integer' => __('courses::validation.integer')
        ];
    }

    public function attributes() {
        return __('courses::validation.attributes');
    }
}
