@component('admin.layouts.content' , ['title' => 'فهرست تخفیف'])
    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
        <li class="breadcrumb-item active">فهرست تخفیف‌ها</li>
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
                    <h3 class="card-title">تخفیف‌ها</h3>

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
                            @can('create-discount')
                                <a href="{{ route('admin.discounts.create') }}" class="btn btn-info">ایجاد تخفیف جدید</a>
                            @else
                                <a href="#" class="btn btn-dark disabled">ایجاد تخفیف جدید</a>
                           @endcan
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>آی‌دی تخفیف</th>
                            <th>کد تخفیف</th>
                            <th>میزان تخفیف (درصد)</th>
                            <th>مربوط به کاربر</th>
                            <th>مربوط به محصول</th>
                            <th>مربوط به دسته</th>
                            <th>مهلت استفاده</th>
                            <th>اقدامات</th>
                        </tr>

                        @foreach($discounts as $discount)
                            <tr>
                                <td>{{ $discount->id }}</td>
                                <td>{{ $discount->code }}</td>
                                <td>{{ $discount->percent }}</td>
                                <td>{{ $discount->users->count() ? $discount->users->pluck('name')->join(', ') : 'همه کاربران' }}</td>
                                <td>{{ $discount->products->count() ? $discount->products->pluck('title')->join(', ') : 'همه محصولات' }}</td>
                                <td>{{ $discount->categories->count() ?  $discount->categories->pluck('name')->join(', ') : 'همه دسته‌ها' }}</td>
                                <td>{{ jdate($discount->expired_at)->ago() }}</td>
                                <td class="d-flex">

                                    @can('delete-discount')
                                        <form method="POST" id="delete_form">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <a href="#" data-url="{{ route('admin.discounts.destroy' , $discount->id) }}" class="btn btn-sm btn-danger ml-1" onclick="return confirmDelete(this);">حذف</a>
                                    @else
                                        <button class="btn btn-sm btn-dark ml-1 disabled">حذف</button>
                                    @endcan
                                    @can('edit-discount')
                                        <a href="{{ route('admin.discounts.edit' , $discount->id) }}" class="btn btn-sm btn-primary">ویرایش</a>
                                    @else
                                        <a href="#" class="btn btn-sm btn-dark disabled">ویرایش</a>
                                    @endcan

                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    {{ $discounts->appends([ 'search' => request('search') ])->render() }}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>

@endcomponent
