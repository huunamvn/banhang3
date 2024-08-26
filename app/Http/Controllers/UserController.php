<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Laravel\Ui\Presets\React;
use Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function __construct()
{
    // examples:
    $this->middleware('permission:publish User|create User|edit User|delete User',['only'=>['index','show']]);
    $this->middleware('permission:Create User',['only'=>['insertPermission']]);
    $this->middleware('permission:edit User',['only'=>['edit','update']]);
    $this->middleware('permission:delete User',['only'=>['destroy']]);
    $this->middleware('permission:insert_permission',['only'=>['insert_permission']]);
    $this->middleware('permission:admin',['only'=>['index']]);
    $this->middleware('permission:insertPermission',['only'=>['insertPermission']]);
    $this->middleware('permission:insert_roles',['only'=>['insert_roles']]);
    
    // $this->middleware('permission:insert_roles',['only'=>['insert_roles']]);

}
    public function index(Role $role, Permission $permission)
    {
        // $role = Role::create(['name' => 'admin']);
        // $permission = Permission::create(['name' => 'create Category']);
        // $role->givePermissionTo($permission);
        // $role = Role::find(7);
        // $permission = Permission::find(1);

        // $user =User::find(7);
        // $user->getPermissionsViaRoles(); lấy ra quyền user
        // dd($user);
        // if(  $user->hasDirectPermission('adddanhmuc')){ check quyền [check nhiều quyền]
        //     echo'có';
        // }else{
        //     echo 'khong có';
        // }

        // echo auth()->user()->id;  
        // $user=User::find(5);
        // $user->assignRole('user2');

        // if($user->hasRole(['admin2','admin4'])) { check nhiều vai trò phải sử dụng mảng 
        //     echo'có những quyền trên'; 
        //  }else{
        //      echo 'không';
        //  }

        // if($user->hasAnyRole(['admin2','admin4'])) { check nhiều vai trò có hay không
        //    echo'có những quyền trên'; 
        // }else{
        //     echo 'không';
        // }


        // $user->syncPermissions(['adddanhmuc']);add quyền theo id user
        // auth()->user()->syncPermissions(['publish Category','edit Category','create Category']);

        // $role->givePermissionTo($permission); add quyền 
        // $permission->assignRole($role);
        // $role->revokePermissionTo($permission); Delete quyền
        // $permission->removeRole($role);

        // auth()->user()->assignRole() cấp quyền
        $user = User::with('roles', 'permissions')->get();

        return view('admin.users.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        User::create($request->all());
        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function phanvaitro($id)
    {
        $user = User::find($id);
        $all_column_roles = $user->roles->first();
        $permission = Permission::orderBy('id', 'DESC')->get();
        $role = Role::orderBy('id', 'DESC')->get();
        return view('admin.users.phanvaitro', compact('user', 'role', 'all_column_roles', 'permission'));
    }
    public function phanquyen($id)
    {
        
        $user = User::find($id);
        $permission = Permission::orderBy('id', 'DESC')->get();

        $name_roles = $user->roles->first()->name;

        $get_permission_viaroles = $user->getPermissionsViaRoles();
        // dd(  $get_permission_viaroles);
        return view('admin.users.phanquyen', compact('user', 'name_roles', 'permission', 'get_permission_viaroles'));
    }
    public function insert_roles(Request $request, $id)
    {
        $data = $request->all();
        // cấp vai trò
        $user = User::find($id);
        $user->syncRoles($data['role']);
        $role_id = $user->roles->first()->id;
        return redirect()->back()->with('thong bao', 'thêm thành công');
    }
    public function insert_permission(Request $request, $id)
    {
        $data = $request->all();
        $user = User::find($id);
        $role_id = $user->roles->first()->id;

        // cấp quyền c
        $role = Role::find($role_id);
        $role->syncPermissions($data['permission']);
        // dd($data['permission']);

        return redirect()->route('admin.users.index');
    }
    public function  insertPermission(Request $request)
    {
        $data = $request->all();
        $permission = new Permission();
        $permission->name = $data['permission'];
        $permission->save();
        return redirect()->back()->with('thong bao', 'thêm thành công');
    }
    public function impersonate($id)
    {
        $user = User::find($id);
        if ($user) {
            Session::put('impersonate', $user->id);
        }
        return redirect('/users');
    }
}
