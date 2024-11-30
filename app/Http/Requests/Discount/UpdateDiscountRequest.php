<?php

namespace App\Http\Requests\Discount;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDiscountRequest extends FormRequest
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
            'code' => 'required|unique:discounts,code',
            'discount' => 'required',
            'limit_number' => 'required',
            'expiration_date' => 'required',
            'payment_limit' => 'required',
            'number_used' => 'required',
        ];
    }
    public function messages():array
    {
        return[
            'code.unique' => 'Mã giảm này đã tồn tại.',
            'code.required' => 'Vui lòng nhập tên giảm.',
            'expiration_date.required' => 'Vui lòng chọn ngày hết hạn.',
            'discount.required' => 'Vui lòng nhập số tiền giảm.',
            'limit_number.required' => 'Vui lòng nhập số lượng. ',
            'payment_limit.required' => 'Vui lòng nhập số tiền tối thiểu.'
        ];
    }
}
