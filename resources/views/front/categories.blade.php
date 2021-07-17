@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
{{--                @foreach($products as $product)--}}
{{--                    --}}
{{--                @endforeach--}}
                @foreach($categories->where('parent_id',0)->chunk(4) as $row)
                    <div class="row">
                        @foreach($row as $category)
                            <div class="col-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $category->name }}</h5>
                                        <a href="/categories/{{ $category->id }}" class="btn btn-primary">محصولات این دسته</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
