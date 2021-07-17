@extends('layouts.app')

@section('script')
    <script>
        $('#sendComment').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            let parent_id = button.data('id');

            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('input[name="parent_id"]').val(parent_id)
        })

    // document.querySelector('#sendCommentForm').addEventListener('submit' , function(event) {
        //     event.preventDefault();
        //     let target = event.target;
        //
        //     let data = {
        //         commentable_id : target.querySelector('input[name="commentable_id"]').value,
        //         commentable_type: target.querySelector('input[name="commentable_type"]').value,
        //         parent_id: target.querySelector('input[name="parent_id"]').value,
        //         comment: target.querySelector('textarea[name="comment"]').value
        //     }
        //
        //     // if(data.comment.length < 2) {
        //     //     console.error('pls enter comment more than 2 char');
        //     //     return;
        //     // }
        //
        //
        //     $.ajaxSetup({
        //         headers : {
        //             'X-CSRF-TOKEN' : document.head.querySelector('meta[name="csrf-token"]').content,
        //             'Content-Type' : 'application/json'
        //         }
        //     })
        //
        //
        //     $.ajax({
        //         type : 'POST',
        //         url : '/comments',
        //         data : JSON.stringify(data),
        //         success : function(data) {
        //             console.log(data);
        //         }
        //     })
    // })

        //Up & Down Quantity Value Fn
        function increaseValue() {
            var value = parseInt(document.getElementById('number').value, 10);
            value = isNaN(value) ? 0 : value;
            value++;
            document.getElementById('number').value = value;
            }

            function decreaseValue() {
            var value = parseInt(document.getElementById('number').value, 10);
            value = isNaN(value) ? 0 : value;
            value < 1 ? value = 1 : '';
            value--;
            document.getElementById('number').value = value;
        }
    </script>
@endsection

@section('content')

    @auth
        <div class="modal fade" id="sendComment">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">ارسال نظر</h5>
                        <button type="button" class="close mr-auto ml-0" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('send.comment') }}" method="post" id="sendCommentForm">
                        @csrf
                        <div class="modal-body">
                                <input type="hidden" name="commentable_id" value="{{ $product->id }}" >
                                <input type="hidden" name="commentable_type" value="{{ get_class($product) }}">
                                <input type="hidden" name="parent_id" value="0">

                                <div class="form-group">
                                    <label for="message-text" class="col-form-label">پیام دیدگاه:</label>
                                    <textarea name="comment" class="form-control" id="message-text"></textarea>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">لغو</button>
                            <button type="submit" class="btn btn-primary">ارسال نظر</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endauth

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-center">
                        {{ $product->title }}
                    </div>

                    <div class="card-body">
                        @if (Cart::count($product) < $product->inventory)
                            <form action="{{ route('cart.add' , $product->id) }}" method="POST" id="add_to_cart">
                                @csrf
                            </form>
                            <div class="float-left">
                                <div class="row">
                                    <span class="col d-flex">قیمت: {{ $product->price }}</span>
                                </div>
                                <span class="btn btn-sm btn-success" onclick="document.getElementById('add_to_cart').submit()">اضافه کردن به سبد خرید</span>
                            </div>
                        @else
                            <span class="text-danger float-left">ناموجود</span>
                        @endif
                        @if ($product->categories)
                            @foreach ($product->categories as $catesh)
                                <a href="/categories/{{ $catesh->id }}">{{ $catesh->name }}</a>
                                @if ($loop->count>1 && !$loop->last)
                                    -
                                @endif
                            @endforeach
                            <br>
                        @endif
                        @if ($product->gallery())
                            @foreach ($product->gallery as $gallesh)
                                <img src="{{ url($gallesh->image) }}" class="img-fluid my-4 mx-1" alt="{{ url($gallesh->alt) }}" width="150" height="150">
                            @endforeach
                        <br>
                        @endif
                        {{ $product->description }}

                    </div>
                    <!-- ویژگی ها -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title h4">ویژگی ها</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped w-100">
                                <tbody>
                                @foreach($product->attributes as $atter)
                                    <tr class="">
                                        <td>{{$atter->pivot->attribute->name}}</td>
                                        <td>{{$atter->pivot->value->value}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- ویژگی ها -->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="mt-4">بخش نظرات</h4>
                    @auth
                        <span class="btn btn-sm btn-primary" data-toggle="modal" data-target="#sendComment" data-id="0">ثبت نظر جدید</span>
                    @endauth
                </div>

                @guest
                    <div class="alert alert-warning">برای ثبت نظر لطفا وارد سایت شوید.</div>
                @endguest

                {{-- Faqat commenthaye taiid shode namaayesh dade beshe ? ~~> be khate zir bad az comments() ezafe shavad:: "->where('approved', 1)" --}}
                @include('layouts.comments', ['comments' => $product->comments()->where('parent_id' , 0)->get()])

            </div>
        </div>
    </div>
@endsection
