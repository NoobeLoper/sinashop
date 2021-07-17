<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Helpers\Cart\Cart;
use App\UsedDiscount;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{

    //Start Preparing Order Cart

    public function payment()
    {
        $cartItems = Cart::all();
        if ($cartItems->count() > 0) {

            $price = $cartItems->sum(function($cart){
                return $cart['discount_percent'] == 0
                    ? $cart['product']->price * $cart['quantity']
                    : ($cart['product']->price - ($cart['product']->price * $cart['discount_percent']) ) * $cart['quantity'];
            });

            $orderItems = $cartItems->mapWithKeys(function($cart){
                return [$cart['product']->id => [ 'quantity' => $cart['quantity']]];
            });


            $order = auth()->user()->orders()->create([
                'status' => 'unpaid',
                'price' => $price
            ]);
            //     //?
            // dd($cartItems->each(function ($item, $key){
            //     return $key->id;
            // }));
            //     //?
            $order->products()->attach($orderItems);
            //Line 43-47 For Test - Test Done And Working (Line-75)
            // if (Cart::getDiscount()) {
            //     UsedDiscount::create([
            //         'user_id' => auth()->user()->id,
            //         'discount_code' => Cart::getDiscount()->code
            //     ]);
            // }
            //End Of Preparing Order Cart

            //Payment Start
            $token = config('services.payping.token');
            $res_number = Str::random();
            $args = [
                //! Put "amount" => $price For Production Mode
                "amount" => 1000,
                "payerName" => auth()->user()->name,
                "returnUrl" => route('payment.callback'),
                "clientRefId" => $res_number,
            ];

            $payment = new \PayPing\Payment($token);

            try {
                $payment->pay($args);
            } catch (\Exception $e) {
                throw $e;
            }
            // echo $payment->getPayUrl();

            $order->payments()->create([
                'resnumber' => $res_number,
            ]);

            if (Cart::getDiscount()) {
                UsedDiscount::create([
                    'user_id' => auth()->user()->id,
                    'discount_code' => Cart::getDiscount()->code
                ]);
            }
            //Payment End

            $cartItems->flush();

            return redirect($payment->getPayUrl());
            alert()->success('payment successfully completed', 'success');
        }

        alert()->error('سبد شما خالی است', 'خطا');
        return back();
    }

    public function callback(Request $request)
    {
        $payment = Payment::where('resnumber', $request->clientrefid)->firstOrFail();

        $token = config('services.payping.token');

        $payping = new \PayPing\Payment($token);

        try {
            //! $payment->price Instead of 1000 in Production Mode
            if($payping->verify($request->refid, 1000)){
                $payment->update([
                    'status' => 1
                ]);

                $payment->order()->update([
                    'status' => 'paid'
                ]);

                 alert()->success('پرداخت شما موفق بود');
                 return redirect('/products');
            }else{
                 alert()->error('پرداخت شما تایید نشد');
                 return redirect('/products');
            }
        } catch (\Exception $e) {
            $errors = collect(json_decode($e->getMessage() , true));

             alert()->error($errors->first());
             return redirect('/products');

            }
        }
}
