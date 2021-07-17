<?php

namespace App\Http\Controllers;

use App\Order;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Auth::user()->orders()->latest('created_at')->paginate(10);
        return view('auth.profile.orders-list', compact('orders'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        // return $order->products;
        $this->authorize('view', $order);
        return view('auth.profile.order-show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    public function payment(Order $order)
    {
        $this->authorize('view', $order);

        //Payment Start
        $token = config('services.payping.token');
        $res_number = Str::random();
        $args = [
            //! Put "amount" => $order->price For Production Mode
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
        //Payment End

        return redirect($payment->getPayUrl());
        alert()->success('payment successfully completed', 'success');

    }

}
