<?php

namespace App\Http\Requests\Customer;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
            'name_customer' => 'required',
            'phone_customer' => [
                'required',
                'digits:10',
                Rule::unique('customers', 'phone_customer')->ignore($this->route('customer'), 'id_customer')
            ],
            'address_customer' => 'nullable',
            'email_customer' => 'required|email',
            'address_customer'=>'required',
        ];
    }
    public function messages():array
    {
        return[
            'name_customer.required' => 'Vui lòng không bỏ trống tên khách hàng',
            'phone_customer.required' => 'Vui lòng không bỏ trống thông tin số điện thoại',
            'phone_customer.unique' => 'Số điện thoại đã tồn tại cho một khách hàng khác',
            'phone_customer.digits' => 'Số điện thoại phải là số có 10 chữ số',
            'address_customer.required'=>'Vui lòng không để trống địa chỉ',
        ];
    }
}
