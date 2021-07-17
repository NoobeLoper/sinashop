@component('admin.layouts.content', ['title' => 'مقام ها'])

    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
        <li class="breadcrumb-item active">مقام ها</li>
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
                    <h3 class="card-title">جدول کاربران</h3>

                    <div class="card-tools d-flex">

                        <!-- Search Box -->
                        <form action="">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="search" class="form-control float-right" placeholder="جستجو" value="{{ request('search') }}">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                        @can('create-role')
                            <div class="btn-group-sm mr-1">
                                <a href="{{ route('admin.roles.create') }}" class="btn btn-info">ایجاد مقام جدید</a>
                            </div>
                        @else
                            <a href="#" class="btn btn-dark">ایجاد مقام جدید</a>
                        @endcan

                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>نام مقام</th>
                                <th>توضیح مقام</th>
                                <th>مدیریت</th>
                            </tr>

                            @foreach($roles as $role)
                                <tr>
                                    @can('show-roles')
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->label }}</td>
                                        <td class="d-flex">
                                            @can('delete-role')
                                                <form id="delete_form" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <a href="#" data-url="{{ route('admin.roles.destroy', $role->id) }}" class="btn btn-sm btn-danger ml-1" onclick="return confirmDelete(this);">حذف</a>
                                            @else
                                                <button class="btn btn-sm btn-dark ml-1 disabled">حذف</button>
                                            @endcan
                                            @can('edit-role')
                                                <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-primary">ویرایش</a>
                                            @else
                                                <a href="#" class="btn btn-sm btn-dark">ویرایش</a>
                                            @endcan
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    {{ $roles->render() }}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>

@endcomponent
