@extends('admin.layouts.master')
@section('title', 'تعديل خطة')
@section('content')
<div class="container-fluid">
    <h2 class="h4 mb-3">تعديل بيانات الخطة</h2>
    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('plans.update', $plan) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.plans._form')
            </form>
        </div>
    </div>
</div>
@endsection