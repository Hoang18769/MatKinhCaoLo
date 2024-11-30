<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
                'unique:products,name_product',
            ],
            'sku' => [
                'required',
                'unique:products,sku',
            ],
            'sortdect_product' => 'nullable',
            'desc_product' => 'required',
            'price_product' => 'required',
            'sellprice_product' => 'nullable',
            'id_category' => 'required|exists:categories,id_category',
            'avt_product'=>'url',
            'image_product' => 'required|array',
            // 'image_product'=>'url',
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
            'sortdesc_product.required' => 'Vui lòng nhập mô tả ngắn cho sản phẩm.',
            'desc_product.required' => 'Vui lòng nhập mô tả cho sản phẩm.',
            'avt_product.url'=>'Vui lòng nhập ảnh bằng URL',
            // 'image_product.url'=>'Vui lòng nhập Album ảnh bằng URL',
        ];
    }
}
