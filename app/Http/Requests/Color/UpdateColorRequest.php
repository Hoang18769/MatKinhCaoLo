<?php

namespace App\Http\Requests\Color;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateColorRequest extends FormRequest
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
            'desc_color' =>[ 'required',
            Rule::unique('colors', 'desc_color')->ignore($this->id_color, 'id_color')]
        ];
    }
    public function messages():array
    {
        return[
            'desc_color.unique' => 'Màu sắc này đã tồn tại.',
            'desc_color.required' => "$this->desc_color Vui lòng nhập tên màu sắc.",
        ];
    }
}
