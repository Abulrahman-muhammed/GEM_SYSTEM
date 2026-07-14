<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePlanRequest extends FormRequest
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
            'price'          => ['required', 'numeric', 'min:0'],
            'duration_days'  => ['required', 'integer', 'min:1'],
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
            'name.required'          => 'اسم الخطة مطلوب.',
            'price.required'         => 'سعر الخطة مطلوب.',
            'price.numeric'          => 'السعر يجب أن يكون رقمًا.',
            'price.min'              => 'السعر لا يمكن أن يكون بالسالب.',
            'duration_days.required' => 'مدة الاشتراك مطلوبة.',
            'duration_days.integer'  => 'المدة يجب أن تكون عدد أيام صحيح.',
            'duration_days.min'      => 'المدة يجب أن تكون يوم واحد على الأقل.',
        ];
    }
}
