<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->seo()->setTitle('دسته بندی ها')->setDescription('همه دسته بندی های فروشگاه سینا');
        $categories = Category::orderByDesc('id')->paginate(10);
        return view('front.categories', compact('categories'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $this->seo()->setTitle('دسته بندی')->setDescription(' دسته بندی ' . $category->name . ' فروشگاه');
        return view('front.categories-show', compact('category'));
    }
}
