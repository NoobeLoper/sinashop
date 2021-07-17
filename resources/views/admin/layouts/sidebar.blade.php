<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ asset('img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">پنل مدیریت</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar" style="direction: ltr">
        <div style="direction: rtl">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ asset('img/avatar5.png') }}" class="img-circle elevation-2"
                        alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">سیناش</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">

                    <li>
                        <a href="{{ route('admin.') }}"
                            class="nav-link {{ Route::currentRouteName() == 'admin' ? 'active' : '' }}">
                            <i class="nav-icon fa fa-dashboard" aria-hidden="true"></i> پنل مدیریت
                        </a>
                    </li>

                    <!-- Users -->
                    @can('show-users')

                        <li
                        class="nav-item has-treeview {{ in_array(Route::currentRouteName() , ['admin.users.index', 'admin.users.create', 'admin.users.edit']) ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ in_array(Route::currentRouteName() , ['admin.users.index', 'admin.users.create', 'admin.users.edit']) ? 'active' : '' }}">
                            <i class="nav-icon fa fa-users"></i>
                            <p>
                                کاربران
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.users.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName() , ['admin.users.index', 'admin.users.create', 'admin.users.edit']) ? 'fa fa-check-circle' : 'fa fa-circle-o' }}">
                                        <i class="nav-icon" aria-hidden="true"></i>
                                        <p>فهرست کاربران</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                    @endcan


                    <!-- Permissions & Roles -->
                    @canany(['show-permissions', 'show-roles'])
                        <li
                            class="nav-item has-treeview {{ in_array(Route::currentRouteName() , ['admin.permissions.index', 'admin.permissions.create', 'admin.permissions.edit', 'admin.roles.index', 'admin.roles.create', 'admin.roles.edit']) ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ in_array(Route::currentRouteName() , ['admin.permissions.index', 'admin.permissions.create', 'admin.permissions.edit', 'admin.roles.index', 'admin.roles.create', 'admin.roles.edit']) ? 'active' : '' }}">
                                <i class="nav-icon fa fa-ban"></i>
                                <p>
                                    اجازه دسترسی
                                    <i class="right fa fa-angle-left"></i>
                                </p>
                            </a>
                            <!-- Roles -->

                            @can('show-roles')
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.roles.index') }}"
                                            class="nav-link
                                        {{ in_array(Route::currentRouteName() , ['admin.roles.index', 'admin.roles.create', 'admin.roles.edit']) ? 'fa fa-circle' : 'fa fa-circle-o' }}">
                                            <i class="nav-icon" aria-hidden="true"></i>
                                            <p>همه مقام ها</p>
                                        </a>
                                    </li>
                                </ul>
                            @endcan

                            <!-- Permissions -->
                            @can('show-permissions')
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.permissions.index') }}"
                                            class="nav-link {{ in_array(Route::currentRouteName() , ['admin.permissions.index', 'admin.permissions.create', 'admin.permissions.edit']) ? 'fa fa-circle' : 'fa fa-circle-o' }}">
                                            <i class="nav-icon" aria-hidden="true"></i>
                                            <p>همه دسترسی ها</p>
                                        </a>
                                    </li>
                                </ul>
                            @endcan
                        </li>
                    @endcanany

                    <li
                        class="nav-item has-treeview {{ in_array(Route::currentRouteName() , ['admin.products.index', 'admin.products.create', 'admin.products.edit']) ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ in_array(Route::currentRouteName() , ['admin.products.index', 'admin.products.create', 'admin.products.edit']) ? 'active' : '' }}">
                            <i class="nav-icon fa fa-barcode"></i>
                            <p>
                                محصولات
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.products.index') }}"
                                    class="nav-link {{ in_array(Route::currentRouteName() , ['admin.products.index', 'admin.products.create', 'admin.products.edit']) ? 'fa fa-check-circle' : 'fa fa-circle-o' }}">
                                    <i class="nav-icon" aria-hidden="true"></i>
                                    <p>فهرست محصولات</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    @can('show-categories')
                        <li
                            class="nav-item has-treeview {{ in_array(Route::currentRouteName() , ['admin.caregories.index', 'admin.caregories.create', 'admin.caregories.edit']) ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ in_array(Route::currentRouteName() , ['admin.caregories.index', 'admin.caregories.create', 'admin.caregories.edit']) ? 'active' : '' }}">
                                <i class="nav-icon fa fa-list"></i>
                                <p>
                                    دسته بندی ها
                                    <i class="right fa fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.categories.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName() , ['admin.categories.index', 'admin.categories.create', 'admin.categories.edit']) ? 'fa fa-check-circle' : 'fa fa-circle-o' }}">
                                        <i class="nav-icon" aria-hidden="true"></i>
                                        <p>دسته بندی ها</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    @can('show-comments')
                        <li
                            class="nav-item has-treeview {{ in_array(Route::currentRouteName() , ['admin.comments.index', 'admin.comments.edit']) ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ in_array(Route::currentRouteName() , ['admin.comments.index', 'admin.comments.edit']) ? 'active' : '' }}">
                                <i class="nav-icon fa fa-comment"></i>
                                <p>
                                    دیدگاه ها
                                    <i class="right fa fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.comments.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName() , ['admin.comments.index', 'admin.comments.create', 'admin.comments.edit']) ? 'fa fa-check-circle' : 'fa fa-circle-o' }}">
                                        <i class="nav-icon" aria-hidden="true"></i>
                                        <p>همه دیدگاه</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    @can('show-orders')
                        <li class="nav-item has-treeview {{ in_array(Route::currentRouteName(), ['admin.orders.index', 'admin.orders.show', 'admin.orders.edit', 'admin.orders.delete']) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), ['admin.orders.index', 'admin.orders.show', 'admin.orders.edit', 'admin.orders.delete']) ? 'active' : '' }}">
                                <i class="nav-icon fa fa-shopping-basket"></i>
                                <p>
                                    سفارش ها
                                    <i class="right fa fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.orders.index' , ['type' => 'unpaid']) }}" class="nav-link {{ request()->fullUrlIs(route('admin.orders.index', ['type' => 'unpaid']) ) ? 'active' : '' }}">
                                        <i class="fa fa-circle-o nav-icon text-warning"></i>
                                        <p>پرداخت نشده
                                            <span class="badge badge-warning right">{{ \App\Order::whereStatus('unpaid')->count() }}</span>
                                        </p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.orders.index' , ['type' => 'paid']) }}" class="nav-link  {{ request()->fullUrlIs(route('admin.orders.index', ['type' => 'paid']) ) ? 'active' : '' }}">
                                        <i class="fa fa-circle-o nav-icon text-info"></i>
                                        <p>پرداخت شده
                                            <span class="badge badge-info right">{{ \App\Order::whereStatus('paid')->count() }}</span>
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.orders.index'  , ['type' => 'preparing']) }}" class="nav-link  {{ request()->fullUrlIs(route('admin.orders.index', ['type' => 'preparing']) ) ? 'active' : '' }}">
                                        <i class="fa fa-circle-o nav-icon text-primary"></i>
                                        <p>در حال پردازش
                                            <span class="badge badge-primary right">{{ \App\Order::whereStatus('preparing')->count() }}</span>
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.orders.index' , ['type' => 'posted']) }}" class="nav-link  {{ request()->fullUrlIs(route('admin.orders.index', ['type' => 'posted']) ) ? 'active' : '' }}">
                                        <i class="fa fa-circle-o nav-icon text text-light"></i>
                                        <p>ارسال شده
                                            <span class="badge badge-light right">{{ \App\Order::whereStatus('posted')->count() }}</span>
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.orders.index' , ['type' => 'received']) }}" class="nav-link  {{ request()->fullUrlIs(route('admin.orders.index', ['type' => 'received']) ) ? 'active' : '' }}">
                                        <i class="fa fa-circle-o nav-icon text-success"></i>
                                        <p>دریافت شده
                                            <span class="badge badge-success right">{{ \App\Order::whereStatus('received')->count() }}</span>
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.orders.index' , ['type' => 'canceled']) }}" class="nav-link  {{ request()->fullUrlIs(route('admin.orders.index', ['type' => 'canceled']) ) ? 'active' : '' }}">
                                        <i class="fa fa-circle-o nav-icon text-danger"></i>
                                        <p>کنسل شده
                                            <span class="badge badge-danger right">{{ \App\Order::whereStatus('canceled')->count() }}</span>
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan


                    @if(Route::has('admin.discounts.index'))

                    @can('show-discounts')
                        <li
                            class="nav-item has-treeview {{ in_array(Route::currentRouteName() , ['admin.discounts.index', 'admin.discounts.create', 'admin.discounts.show', 'admin.discounts.edit', 'admin.discounts.delete']) ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ in_array(Route::currentRouteName() , ['admin.discounts.index', 'admin.discounts.create', 'admin.discounts.show', 'admin.discounts.edit', 'admin.discounts.delete']) ? 'active' : '' }}">
                                <i class="nav-icon fa fa-percent"></i>
                                <p>
                                    تخفیف ها
                                    <i class="right fa fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.discounts.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName() , ['admin.discounts.index', 'admin.discounts.create', 'admin.discounts.edit']) ? 'fa fa-check-circle' : 'fa fa-circle-o' }}">
                                        <i class="nav-icon" aria-hidden="true"></i>
                                        <p>همه تخفیف‌ها</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                    @endif

                    @if (Route::has('admin.modules.index'))

                    @can('show-modules')
                        <li
                            class="nav-item has-treeview {{ in_array(Route::currentRouteName() , ['admin.modules.index', 'admin.modules.create', 'admin.modules.show', 'admin.modules.edit', 'admin.modules.delete']) ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ in_array(Route::currentRouteName() , ['admin.modules.index', 'admin.modules.create', 'admin.modules.show', 'admin.modules.edit', 'admin.modules.delete']) ? 'active' : '' }}">
                                <i class="nav-icon fa fa-meetup"></i>
                                <p>
                                    ماژول ها
                                    <i class="right fa fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.modules.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName() , ['admin.modules.index', 'admin.modules.create', 'admin.modules.edit']) ? 'fa fa-check-circle' : 'fa fa-circle-o' }}">
                                        <i class="nav-icon" aria-hidden="true"></i>
                                        <p>مدیریت ماژول ها</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    @endif

                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
    </div>
    <!-- /.sidebar -->
</aside>
