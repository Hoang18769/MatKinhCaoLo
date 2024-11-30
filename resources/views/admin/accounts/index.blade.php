@extends('admin.master')
@section('title', 'Quản lý Tài Khoản')
@section('main-content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
            <h4 class="mb-sm-0 font-size-16 fw-bold">QUẢN LÝ TÀI KHOẢN</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{route('account.index')}}">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Quản lý tài khoản</li>
                </ol>
            </div>

        </div>
    </div>
</div>
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="Tabledatatable" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th style="width: 10px">STT</th>
                            <th>Tên tài khoản</th>
                            <th>Email</th>
                            <th>Ngày tạo</th>
                            <th>Trạng thái</th>
                            <th style="width: 40px">Chức năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $count = 1; // Khởi tạo biến đếm
                        @endphp
                        @foreach($accounts as $account)
                        <tr>
                            <td class="text-center">{{ $count++ }}</td>
                            <td>{{ $account->name_account  }}</td>
                            <td>{{ $account->email_account }}</td>
                            <td>{{ \Carbon\Carbon::parse($account->created_at)->format('d/m/Y H:i:s') }}</td>
                            <td>@if($account->status_account == 1)
                                <small class="label label-success badge-pill">Hoạt động</small>
                                @elseif($account->status_account == 0)
                                <small class="label label-warning badge-pill">Đã khóa</small>
                                @endif</td>
                            <td>
                                <div style="display:flex;gap:10px">
                                    @if($account->id_account !== 1)
                                    <!-- Nút xoá chỉ được hiển thị nếu ID không phải là 1 -->
                                    <a class="btn btn-primary" href="{{ route('account.edit', $account->id_account) }}"><i class="bx bx-edit"></i> Chỉnh sửa</a>
                                    <a href="{{ route('accounts.activate', $account->id_account) }}" class="btn fw-bold update-status
                                        {{ ($account->status_account == 1) ? 'btn btn-success':'btn btn-warning' }}"
                                        data-id="{{ $account->id_account }}" data-status="{{ ($account->status_account == 1) ? 0 : 1 }}">
                                         <i class="bx {{ ($account->status_account == 1) ? 'bx-x' : 'bx-check' }}"></i>
                                         {{ ($account->status_account == 1) ? 'Khóa' : 'Mở khóa' }}
                                     </a>
                                    @endif
                                </div>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            <!-- end card-body -->
        </div>
        <!-- end card -->
    </div> <!-- end col -->
    {{ $accounts->links() }}
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.all.min.js
"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const updateStatusLinks = document.querySelectorAll('.update-status');
        const statusNames = {
            0: 'Khóa',
            1: 'Mở khóa',
        };

        updateStatusLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const accountID = this.getAttribute('data-id');
                const newStatus = this.getAttribute('data-status');
                const statusName = statusNames[newStatus];
                Swal.fire({
                    title: `Chuyển sang ${statusName}`,
                    text: `Bạn có chắc muốn thực hiện thao tác này ?`,
                    showCancelButton: true,
                    confirmButtonColor: "#34c3af",
                    cancelButtonColor: "#f46a6a",
                    confirmButtonText: "Đồng ý",
                    cancelButtonText: "Huỷ bỏ",customClass: {
                popup: 'custom-swal-popup' // Đặt lớp CSS tùy chỉnh cho cửa sổ thông báo
            }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `account/activate/${accountID}`;
                    }
                });
            });
        });
    });
</script>

@endsection
