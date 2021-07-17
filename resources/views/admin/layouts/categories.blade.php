<ul class="list-group list-group-flush">
    @foreach($categories as $category)
        <li class="list-group-item">
            <div class="container">
                <div class="row">
                    <div class="col align-self-start">
                        <div class="btn-group dropleft">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ $category->name }}
                            </button>
                            <div class="dropdown-menu">
                                @can('edit-category')
                                    <a href="{{ route('admin.categories.edit' , $category->id) }}" class="dropdown-item">ویرایش</a>
                                @else
                                    <a href="#" class="dropdown-item disabled">دسترسی ندارید</a>
                                @endcan

                                @can('create-subcategory')
                                    <a href="{{ route('admin.categories.create') }}?parent_id={{ $category->id }}" class="dropdown-item">ثبت زیر دسته</a>
                                @else
                                    <a href="#" class="dropdown-item disabled">دسترسی ندارید</a>
                                @endcan
                                @can('delete-category')
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" id="category-{{ $category->id }}-delete" method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('category-{{ $category->id }}-delete').submit()" class="dropdown-item">حذف</a>
                                @else
                                    <a href="#" class="dropdown-item disabled">دسترسی ندارید</a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($category->child->count())
            <div class="container">
                <div class="row justify-content-around">
                    <div class="col-10">
                        @include('admin.layouts.categories' , [ 'categories' => $category->child])
                    </div>
                </div>
            </div>
            @endif
        </li>
    @endforeach
</ul>
