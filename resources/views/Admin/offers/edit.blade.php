@extends('admin.layouts.master')
@section('title', 'تعديل عرض')
@section('content')
<div class="container-fluid">
    <h2 class="h4 mb-3">تعديل بيانات العرض</h2>
    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('offers.update', $offer) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.offers._form')
            </form>
        </div>
    </div>
</div>
@endsection