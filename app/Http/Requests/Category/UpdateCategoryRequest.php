<?php

namespace App\Http\Requests\Category;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
            'status_category' => $this->has('status_category') ? 1 : 0,
         ]);
     }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            // 'name_category' => [
            //     'required',
            //     Rule::unique('categories', 'name_category')->ignore($this->id_category, 'id_category')
            //     // thay cho id mặc định thành id_category, lưu ý điều này.
            // ],
            'name_category' => 'required|unique:categories,name_category,' . $id . ',id_category',
            'id_parent' => 'nullable|exists:categories,id_category',
            'status_category' => 'required|integer',
        ];
    }
    public function messages():array
    {
        return[
            'name_category.required' => 'Vui lòng điền thông tin danh mục',
            'name_category.unique' => 'Tên danh mục này đã tồn tại, vui lòng nhập tên khác.',
        ];
    }
}
