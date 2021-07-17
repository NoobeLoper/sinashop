<?php

namespace App\Http\Controllers\Admin;

use App\Attribute;
use App\Http\Controllers\Controller;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:create-product')->only(['create', 'store']);
        $this->middleware('can:edit-product')->only(['edit', 'update']);
        $this->middleware('can:delete-product')->only(['destroy']);
        $this->middleware('can:show-products')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::query();
        if ($keyword = request('search')) {
            $products->where('title', 'LIKE', "%{$keyword}%")->orWhere('id', $keyword);
        }

        $products = $products->latest()->paginate(10);

        return view('admin.products.all', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:512'],
            'description' => ['required'],
            'price' => ['required', 'integer'],
            'image' => ['required', 'max:512'],
            'inventory' => ['required', 'integer'],
            'categories' => ['required', 'array'],
            'attributes' => ['array'],
        ]);

        // $file = $data['image']; <~~ in , Ya khate paiin

        //? 4 KHate Paiin, Pish az ezafe kardae File-Manager, Baraye Save kardane Input image bud
        // $file = $request->file('image');
        // $destinationPath = '/images/' . $request->input('title') . '/';
        // $file->move( public_path($destinationPath), $file->getClientOriginalName());

        // $data['image'] = $destinationPath . $file->getClientOriginalName();


        // auth()->user()->products()->create($data);
        $product = Auth::user()->products()->create($data);
        $product->categories()->sync($data['categories']);

        if(isset($data['attributes']))
            $this->attachAttributesToProduct($product, $data);

        alert()->success('ثبت با موفقیت انجام شد.', 'Message');
        return redirect(route('admin.products.index'))->with('success', 'ثبت کاربر انجام شد.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'title' => ['required'],
            'description' => ['required'],
            'price' => ['required'],
            'inventory' => ['required'],
            'image' => ['required'],
            'categories' => ['required', 'array'],
            'attributes' => ['array'],
        ]);


        //?Codes Before File-Manager (Uploading File/Image)
        // if($request->file('image')){
        //     $request->validate([
        //         'image' => 'required|image|mimes:png,jpg|max:512'
        //     ]);
        // }

        // if(File::exists(public_path($product->image)))
        // {
        //     File::delete(public_path($product->image));
        // }

        // $file = $request->file('image');
        // $destinationPath = '/images/' . $request->input('title') . '/';
        // $file->move( public_path($destinationPath), $file->getClientOriginalName());

        // $data['image'] = $destinationPath . $file->getClientOriginalName();

        //?Code For Storing Image Into Storaeg Path -- $data['image'] Must Be Commented -- in Blade too!

        //Storage::putFile('images', $request->file('image'));
        //For Give Name To File:
        //Storage::putFile('images', $request->file('image'), $request->file('name')->getClientOriginalName());

        $product->update($data);
        $product->categories()->sync($data['categories']);

        $product->attributes()->detach();

        if(isset($data['attributes']))
            $this->attachAttributesToProduct($product, $data);

        alert()->success('ثبت با موفقیت انجام شد.', 'Message');
        return redirect(route('admin.products.index'))->with('success', 'ثبت کاربر انجام شد.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        alert()->warning('Your Product has Been Deleted successfully', 'Deleted')->persistent('FFFFine');
        return redirect(route('admin.products.index'))->with('success', 'کاربر حذف شد.');
    }

    /**
     * @param Product $product
     * @param array $data
     */
    protected function attachAttributesToProduct(Product $product, array $data): void
    {
        $attributes = collect($data['attributes']);
        $attributes->each(function ($item) use ($product) {
            if (is_null($item['name']) || is_null($item['value'])) return;

            $attr = Attribute::firstOrCreate(
                ['name' => $item['name']]
            );

            $attr_value = $attr->values()->firstOrCreate(
                ['value' => $item['value']]
            );

            $product->attributes()->attach($attr->id, ['value_id' => $attr_value->id]);
        });
    }
}
