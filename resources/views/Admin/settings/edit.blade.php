@extends('admin.layouts.master')
@section('title', 'إعدادات النظام')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">إعدادات النظام العامة</h2>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-grid-2">

                    {{-- اسم الجيم --}}
                    <div class="form-group">
                        <label for="gym_name">
                            <i data-feather="home" class="icon-sm"></i>
                            اسم الجيم
                        </label>
                        <input type="text"
                               name="gym_name"
                               id="gym_name"
                               class="form-control @error('gym_name') is-invalid @enderror"
                               value="{{ old('gym_name', $settings->gym_name) }}"
                               required>
                        @error('gym_name')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- اسم المالك --}}
                    <div class="form-group">
                        <label for="owner_name">
                            <i data-feather="user" class="icon-sm"></i>
                            اسم المالك
                        </label>
                        <input type="text"
                               name="owner_name"
                               id="owner_name"
                               class="form-control @error('owner_name') is-invalid @enderror"
                               value="{{ old('owner_name', $settings->owner_name) }}"
                               required>
                        @error('owner_name')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- رقم الهاتف --}}
                    <div class="form-group">
                        <label for="phone">
                            <i data-feather="phone" class="icon-sm"></i>
                            رقم الهاتف
                        </label>
                        <input type="text"
                               name="phone"
                               id="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $settings->phone) }}"
                               required>
                        @error('phone')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- البريد الإلكتروني --}}
                    <div class="form-group">
                        <label for="email">
                            <i data-feather="mail" class="icon-sm"></i>
                            البريد الإلكتروني
                        </label>
                        <input type="email"
                               name="email"
                               id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $settings->email) }}"
                               required>
                        @error('email')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- المنطقة الزمنية --}}
                    <div class="form-group">
                        <label for="timezone">
                            <i data-feather="clock" class="icon-sm"></i>
                            المنطقة الزمنية
                        </label>
                        <select name="timezone"
                                id="timezone"
                                class="form-control @error('timezone') is-invalid @enderror select2"
                                required>
                            @foreach(timezone_identifiers_list() as $tz)
                                <option value="{{ $tz }}" {{ old('timezone', $settings->timezone) === $tz ? 'selected' : '' }}>
                                    {{ $tz }}
                                </option>
                            @endforeach
                        </select>
                        @error('timezone')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- رقم الواتساب --}}
                    <div class="form-group">
                        <label for="whatsapp_number">
                            <i data-feather="message-circle" class="icon-sm"></i>
                            رقم الواتساب
                        </label>
                        <input type="text"
                               name="whatsapp_number"
                               id="whatsapp_number"
                               class="form-control @error('whatsapp_number') is-invalid @enderror"
                               value="{{ old('whatsapp_number', $settings->whatsapp_number) }}"
                               required>
                        @error('whatsapp_number')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- رابط الفيسبوك --}}
                    <div class="form-group">
                        <label for="facebook_url">
                            <i data-feather="facebook" class="icon-sm"></i>
                            رابط الفيسبوك
                        </label>
                        <input type="url"
                               name="facebook_url"
                               id="facebook_url"
                               class="form-control @error('facebook_url') is-invalid @enderror"
                               value="{{ old('facebook_url', $settings->facebook_url) }}">
                        @error('facebook_url')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- رابط الانستجرام --}}
                    <div class="form-group">
                        <label for="instagram_url">
                            <i data-feather="instagram" class="icon-sm"></i>
                            رابط الانستجرام
                        </label>
                        <input type="url"
                               name="instagram_url"
                               id="instagram_url"
                               class="form-control @error('instagram_url') is-invalid @enderror"
                               value="{{ old('instagram_url', $settings->instagram_url) }}">
                        @error('instagram_url')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- العنوان (يمتد على العرض الكامل) --}}
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="address">
                            <i data-feather="map-pin" class="icon-sm"></i>
                            العنوان
                        </label>
                        <textarea name="address"
                                  id="address"
                                  rows="2"
                                  class="form-control @error('address') is-invalid @enderror"
                                  required>{{ old('address', $settings->address) }}</textarea>
                        @error('address')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- اللوجو (يمتد على العرض الكامل) --}}
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="logo">
                            <i data-feather="image" class="icon-sm"></i>
                            لوجو الجيم
                        </label>

                        @if($settings->logo)
                            <div class="mb-2">
                                <img src="{{ Storage::url($settings->logo) }}"
                                     alt="Current Logo"
                                     style="max-height: 80px; border-radius: 8px;">
                            </div>
                        @endif

                        <input type="file"
                               name="logo"
                               id="logo"
                               accept="image/*"
                               class="form-control @error('logo') is-invalid @enderror">
                        @error('logo')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="save" class="icon-sm"></i>
                        حفظ التعديلات
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    feather.replace();
</script>
@endpush