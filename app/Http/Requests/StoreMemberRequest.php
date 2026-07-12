<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Gender;
use Carbon\Carbon;
use Illuminate\Validation\Rules\Enum;
class StoreMemberRequest extends FormRequest
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
            'full_name' => 'required|string|max:100',
            'phone' => 'required|unique:members,phone|string|max:20',
            'gender' => [
                'required',
                new Enum(Gender::class),
            ],
            'birth_date' => 'required|date|before:'.Carbon::now(),
            'address' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|boolean',
            'notes' => 'nullable|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'full_name.required'     => 'اسم العضو مطلوب.',
            'phone.required'    => 'رقم الهاتف مطلوب.',
            'phone.unique'      => 'رقم الهاتف موجود بالفعل.',
            'gender.required'   => 'الجنس مطلوب.',
            'birth_date.required' => 'تاريخ الميلاد مطلوب.',
            'address.required'  => 'العنوان مطلوب.',
            'full_name.string'       => 'يجب أن يكون اسم العضو نص.',
            'phone.string'      => 'يجب أن يكون رقم الهاتف نص.',
            'birth_date.date'     => 'يجب أن يكون تاريخ الميلاد تاريخًا.', 
            'birth_date.before'   => 'يجب أن يكون تاريخ الميلاد قبل تاريخ اليوم.',
            'address.string'    => 'يجب أن يكون العنوان نص.',
            'status.required'   => 'الحالة مطلوبة.',
            'status.boolean'    => 'يجب أن تكون الحالة قيمة منطقية.',
            'notes.string'      => 'يجب أن تكون الملاحظات نصًا.',

            'full_name.max'          => 'لا يمكن أن يتجاوز اسم العضو 100 حرف.',
            'phone.max'         => 'لا يمكن أن يتجاوز رقم الهاتف 20 حرفًا.',
            'address.max'       => 'لا يمكن أن يتجاوز العنوان 255 حرفًا.',
            'notes.max'         => 'لا يمكن أن تتجاوز الملاحظات 255 حرفًا.',
            'photo.image'       => 'يجب أن تكون الصورة ملفًا من نوع صورة.',
            'photo.mimes'       => 'يجب أن تكون الصورة ملفًا من نوع: jpg, jpeg, png.',
            'photo.max'         => 'لا يمكن أن يتجاوز حجم الصورة 2048 كيلوبايت.',
        ];
    }
}
