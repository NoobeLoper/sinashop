@extends('layouts.app')

@section('content')
    <table class="table">
        <tbody>
        <tr>
            <th>شناسه محصول</th>
            <th>عنوان محصول</th>
            <th>قیمت واحد</th>
            <th>تعداد سفارش</th>
            <th>هزینه نهایی</th>
            <th>کد مرسوله</th>
            <th>اقدامات</th>
            <th>برگشت</th>
        </tr>
        @foreach ($order->products as $item)
            @php
                $total = isset($total) ? $total  : 0;
            @endphp
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->title }}</td>
                <td>{{ $item->price }}</td>
                <td>{{ $item->pivot->quantity }}</td>
                <td>{{ $item->price * $item->pivot->quantity }}</td>
                @php($total += $item->price * $item->pivot->quantity)
                <td>{{ $order->tracking_serial ?? 'هنوز ثبت نشده' }}</td>
        @endforeach
            <td>
                @if ($order->status == 'unpaid')
                    <a href="{{ route('profile.order.payment', $order->id) }}" class="btn btn-sm btn-success" >پرداخت</a>
                @else
                    <a href="#" class="btn btn-sm btn-dark disabled"> پرداخت انجام شده است</a>
                @endif
            </td>
            <td><a href="/profile/orders" class="btn btn-sm btn-warning">بازگشت</a></td>

        </tr>


        </tbody>
    </table>
    @if ($order->status == 'unpaid' )
        <div class="container">
            <div class="row float-left">
                <div class="col bg-info">
                    قابل پرداخت: {{ $total }}
                </div>
            </div>
        </div>
    @else
    <div class="container">
        <div class="row float-left">
            <div class="col bg-dark">
               پرداخت شده : {{ $total }}
            </div>
        </div>
    </div>
    @endif

@endsection
