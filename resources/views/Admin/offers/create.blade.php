@extends('admin.layouts.master')
@section('title', 'إضافة عرض')
@section('content')
<div class="container-fluid">
    <h2 class="h4 mb-3">إضافة عرض جديد</h2>
    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('offers.store') }}" method="POST">
                @csrf
                @include('admin.offers._form')
            </form>
        </div>
    </div>
</div>
@endsection