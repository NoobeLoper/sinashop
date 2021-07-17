<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:create-category')->only(['create', 'store']);
        $this->middleware('can:edit-category')->only(['edit', 'update']);
        $this->middleware('can:delete-category')->only(['destroy']);
        $this->middleware('can:show-categories')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::where('parent_id' , 0)->latest()->paginate(10);
        return view('admin.categories.all' , compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->parent_id) {
            $request->validate([
                'parent_id' => 'exists:categories,id',
            ]);
        }
        $request->validate([
            'name' => 'required|min:3',
        ]);
        Category::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id ?? 0,
        ]);
        alert()->success('دسته بندی ذخیره شد', 'انجام شد');
        return redirect(route('admin.categories.index'))->with('success', 'دسته بندی ذخیره شده است');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        if($request->parent_id) {
            $request->validate([
                'parent_id' => 'exists:categories,id'
            ]);
        }

        $request->validate([
            'name' => 'required|min:3'
        ]);

        $category->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id
        ]);
        alert()->success('ویرایش دسته بندی انجام شد');
        return redirect(route('admin.categories.index'))->with('success', 'ویرایش انجام شد.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if ($category->parent_id){
            alert()->error('ابتدا باید زیر دسته ها حذف شوند.');
            return back();
        }else {
            $category->delete();
            alert()->success('حذف دسته بندی انجام شد');
            return redirect(route('admin.categories.index'))->with('success', 'حذف انجام شد.');
        }
    }
}
