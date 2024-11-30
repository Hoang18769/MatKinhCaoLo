@extends('admin.master')
@section('title', 'Chỉnh sửa Tài Khoản')
@section('main-content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
            <h4 class="mb-sm-0 font-size-16 fw-bold ">CHỈNH SỬA TÀI KHOẢN</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{route('account.index')}}">Quản lý tài khoản</a></li>
                    <li class="breadcrumb-item active">Chỉnh sửa tài khoản</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('account.update', $account->id_account) }}">
                        @csrf
                        @method('PUT')
                        <div class="row" bis_skin_checked="1">
                            <div class="col-md-12" bis_skin_checked="1">
                                <div class="mb-3" bis_skin_checked="1">
                                    <br>
                                    <label for="name_account" class="form-label">Tên tài khoản</label>
                                    <input type="text" class="form-control" id="name_account" name="name_account" value="{{ $account->name_account }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-12" bis_skin_checked="1">
                                <div class="mb-3" bis_skin_checked="1">
                                    <br>
                                    <label for="email_account" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email_account" name="email_account" value="{{ $account->email_account }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-12" bis_skin_checked="1">
                                <div class="mb-3" bis_skin_checked="1">
                                    <br>
                                    <label for="password_account" class="form-label">Đổi mật khẩu mới</label>
                                    <input type="password" class="form-control" id="password_account" name="password_account">
                                </div>
                            </div>
                        </div>
                        <div bis_skin_checked="1">
                            <br>
                            @if($account->id !== 1)
                            <button class="btn btn-success" type="submit"><i class="bx bx-save"></i> Lưu tài khoản</button>
                            @endif
                            <a href="{{ route('account.index') }}" class="btn btn-danger"><i class="bx bx-x-circle"></i> Huỷ bỏ</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Sự kiện thay đổi cho tên trạng thái
    const checkbox = document.getElementById('status_account');
    checkbox.addEventListener('change', function() {
        const label = document.querySelector('label[for="status_account"]');
        if (checkbox.checked) {
            label.textContent = 'Hoạt động';
            checkbox.value = '1'
        } else {
            label.textContent = 'Ngừng hoạt động';
            checkbox.value = '0';
        }
    });
</script>
@endsection
