<?php

namespace Modules\Teachers\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeachersRequest extends FormRequest
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
        $id = $this->route()->teachers;
        $rules = [
            'name' => 'required|max:255',
            'slug' => 'required|max:255|',
            'description' => 'required',
            'exp' => 'required|integer',
            'image' => 'required|max:255'
        ];

        return $rules;
    }

    public function messages() {
        return [
            'required' => __('teachers::validation.required'),
            'max' => __('teachers::validation.max'),
            'integer' => __('teachers::validation.integer')
        ];
    }

    public function attributes() {
        return __('teachers::validation.attributes');
    }
}
