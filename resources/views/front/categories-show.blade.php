@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ $category->name }} ها
                    </div>

                    <div class="card-body">
                        @if ($category->products)
                            @foreach ($category->products as $productesh)
                            <a href="/products/{{ $productesh->id }}">{{ $productesh->title }}</a>
                            @if ($loop->count>1 && !$loop->last)
                                -
                            @endif
                            @endforeach
                            <br>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
