@component('admin.layouts.content' , ['title' => 'لیست نظرات'])
    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
        <li class="breadcrumb-item active">لیست نظرات</li>
    @endslot

    @slot('script')
        <script>
            function confirmDelete(e) {
                var url = $(e).data('url');
                return swal({
                    title: "آیا از حذف این مورد مطمئن هستید؟",
                    text: "پس از حذف امکان دست یابی به اطلاعات مقدور نمی باشد",
                    icon: "error",

                    buttons: ["نه اشتباه شد", "بله حذف کن"]
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $('#delete_form').attr('action', url);
                        $('#delete_form').submit();
                    } else {
                        swal("خیلی هم خوب");
                    }
                });
                return false;
            }
        </script>
    @endslot

    <div class="row">
        <div class="col-12">
            @include('admin.layouts.errors')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">نظرات</h3>

                    <div class="card-tools d-flex">
                        <form action="">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="search" class="form-control float-right" placeholder="جستجو" value="{{ request('search') }}">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                        <div class="btn-group-sm mr-1">
                            @can('show-approved-comments')
                                <a href="{{ request()->fullUrlWithQuery(['approved' => 1])  }}" class="btn btn-warning">تنها دیدگاه های منتشر نشده</a>
                            @else
                                <a href="#" class="btn btn-dark disabled">تنها دیدگاه های منتشر نشده</a>
                            @endcan
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>آیدی نظر</th>
                                <th>نام کاربر</th>
                                <th>متن</th>
                                <th>مدیریت</th>
                            </tr>

                            @foreach($comments as $comment)
                                <tr>
                                    <td>{{ $comment->id }}</td>
                                    <td>{{ $comment->user->name }}</td>
                                    <td>
                                        <p>{{ mb_substr(strip_tags($comment->comment),0,40,'UTF8'). '...' }} </p>
                                    </td>
                                    <td class="d-flex">
                                        @can('delete-comment')
                                            <form method="POST" id="delete_form">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <a href="#" data-url="{{ route('admin.comments.destroy' , $comment->id) }}" class="btn btn-sm btn-danger ml-1" onclick="return confirmDelete(this);">حذف</a>
                                        @else
                                            <a href="#" class="btn btn-sm btn-dark disabled ml-1">حذف</button>
                                        @endcan
                                        @can('edit-comment')
                                            <a href="{{ route('admin.comments.edit', $comment->id) }}" class="btn btn-sm btn-primary ml-1">ویرایش</a>
                                        @else
                                            <a href="#" class="btn btn-sm btn-dark disabled ml-1">ویرایش</a>
                                        @endcan
                                        @can('approving-comment')
                                            <form action="{{ route('admin.comments.approving', $comment->id) }}", method="POST">
                                                @csrf
                                                @if ($comment->approved == 1)
                                                    <button type="submit" class="btn btn-sm btn-success ml-1">منتشر شده</button>
                                                @else
                                                    <button type="submit" class="btn btn-sm btn-warning ml-1">منتشر نشده</button>
                                                @endif
                                            </form>
                                        @else
                                            <a href="#" class="btn btn-sm btn-dark disabled ml-1">وضعیت انتشار</a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    {{ $comments->appends([ 'search' => request('search') ])->render() }}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>

@endcomponent
