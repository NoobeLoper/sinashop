@extends('/layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills pr-0">
                        <li class="nav-item">
                            <a href="{{ route('profile') }}" class="nav-link {{ Request::path() === 'profile' ? 'active' : '' }}">پروفایل</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('profile.two.factor') }}" class="nav-link {{ Request::path() === 'profile/twofactor' ? 'active' : '' }}" >احراز هویت دو مرحله ای</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('profile.orders') }}" class="nav-link {{ Request::path() === 'profile/orders' ? 'active' : '' }}" class="nav-link disabled">فهرست سفارش ها</a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    @yield('cardBody')
                </div>

            </div>
        </div>
    </div>
</div>
@endsection


