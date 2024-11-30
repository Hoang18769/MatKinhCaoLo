<?php

namespace App\Http\Requests\Size;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSizeRequest extends FormRequest
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
            'desc_size' => ['required',
            Rule::unique('sizes', 'desc_size')->ignore($this->id_size, 'id_size')],
        ];
    }
    public function messages():array
    {
        return[
            'desc_size.unique' => 'Kích thước này đã tồn tại.',
            'desc_size.required' => 'Vui lòng nhập tên kích thước.',
        ];
    }
}
