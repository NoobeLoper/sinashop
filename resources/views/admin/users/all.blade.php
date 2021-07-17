@component('admin.layouts.content', ['title' => 'فهرست کاربران'])

    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
        <li class="breadcrumb-item active">فهرست کاربران</li>
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
                        <div class="btn-group-sm mr-1">
                            @can('create-user')
                                <a href="{{ route('admin.users.create') }}" class="btn btn-info">ایجاد کاربر جدید</a>
                            @else
                                <a href="#" class="btn btn-dark disabled">ایجاد کاربر جدید</a>
                            @endcan

                            @can('show-staff-users')
                                <a href="{{ request()->fullUrlWithQuery(['admin' => 1])  }}" class="btn btn-warning">کاربران مدیر</a>
                            @else
                                <a href="#" class="btn btn-dark disabled">کاربران مدیر</a>
                            @endcan
                        </div>

                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>آیدی کاربر</th>
                                <th>نام کاربر</th>
                                <th>ایمیل</th>
                                <th>وضعیت ایمیل</th>
                                <th>مدیریت</th>
                            </tr>

                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    @if($user->email_verified_at)
                                        <td><span class="badge badge-success">فعال</span></td>
                                    @else
                                        <td><span class="badge badge-danger">غیرفعال</span></td>
                                    @endif
                                    <td class="d-flex">
                                        @can('delete-user')
                                            <form id="delete_form" method="POST">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <a href="#" data-url="{{ route('admin.users.destroy', $user->id) }}" class="btn btn-sm btn-danger ml-1" onclick="return confirmDelete(this);">حذف</a>
                                        @else
                                            <button class="btn btn-sm btn-dark ml-1 disabled">حذف</button>
                                        @endcan


                                        @can('edit-user')
                                            <a href="{{ route('admin.users.edit', ['user'=>$user->id]) }}" class="btn btn-sm btn-primary ml-1">ویرایش</a>
                                        @else
                                            <a href="#" class="btn btn-sm btn-dark disabled ml-1">ویرایش</a>
                                        @endcan

                                        @if ($user->isStaffUser())
                                            @can('staff-user-permissions')
                                                <a href="{{ route('admin.users.permissions.create', $user->id) }}" class="btn btn-sm btn-warning">دسترسی ها</a>
                                            @else
                                                <a href="#" class="btn btn-sm btn-dark disabled">دسترسی ها</a>
                                            @endcan
                                        @endif

                                    </td>
                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    {{-- {{ $users->render() }} --}}
                    {{-- baraye inke dar page 2-3-... ham search ro neshum bede: --}}
                    {{ $users->appends([ 'search' => request('search') ])->render() }}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>

@endcomponent
