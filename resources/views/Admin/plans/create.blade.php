@extends('admin.layouts.master')
@section('title', 'إضافة خطة')
@section('content')
<div class="container-fluid">
    <h2 class="h4 mb-3">إضافة خطة اشتراك جديدة</h2>
    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('plans.store') }}" method="POST">
                @csrf
                @include('admin.plans._form')
            </form>
        </div>
    </div>
</div>
@endsection