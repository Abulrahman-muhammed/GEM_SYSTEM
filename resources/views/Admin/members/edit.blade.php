@extends('admin.layouts.master')
@section('title', 'تعديل عضو')
@section('content')
<div class="container-fluid">
    <h2 class="h4 mb-3">تعديل بيانات العضو</h2>
    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('members.update', $member) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.members._form')
            </form>
        </div>
    </div>
</div>
@endsection