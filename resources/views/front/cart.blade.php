@extends('layouts.app')

@section('script')
    <script>
        //Cancel Handle
        function confirmCancel(e) {
                return swal({
                    text: "آیا از لغو خرید و بازگشت به صفحه محصولات مطمئن هستید؟",
                    icon: "warning",

                    buttons: ["نه اشتباه شد", "بله لغو کن"]
                })
                .then((willDelete) => {
                    if (willDelete) {
                        window.location.replace("/products");
                        session.confirmCancel
                    }else{
                        swal("خیلی هم خوب", {
                            button: false,
                            timer: 500,
                        });
                    }
                });
                return false;
        }

        //Remove Item Handle
        function confirmDelete(e) {
                var url = $(e).data('url');
                return swal({
                    text: "آیا از حذف این محصول مطمئن هستید؟",
                    icon: "error",

                    buttons: ["نه اشتباه شد", "بله حذف کن"]
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $('#delete_cart').attr('action', url);
                        $('#delete_cart').submit();
                    } else {
                        swal("خیلی هم خوب", {
                            button: false,
                            timer: 500,
                        });
                    }
                });
                return false;
            }


        function changeQuantity(event, id , cartName = null) {
            //
            $.ajaxSetup({
                headers : {
                    'X-CSRF-TOKEN' : document.head.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type' : 'application/json'
                }
            })

            //
            $.ajax({
                type : 'POST',
                url : '/cart/quantity/change',
                data : JSON.stringify({
                    id : id ,
                    quantity : event.target.value,
                    // cart : cartName,
                    _method : 'patch'
                }),
                success : function(res) {
                    location.reload();
                }
            });
        }

    </script>
@endsection

@section('content')
    <div class="container px-3 my-5 clearfix">
        <!-- Shopping cart table -->
        <div class="card">
            <div class="card-header">
                <h2>سبد خرید</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered m-0">
                        <thead>
                        <tr>
                            <!-- Set columns width -->
                            <th class="text-center py-3 px-4" style="min-width: 400px;">نام محصول</th>
                            <th class="text-right py-3 px-4" style="width: 150px;">قیمت واحد</th>
                            <th class="text-center py-3 px-4" style="width: 120px;">تعداد</th>
                            <th class="text-right py-3 px-4" style="width: 150px;">قیمت نهایی</th>
                            <th class="text-center align-middle py-3 px-0" style="width: 40px;"><a href="#" class="shop-tooltip float-none text-light" title="" data-original-title="Clear cart"><i class="ino ion-md-trash"></i></a></th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach(Cart::all() as $cart)
                            @if(isset($cart['product']))
                                @php
                                    $product = $cart['product'];
                                @endphp
                                <tr>
                                <td class="p-4">
                                    <div class="media align-items-center">
                                        <div class="media-body">
                                            <a href="#" class="d-block text-dark">{{ $product->title }}</a>
                                            @if($product->attributes)
                                                <small>
                                                    @foreach($product->attributes->take(3) as $attr)
                                                        <span class="text-muted">{{ $attr->name }}: </span> {{ $attr->pivot->value->value }}
                                                    @endforeach
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                @if (! $cart['discount_percent'])
                                    <td class="text-right font-weight-semibold align-middle p-4">{{ $product->price }} تومان</td>
                                @else
                                    <td class="text-right font-weight-semibold align-middle p-4">
                                        <del class="text-danger text-sm">{{ $product->price }} تومان</del>
                                        @php
                                            $discountedPrice = $product->price - ($product->price * $cart['discount_percent'])
                                        @endphp
                                        <span>{{ $discountedPrice }} تومان</span>
                                    </td>
                                @endif
                                <td class="align-middle p-4">
                                    <select onchange="changeQuantity(event, '{{ $cart['id'] }}')" class="form-control text-center">
                                        @foreach(range(1 , $product->inventory) as $item)
                                            <option value="{{ $item }}" {{  $cart['quantity'] == $item ? 'selected' : '' }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                @if (! $cart['discount_percent'])
                                    <td class="text-right font-weight-semibold align-middle p-4">تومان {{ $product->price * $cart['quantity'] }}</td>
                                @else
                                    <td class="text-right font-weight-semibold align-middle p-4">
                                        <del class="text-danger text-sm">{{ $product->price * $cart['quantity'] }} تومان</del>
                                        <span>{{ $discountedPrice * $cart['quantity'] }} تومان</span>
                                    </td>
                                @endif
                                <td class="text-center align-middle px-0">
                                    <form id="delete_cart" method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <a href="#" data-url="{{ route('cart.destroy', $cart['id']) }}" onclick="return confirmDelete(this);" class="shop-tooltip close float-none text-danger" title="پاک کردن {{ $product->title }}">
                                        <svg height="18" viewBox="-64 0 512 512" width="18" xmlns="http://www.w3.org/2000/svg"><path d="m256 80h-32v-48h-64v48h-32v-80h128zm0 0" fill="#62808c"/><path d="m304 512h-224c-26.507812 0-48-21.492188-48-48v-336h320v336c0 26.507812-21.492188 48-48 48zm0 0" fill="#e76e54"/><path d="m384 160h-384v-64c0-17.671875 14.328125-32 32-32h320c17.671875 0 32 14.328125 32 32zm0 0" fill="#77959e"/><path d="m260 260c-6.246094-6.246094-16.375-6.246094-22.625 0l-41.375 41.375-41.375-41.375c-6.25-6.246094-16.378906-6.246094-22.625 0s-6.246094 16.375 0 22.625l41.375 41.375-41.375 41.375c-6.246094 6.25-6.246094 16.378906 0 22.625s16.375 6.246094 22.625 0l41.375-41.375 41.375 41.375c6.25 6.246094 16.378906 6.246094 22.625 0s6.246094-16.375 0-22.625l-41.375-41.375 41.375-41.375c6.246094-6.25 6.246094-16.378906 0-22.625zm0 0" fill="#fff"/></svg>
                                    </a>
                                </td>
                            </tr>
                            @endif
                        @endforeach

                        </tbody>
                    </table>
                </div>
                <!-- / Shopping cart table -->
                <div class="d-flex flex-wrap justify-content-between align-items-center pb-4">
                    @if ($discount = Cart::getDiscount())
                        <div class="mt-4">
                            <form action="{{ route('cart.discount.delete') }}" method="post" id="delete-discount">
                                @method('delete')
                                @csrf
                                <input type="hidden" name="cart" value="cart">
                            </form>
                            <span>کد تخفیف فعال : <span class="text-success">{{ $discount->code }}</span> <a href="#" onclick="event.preventDefault();document.getElementById('delete-discount').submit()" class="badge badge-danger">حذف کد تخفیف</a></span>
                            <div>درصد تخفیف : <span class="text-success">{{ $discount->percent }} درصد</span></div>
                        </div>
                    @else
                        <form action="{{ route('cart.discount.check') }}" method="post" class="mt-4">
                            @csrf
                            <div class="form-group form-inline">
                                <input type="hidden" name="cart" value="cart" class="form-control">
                                <input type="text" name="discount" class="form-control" placeholder="کد تخفیف دارید؟">
                                <button type="submit" class="btn btn-sm btn-success mr-3">اعمال کد تخفیف</button>
                            </div>
                            @if ($errors->has('discount'))
                                <div class="text-danger text-sm mt-2">{{ $errors->first('discount') }}</div>
                            @endif
                        </form>
                    @endif
                    <div class="d-flex">
{{--                        <div class="text-right mt-4 mr-5">--}}
{{--                            <label class="text-muted font-weight-normal m-0">Discount</label>--}}
{{--                            <div class="text-large"><strong>$20</strong></div>--}}
{{--                        </div>--}}
                        <div class="text-right mt-4">
                            <label class="text-muted font-weight-normal m-0">قیمت کل</label>

                            @php
                                $totalPrice = Cart::all()->sum(function($cart) {
                                    return $cart['discount_percent'] == 0
                                    ? $cart['product']->price * $cart['quantity']
                                    : ($cart['product']->price - ($cart['product']->price * $cart['discount_percent']) ) * $cart['quantity'];
                                });
                            @endphp
                            <div class="text-large"><strong>{{ $totalPrice }} تومان</strong></div>
                        </div>
                    </div>
                </div>

                <div class="float-left">
                    <form action="{{ route('cart.payment') }}" method="POST" id="cart_payment">
                        @csrf
                    </form>
                    <button type="button" class="btn btn-lg btn-primary mt-2" onclick="document.getElementById('cart_payment').submit();">پرداخت</button>
                </div>

                <div class="float-right" id="cancel_cart">
                    <a type="button" onclick="return confirmCancel(this);" class="btn btn-lg btn-danger mt-2">لغو</a>
                </div>

            </div>
        </div>
    </div>
@endsection
