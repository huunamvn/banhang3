@extends('admin.layouts.master')
@section('title')
   Thêm mới user
@endsection
@include('admin.layouts.partials.footer')
@section('content')
<div class="col-lg-12">
    <div class="white_card card_height_100 mb_30">
        <div class="white_card_header">
            <div class="box_header m-0">
                <div class="main-title">
                    <h1 class="m-0">Thêm user</h1>
                </div>
            </div>
        <h1>csadjsad</h1>
<form action="{{route('admin.users.store')}}" method="POST">
    @csrf
    <div class="mb-3 mt-3">
        <label for="name" class="form-label">Name:</label>
        <input type="text" class="form-control" id="name" placeholder="Enter name" name="name">
    </div>
    <div class="mb-3 mt-3">
        <label for="email" class="form-label">Email:</label>
        <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
    </div>
    <div class="mb-3 mt-3">
        <label for="password" class="form-label">Password:</label>
        <input type="text" class="form-control" id="password" placeholder="Enter password" name="password">
    </div>
    <div class="mb-3 mt-3">
        <label for="password" class="form-label">Password:</label>
        <input type="text" class="form-control" id="confirm_password" placeholder="Enter confirm_password"
            name="confirm_password">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>
</div>

@endsection


   


