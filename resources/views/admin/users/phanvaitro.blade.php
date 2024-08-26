@extends('admin.layouts.master')

@section('title')
    Danh sách User
@endsection

@include('admin.layouts.partials.footer')

@section('content')
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Phân vai trò user : {{ $user->name }} </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <form action="{{ url('admin/insert_roles', [$user->id]) }}" method="POST">
                        @csrf
                        @foreach ($role as $key => $r)
                            @if (isset($all_column_roles))
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input"
                                        {{ $r->id == $all_column_roles->id ? 'checked' : '' }} name="role"
                                        id="{{ $r->id }}" value="{{ $r->name }}">
                                    <label class="form-check-label" for="{{ $r->id }}">{{ $r->name }}</label>
                                </div>
                            @else
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="role" id="{{ $r->id }}"
                                        value="{{ $r->name }}">
                                    <label class="form-check-label" for="{{ $r->id }}">{{ $r->name }}</label>
                                </div>
                            @endif
                        @endforeach
                        <br>
                        <input type="submit" name="insertroles" value="Cấp vai trò" class="btn btn-success">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
