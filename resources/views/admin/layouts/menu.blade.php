@php
    use App\Models\User;
@endphp
@if (session()->has('id'))
    @php
        // Người dùng đã đăng nhập
        $user_id = session('id');
        $user = User::find($user_id);
    @endphp
    <!-- Hiển thị thông tin của người dùng đã đăng nhập -->
    {{-- <p>Xin chào, {{ $user->name }}</p>
<p>Email: {{ $user->email }}</p> --}}
@else
    <p>Người dùng chưa đăng nhập</p>
@endif

<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('assets') }}/images/avatar5.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ $user->name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
        {{-- <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i
                            class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form> --}}
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->


        <ul class="sidebar-menu" data-widget="tree">
            <li class="menu-title"  style="color:aliceblue" key="t-menu">
                Tổng quan
            </li>
            <li>
                <a href="{{ route('admin.index') }}">
                    <i class="bx bx-desktop"></i> <span>Bảng Điều Khiển</span>
                </a>
            </li>
            <li class="menu-title" style="color:aliceblue" key="t-menu">
                Thông tin quản trị
            </li>
            <li>
                <a href="{{ route('account.index') }}">
                    <i class="fa-solid fa-user"></i> <span> Tài khoản </span>
                </a>
            </li>
            <li>
                <a href="{{ route('customer.index') }}">
                    <i class="fa-solid fa-layer-group"></i> <span> Khách Hàng </span>
                </a>
            </li>
            <li>
                <a href="{{ route('category.index') }}">
                    <i class="fa fa-th"></i><span>Danh Mục Sản Phẩm </span>
                </a>
            </li>
            {{-- <li>
          <a href="{{route('category.index')}}">
            <i class="fa fa-th"></i> <span>Quản lý Danh Mục </span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green">FE</small>
            </span>
          </a>
        </li> --}}
            <li>
                <a href="{{ route('color.index') }}">
                    <i class="fa-solid fa-palette"></i> <span> Màu Sắc</span>
                </a>
            </li>

            <li>
                <a href="{{ route('size.index') }}">
                    <i class="fa-solid fa-table-cells-large"></i> <span> Kích Thước</span>
                </a>
            </li>
            <li>
                <a href="{{ route('product.index') }}">
                    <i class="fa-solid fa-shirt"></i> <span> Sản Phẩm</span>
                </a>
            </li>
            <li>
                <a href="{{ route('order.index') }}">
                    <i class="fa-solid fa-dumpster-fire"></i> <span> Đơn Hàng</span>
                </a>
            </li>
            <li>
                <a href="{{ route('discount.index') }}">
                    <i class="fa-solid fa-snowflake"></i> <span> Mã Giảm Giá</span>
                </a>
            </li>

            {{-- <li class="treeview"> --}}
            <li>
                <a href="{{ route('banner.index') }}">
                    <i class="fa fa-dashboard"></i><span>Quản lý Banner</span>
                    {{-- <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span> --}}
                </a>
                {{-- <ul class="treeview-menu">
            <li><a href=""><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
            <li><a href=""><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
          </ul> --}}
            </li>
            {{-- <li>
                <a href="{{ route('favorite.index') }}">
                    <i class="fa-solid fa-heart"></i></i> <span > Quản Lý Yêu Thích</span>
                </a>
            </li> --}}
            <li>
                <a href="{{route('feedback.index')}}">
                    <i class="fa-solid fa-heart"></i></i> <span > Quản Lý Bình Luận</span>
                </a>
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
