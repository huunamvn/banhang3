<?php

namespace App\Http\Controllers;

use App\Models\catelogue;
use Illuminate\Http\Request;

class CatelogueController extends Controller
{
    const PATH_VIEW = 'admin.catelogues.';
    /**
     * Display a listing of the resource.
     * 
     */
    public function __construct()
{
    // examples:
    $this->middleware('permission:publish Category|create Category|edit Category|delete Category',['only'=>['index','show']]);
    $this->middleware('permission:create Category',['only'=>['create','store']]);
    $this->middleware('permission:edit Category',['only'=>['edit','update']]);
    $this->middleware('permission:delete Category',['only'=>['destroy']]);
}
    public function index()
    {
        $data = catelogue::query()->latest('id')->paginate(5);

        return view(self::PATH_VIEW . __FUNCTION__, compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view(self::PATH_VIEW . __FUNCTION__);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //  if ($request->isMethod('POST')){

        //     Catelogue::query()->create($_POST);
        //     return redirect()->route('admin.catelogue.index');
        //  }
        if ($request->isMethod('POST')) {
            $request->validate(
                [
                    'name' => 'required|max:255',
                ],
                [
                    'name.required' => 'Name phẩm bắt buộc điền',
                ]
            );
            $data = $request->except('_token');
            // dd($data);
            Catelogue::query()->create($data);
            return redirect()->route('admin.catelogue.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $model = catelogue::query()->findOrFail($id);
        // dd($model);
        return view(self::PATH_VIEW . __FUNCTION__, compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $model = catelogue::query()->findOrFail($id);

        return view(self::PATH_VIEW . __FUNCTION__, compact('model'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)

    {
        $model = catelogue::query()->findOrFail($id);
        if ($request->isMethod('POST')) {
            $request->validate(
                [
                    'name' => "required|max:255|"
                ],
                [
                    'name.required' => 'Name phẩm bắt buộc điền',
                ]
            );
            $data = $request->except('_token');
            $model->update($data);
            return redirect()->route('admin.catelogue.edit');
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = catelogue::query()->findOrFail($id);
        $model->delete();
        return back();
    }
}
