<div class="btn-group">
@foreach($categories as $category)
<div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{ $category->name }}
        </button>
        @if($category->child)
            <div class="dropdown-menu text-center" aria-labelledby="dropdownMenu2">
                @foreach ($category->child as $childesh)
                    <a href="/categories/{{ $childesh->id }}" class="dropdown-item">
                        {{ $childesh->name }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endforeach
</div>

{{-- @include('layouts.list-categories' , ['categories' => $category->child]) --}}


{{-- <ul>
    @foreach($categories as $category)
        <li>
            {{ $category->name }}
            @if($category->child)
                @include('layouts.list-categories' , ['categories' => $category->child])
            @endif
        </li>
    @endforeach
</ul> --}}
