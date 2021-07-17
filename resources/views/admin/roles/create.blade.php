@component('admin.layouts.content', ['title' => 'ایجاد مقام'])

    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
        <li class="breadcrumb-item"><a href="/admin/roles">مقام ها</a></li>
        <li class="breadcrumb-item active">ایجاد مقام جدید</li>
    @endslot

    @slot('script')
        <script>
            $('#roles').select2({
                'placeholder' : 'مقام مورد نظر را انتخاب کنید'
            })
            $('#permissions').select2({
                'placeholder' : 'دسترسی مورد نظر را انتخاب کنید'
             })
        </script>
    @endslot

    <div class="row">
        <div class="col-lg-12">
            @include('admin.layouts.errors')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">فرم ایجاد مقام</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf

                    <div class="card-body">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">نام مقام</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="نام مقام را به انگلیسی وارد کنید" value="{{ old('name') }}">
                            <small>فاصله بین کلمات را با خط تیره مشخص کنید. like-this-sample</small>
                        </div>
                        <div class="form-group">
                            <label for="label" class="col-sm-2 control-label">توضیح مقام</label>
                            <input type="text" name="label" class="form-control" id="label" placeholder="توضیح نام مقام را وارد کنید" value="{{ old('label') }}">
                        </div>

                        <div class="form-group">
                            <label for="permissions" class="col-sm-2 control-label">دسترسی ها</label>
                                <select class="selectpicker" name="permissions[]" id="permissions" multiple title="Choose">
                                    @foreach (App\Permission::all() as $permission)
                                        <option value="{{ $permission->id }}">{{ $permission->name }} - {{ $permission->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">ثبت مقام</button>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-default float-left">لغو</a>
                    </div>
                    <!-- /.card-footer -->
                </form>
            </div>
        </div>
    </div>

@endcomponent
