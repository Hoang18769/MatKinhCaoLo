<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
    protected function prepareForValidation(): void
    {
        $this->merge([
            'status_category' => $this->input('status_category', 1),
        ]);
    }
    public function rules(): array
    {
        return [
            'name_category' => [
                'required',
                'unique:categories,name_category',
            ],
            'id_parent' => 'nullable|exists:categories,id_category',
            'status_category' => 'required|integer',
        ];
    }
    public function messages():array
    {
        return[
            // 'name.required'=>'Vui lòng điền tên danh mục',
            // 'name.unique'=>"$this->name đã tồn tại",
            'name_category.required' => 'Vui lòng điền thông tin danh mục.',
            'name_category.unique' => "$this->name_category đã tồn tại, vui lòng nhập tên khác.",
        ];
    }

}
