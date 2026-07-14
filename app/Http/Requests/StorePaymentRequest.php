<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\PaymentMethod;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
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
            'subscription_id' => ['required', 'exists:subscriptions,id'],
            'amount'          => ['required', 'numeric', 'min:0.01'],
            'method'          => ['required', Rule::in(array_column(PaymentMethod::cases(), 'value'))],
            'payment_date'    => ['required', 'date'],
            'notes'           => ['nullable', 'string'],
        ];
    }
 
    /**
     * رسائل الأخطاء بالعربي.
     */
    public function messages(): array
    {
        return [
            'subscription_id.required' => 'اختيار الاشتراك مطلوب.',
            'subscription_id.exists'   => 'الاشتراك المحدد غير موجود.',
            'amount.required'          => 'قيمة الدفعة مطلوبة.',
            'amount.numeric'           => 'قيمة الدفعة يجب أن تكون رقمًا.',
            'amount.min'               => 'قيمة الدفعة يجب أن تكون أكبر من صفر.',
            'method.required'          => 'طريقة الدفع مطلوبة.',
            'payment_date.required'    => 'تاريخ الدفعة مطلوب.',
        ];
    }
}
