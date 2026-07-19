<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
        'gym_name' => ['required'],
        'owner_name' => ['required'],
        'phone' => ['nullable'],
        'email' => ['nullable'],
        'address' => ['nullable'],
        'timezone' => ['required'],
        'facebook_url' => ['nullable'],
        'instagram_url' => ['nullable'],
        'whatsapp_number' => ['nullable'],
        'logo' => ['nullable','image','max:10240','mimes:jpeg,png,jpg,gif,svg'],
        ];
    }

        public function messages(): array
    {
        return [
            'gym_name.required' => 'اسم الجيم مطلوب',
            'owner_name.required' => 'اسم المالك مطلوب',
            'logo.image' => 'الشعار يجب أن يكون صورة',
            'logo.max' => 'الشعار يجب أن يكون بحجم 10 ميجابايت',
            'logo.mimes' => 'الشعار يجب أن يكون بصيغة jpeg,png,jpg,gif,svg',
            'timezone.required' => 'المنطقة الزمنية مطلوبة',
        ];
    }
}
