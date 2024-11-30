<header class="main-header">
    <!-- Logo -->
    <a href="{{route('admin.index')}}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        {{-- <span class="logo-mini"><b>A</b>LT</span> --}}
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">Hệ Thống Quản Trị</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

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


        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->

                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ asset('assets') }}/images/avatar5.png" class="user-image" alt="User Image">
                        <span class="hidden-xs">Xin chào, {{ $user->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        {{-- <li class="user-header">
                <img src="{{asset('assets')}}/images/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  Alexander Pierce - Web Developer
                  <small>Member since Nov. 2012</small>
                </p>
              </li> --}}
                        <!-- Menu Body -->
                        {{-- <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                <!-- /.row -->
              </li> --}}
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            {{-- <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div> --}}
                            <div class="pull-right">
                                {{-- <a href="{{route('admin.logout')}}" class="btn btn-default btn-flat">Sign out</a> --}}
                                <a href="{{ route('admin.logout') }}" class="btn btn-default btn-flat">Đăng Xuất</a>
                            </div>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</header>
