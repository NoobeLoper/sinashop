<?php

namespace App\Http\Controllers;

use App\Helpers\Cart\Cart;
use App\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $this->seo()->setTitle('سبد خرید')->setDescription('سبد خرید فروشگاه سینا');
        return view('front.cart');
    }

    public function addToCart(Product $product)
    {
        if( Cart::has($product)){
            Cart::update($product, 1);
        }else {
            Cart::put(
                [
                    'quantity' => 1,
                ],
                $product
            );
        }

        return redirect()->route('cart.index');
    }

    public function quantityChange(Request $request)
    {
        {
            $data = $request->validate([
               'quantity' => 'required',
               'id' => 'required',
               //'cart' => 'required'
            ]);

            if( Cart::has($data['id']) ) {
                // $product = Cart::get($data['id'])['product'];
                // if ($product['quantity'] <= $product['inventory'])

                Cart::update($data['id'] , [
                   'quantity' => $data['quantity']
                ]);

                return response(['status' => 'success']);
            }

            return response(['status' => 'error'] , 404);
        }
    }

    public function delete($id)
    {
        Cart::delete($id);

        alert()->success('محصول مورد نظر از سبد خرید حذف شد.', 'انجام شد');
        return back();
    }
}
