@component('admin.layouts.content', ['title' => 'ایجاد دسترسی'])

    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
        <li class="breadcrumb-item"><a href="/admin/permissions">دسترسی ها</a></li>
        <li class="breadcrumb-item active">ایجاد دسترسی جدید</li>
    @endslot

    @slot('script')
        <script>
            $('#roles').select2({
                'placeholder' : 'مقام مورد نظر را انتخاب کنید'
            })
            $('#permissions').select2({
                'placeholder': 'دسترسی مورد نظر را انتخاب کنید'
             })
        </script>
    @endslot

    <div class="row">
        <div class="col-lg-12">
            @include('admin.layouts.errors')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">فرم ایجاد دسترسی</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" action="{{ route('admin.permissions.store') }}" method="POST">
                    @csrf

                    <div class="card-body">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">نام دسترسی</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="نام دسترسی را به انگلیسی وارد کنید" value="{{ old('name') }}">
                            <small>فاصله بین کلمات را با خط تیره مشخص کنید. like-this-sample</small>
                        </div>
                        <div class="form-group">
                            <label for="label" class="col-sm-2 control-label">توضیح دسترسی</label>
                            <input type="text" name="label" class="form-control" id="label" placeholder="توضیح نام دسترسی را وارد کنید" value="{{ old('label') }}">
                        </div>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">ثبت دسترسی</button>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-default float-left">لغو</a>
                    </div>
                    <!-- /.card-footer -->
                </form>
            </div>
        </div>
    </div>

@endcomponent
