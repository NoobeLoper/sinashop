@component('admin.layouts.content', ['title' => 'ویرایش دیدگاه'])

    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
        <li class="breadcrumb-item"><a href="/admin/comments">دیدگاه ها</a></li>
        <li class="breadcrumb-item active">ویرایش دیدگاه</li>
    @endslot


    <div class="row">
        <div class="col-lg-12">
            @include('admin.layouts.errors')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">فرم ویرایش دیدگاه</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" action="{{ route('admin.comments.update', $comment->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="card-body">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">نام نظر دهنده</label>
                            <input type="text" name="user_id" class="form-control" id="name"  value="{{ old('user_id', $comment->user->name) }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="label" class="col-sm-2 control-label">دیدگاه</label>
                            <textarea name="comment" class="form-control" id="label">{{ $comment->comment }}</textarea>
                        </div>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">ویرایش دیدگاه</button>
                        <a href="{{ route('admin.comments.index') }}" class="btn btn-default float-left">لغو</a>
                    </div>
                    <!-- /.card-footer -->
                </form>
            </div>
        </div>
    </div>

@endcomponent
