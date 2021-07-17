<?php

namespace Modules\Discount\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Modules\Discount\Entities\Discount;

class DiscountController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:create-discount')->only(['create', 'store']);
        $this->middleware('can:edit-discount')->only(['edit', 'update']);
        $this->middleware('can:delete-discount')->only(['destroy']);
        $this->middleware('can:show-discounts')->only(['index']);
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $discounts = Discount::latest()->paginate(20);
        return view('discount::admin.all', compact('discounts'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('discount::admin.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|unique:discounts,code',
            'percent' => 'required|integer|between:1,99',
            'users' => 'nullable|array|exists:users,id',
            'products' => 'nullable|array|exists:products,id',
            'categories' => 'nullable|array|exists:categories,id',
            'expired_at' => 'required'
        ]);

        $discount = Discount::create($data);

        if ($request->users) {
            $discount->users()->attach($data['users']);
        }
        if($request->products) {
            $discount->products()->attach($data['products']);
        }
        if($request->categories) {
            $discount->categories()->attach($data['categories']);
        }

        alert()->success('ثبت با موفقیت انجام شد.');
        return redirect(route('admin.discounts.index'))->with('success', 'ثبت کد تخفیف انجام شد.');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Discount $discount)
    {
        return view('discount::admin.edit', compact('discount'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Discount $discount)
    {
        $data = $request->validate([
            'code' => ['required', Rule::unique('discounts', 'code')->ignore($discount->id)],
            'percent' => 'required|integer|between:1,99',
            'users' => 'nullable|array|exists:users,id',
            'products' => 'nullable|array|exists:products,id',
            'categories' => 'nullable|array|exists:categories,id',
            'expired_at' => 'required'
        ]);

        $discount->update($data);

        isset($data['users'])
            ? $discount->users()->sync($data['users'])
            : $discount->users()->detach();

        isset($data['products'])
            ? $discount->products()->sync($data['products'])
            : $discount->products()->detach();

        isset($data['categories'])
            ? $discount->categories()->sync($data['categories'])
            : $discount->categories()->detach();

        alert()->success('ویرایش با موفقیت انجام شد.');
        return redirect(route('admin.discounts.index'))->with('success', 'ویرایش کد تخفیف انجام شد.');
    }

    /**
     * Remove the specified resource from storage.
     * @param  Modules\Discount\Entities\Discount $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discount $discount)
    {
        $discount->delete();
        alert()->success('حذف انجام شد');
        return back();
    }
}
