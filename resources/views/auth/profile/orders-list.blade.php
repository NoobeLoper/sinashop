@extends('auth.profile.main')

@section('cardBody')
    <table class="table">
        <tbody>
        <tr>
            <th>شماره سفارش</th>
            <th>تاریخ ثبت</th>
            <th>وضعیت سفارش</th>
            <th>کد رهگیری پستی</th>
            <th>اقدامات</th>
        </tr>

        @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ jdate($order->created_at)->format('%d %B %Y') }}</td>
                @switch($order->status)
                    @case('unpaid')
                        <td class="text-danger">{{ 'پرداخت نشده' }}</td>
                        @break
                    @case('paid')
                        <td class="text-success">{{ 'پرداخت شده' }}</td>
                        @break
                    @case('posted')
                        <td class="text-warning">{{ 'ارسال شده' }}</td>
                        @break
                    @case('canceled')
                        <td class="text-danger">{{ 'سفارش لغو شد' }}</td>
                        @break
                    @case('received')
                        <td class="text-info">{{ 'سفارش رسید' }}</td>
                        @break
                    @case('preparing')
                        <td class="text-muted">{{ 'آماده سازی برای ارسال' }}</td>
                        @break
                @endswitch

                <td>{{ $order->tracking_serial ?? 'هنوز ثبت نشده' }}</td>
                <td>
                    @if ($order->status == 'unpaid')
                        <a href="{{ route('profile.order.show', $order->id) }}" type="button" class="btn btn-sm btn-primary">جزیات سفارش و پرداخت</a>
                    @else
                        <a href="{{ route('profile.order.show', $order->id) }}" type="button" class="btn btn-sm btn-info">جزیات سفارش</a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $orders->links() }}
@endsection
