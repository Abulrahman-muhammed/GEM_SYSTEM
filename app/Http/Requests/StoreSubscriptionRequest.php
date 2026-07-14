<?php

namespace App\Http\Requests;

use App\Enums\PaymentMethod;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubscriptionRequest extends FormRequest
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
            'member_id'      => ['required', 'exists:members,id'],
            'plan_id'        => ['required', 'exists:plans,id'],
            'offer_id'       => ['nullable', 'exists:offers,id'],
            'start_date'     => ['required', 'date'],
            'payment_method' => ['nullable', Rule::in(array_column(PaymentMethod::cases(), 'value'))],
            'paid_amount'    => ['nullable', 'numeric', 'min:0'],
        ];
    }
 
    /**
     * رسائل الأخطاء بالعربي.
     */
    public function messages(): array
    {
        return [
            'member_id.required' => 'اختيار العضو مطلوب.',
            'member_id.exists'   => 'العضو المحدد غير موجود.',
            'plan_id.required'   => 'اختيار الخطة مطلوب.',
            'plan_id.exists'     => 'الخطة المحددة غير موجودة.',
            'offer_id.exists'    => 'العرض المحدد غير موجود.',
            'start_date.required' => 'تاريخ بداية الاشتراك مطلوب.',
            'paid_amount.numeric' => 'المبلغ المدفوع يجب أن يكون رقمًا.',
            'paid_amount.min'     => 'المبلغ المدفوع لا يمكن أن يكون بالسالب.',
        ];
    }
}
