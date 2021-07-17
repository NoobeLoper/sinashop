@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
{{--                @foreach($products as $product)--}}
{{--                    --}}
{{--                @endforeach--}}
                @foreach($products->chunk(4) as $row)
                    <div class="row">
                        @foreach($row as $product)
                            <div class="col-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $product->title }}</h5>
                                        @if ($product->image)
                                            <img src="{{ url($product->image) }}" alt="thumbnail" class="my-1" width="80" height="80">
                                        {{-- @else
                                            <img src="https://via.placeholder.com/80x80/000000/FFFFFF/?text=Thumbnail" alt="thumbnail" class="my-1"> --}}
                                        @endif
                                        <p class="card-tex">{{ mb_substr(strip_tags($product->description),0,50,'UTF8'). '...' }} </p>
                                        <a href="/products/{{ $product->id }}" class="btn btn-primary">جزئیات محصول</a>
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
