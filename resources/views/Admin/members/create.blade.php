@extends('admin.layouts.master')
@section('title', 'إضافة عضو')
@section('content')
<div class="container-fluid">
    <h2 class="h4 mb-3">إضافة عضو جديد</h2>
    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.members._form')
            </form>
        </div>
    </div>
</div>
@endsection