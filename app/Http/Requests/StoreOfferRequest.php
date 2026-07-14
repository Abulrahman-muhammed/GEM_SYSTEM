<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\DiscountType;
use Illuminate\Validation\Rule;


class StoreOfferRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255'],
            'discount_type'  => ['required', Rule::in(array_column(DiscountType::cases(), 'value'))],
            'discount_value' => [
                'required',
                'numeric',
                'min:0',
                Rule::when(
                    $this->input('discount_type') === DiscountType::PERCENTAGE->value,
                    ['max:100']
                ),
            ],
            'start_date'     => ['required', 'date'],
            'end_date'       => ['required', 'date', 'after_or_equal:start_date'],
            'description'    => ['nullable', 'string'],
            'status'         => ['nullable', 'boolean'],
        ];
    }
 
    /**
     * رسائل الأخطاء بالعربي.
     */
    public function messages(): array
    {
        return [
            'name.required'                 => 'اسم العرض مطلوب.',
            'discount_type.required'        => 'نوع الخصم مطلوب.',
            'discount_type.in'               => 'نوع الخصم غير صحيح.',
            'discount_value.required'       => 'قيمة الخصم مطلوبة.',
            'discount_value.numeric'        => 'قيمة الخصم يجب أن تكون رقمًا.',
            'discount_value.min'            => 'قيمة الخصم لا يمكن أن تكون بالسالب.',
            'discount_value.max'            => 'نسبة الخصم لا يمكن أن تتجاوز 100%.',
            'start_date.required'           => 'تاريخ بداية العرض مطلوب.',
            'end_date.required'             => 'تاريخ نهاية العرض مطلوب.',
            'end_date.after_or_equal'       => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية.',
        ];
    }
}
