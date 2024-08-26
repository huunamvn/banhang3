@extends('admin.layouts.master')

@section('title')
    Danh sách User
@endsection
@include('admin.layouts.partials.footer')
@section('content')
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Danh Sách User</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Password</th>
                                <th scope="col">Vai trò</th>
                                <th scope="col">Quyền (Permission)</th>
                                <th scope="col">Quản lý</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user as $key => $u)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $u->name }}</td>
                                    <td>{{ $u->email }}</td>
                                    <!-- Hide the password field for security -->
                                    <td>******</td>
                                    <td>
                                        @foreach($u->roles as $key => $role)
                                        {{$role->name}}
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($role->permissions as $key => $per)
                                        <span class="badge bg-success">{{$per->name}}</span> 
                                        @endforeach
                                    </td>
                                  @can('admin')
                                    <td>
                                        <a href="{{ url('admin/phanvaitro/'. $u->id) }}" class="btn btn-success btn-sm mb-1">Phân vai trò</a>
                                        <a href="{{ url('admin/phanquyen/'. $u->id) }}" class="btn btn-info btn-sm mb-1">Phân Quyền</a>
                                        <a href="{{ url('admin/impersonate/users/'. $u->id) }}" class="btn btn-danger btn-sm mb-1">Chuyển quyền nhanh</a>
                                    </td>           
                                </tr>
                                @endcan
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
