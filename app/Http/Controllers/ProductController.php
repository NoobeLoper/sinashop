<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // \SEO::setTtile('محصولات');
        $this->seo()
        ->setTitle('محصولات')
        ->setDescription('همه محصولات فروشگاه سینا')
        ->opengraph()
        ->addProperty('type', 'products');

        $products = Product::orderByDesc('id')->paginate(8);
        return view('front.products', compact('products'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $this->seo()->setTitle('محصولات')->setDescription(' جزیات محصول ' . $product->title . ' فروشگاه ');
        return view('front.products-show', compact('product'));
    }

}
