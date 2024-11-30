<?php

namespace App\Http\Requests\Product;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        return [
            'name_product' => [
                'required',
                Rule::unique('products', 'name_product')->ignore($this->id_product, 'id_product')
            ],
            'sku' => [
                'required',
                Rule::unique('products','sku')->ignore($this->id_product, 'id_product')
            ],
            'sortdect_product' => 'nullable',
            'desc_product' => 'required',
            'price_product' => 'required',
            'sellprice_product' => 'nullable',
            'avt_product'=>'required',
            'id_category' => 'required|exists:categories,id_category',
        ];
    }
    public function messages():array
    {
        return[
            'name_product.unique' => 'Tên sản phẩm này đã tồn tại, vui lòng nhập tên khác.',
            'name_product.required' => 'Vui lòng nhập tên sản phẩm.',
            'sku.unique' => 'Mã này đã tồn tại, vui lòng nhập mã khác.',
            'sku.required' => 'Vui lòng nhập mã sản phẩm.',
            'price_product.required' => 'Vui lòng nhập giá bán.',
            'id_category.required' => 'Vui lòng chọn danh mục.',
            'avt_product'=>'Vui lòng nhập ảnh đại diện.',
            'desc_product.required' => 'Vui lòng nhập mô tả cho sản phẩm.',
        ];
    }
}
