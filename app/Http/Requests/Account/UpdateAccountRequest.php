<?php

namespace App\Http\Requests\Account;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
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
            'name_account' => 'required',
            'email_account' => ['required','email', Rule::unique('accounts','email_account')->ignore($this->route('account'), 'id_account')],
            'password_account' => 'required|nullable',
        ];
    }
    public function messages():array
    {
        return[
            'email_account'=>'Tài khoản email đã được sử dụng.',
            'password_account.required' => 'Vui lòng điền mật khẩu',
        ];
    }
}
