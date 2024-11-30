@extends('admin.master')
@section('title', 'Quản lý Khách Hàng')
@section('main-content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
            <h4 class="mb-sm-0 font-size-16 fw-bold">QUẢN LÝ KHÁCH HÀNG</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Quản lý khách hàng</li>
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
                <h5 class="fw-bold">Bộ lọc</h5>
                <form class="border-bottom mb-3" action="{{ route('customer.index') }}" method="GET">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <input type="text" class="form-control" placeholder="Tên khách hàng" id="name_filter" name="name_filter" value="{{ request('name_filter') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" placeholder="Email" id="email_filter" name="email_filter" value="{{ request('email_filter') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" placeholder="Số điện thoại" id="phone_filter" name="phone_filter" value="{{ request('phone_filter') }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary"><i class="bx bx-filter-alt"></i> Lọc</button>
                            <a href="{{ route('customer.index') }}" class="btn btn-primary"><i class="bx bx-reset"></i> Reset bộ lọc</a>
                        </div>
                    </div>
                </form>
                <br>
                <table id="Tabledatatable" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                    <tr>
                        <th style="width: 10px">STT</th>
                        <th>Tên khách hàng</th>
                        <th>Email</th>
                        <th style="width: 30px">SĐT</th>
                        <th>Địa chỉ</th>
                        <th style="width: 40px">Tài khoản liên kết</th>
                        <th style="width: 50px">Trạng thái tài khoản</th>
                        {{-- <th>Ngày tạo</th> --}}
                        <th style="width: 40px">Chức năng</th>
                    </tr>
                    </thead>
                    <tbody>
                        @php
                        $count = 1; // Khởi tạo biến đếm
                        @endphp
                          @foreach($customers as $customer)
                        <tr>
                            <td class="text-center">{{ $count++ }}</td>
                            <td>{{ $customer->name_customer  }}</td>
                            <td>{{ $customer->email_customer }}</td>
                            <td>{{ $customer->phone_customer }}</td>
                            <td>{{ $customer->address_customer }}</td>
                            <td>{{ $customer->account->name_account }}</td>
                            <td>@if( $customer->account->status_account == 1)
                                <small class="label label-success badge-pill">Hoạt động</small>
                                @elseif( $customer->account->status_account == 0)
                                <small class="label label-warning badge-pill">Đã khóa</small>
                                @endif</td>
                            {{-- <td>{{ \Carbon\Carbon::parse($customer->created_at)->format('d/m/Y H:i:s') }}</td> --}}
                            <td>
                                <div style="display:flex;gap:10px">
                                    <a class="btn btn-primary" href="{{ route('customer.edit', $customer->id_customer) }}"><i class="bx bx-edit"></i> Chỉnh sửa</a>
                                    <a href="#" class="btn btn-danger delete-customer" data-id="{{ $customer->id_customer }}"><i class="bx bx-trash"></i> Xoá</a>
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
    {{ $customerss->links() }}
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.all.min.js"></script>

<script>
    const deleteLinks = document.querySelectorAll('.delete-customer');
        deleteLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const customerID = this.getAttribute('data-id');
                Swal.fire({
                    title: "Thông báo",
                    text: "Bạn có chắc muốn xoá khách hàng này không?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#34c3af",
                    cancelButtonColor: "#f46a6a",
                    confirmButtonText: "Đồng ý xoá",
                    cancelButtonText: "Huỷ bỏ",customClass: {
                popup: 'custom-swal-popup' // Đặt lớp CSS tùy chỉnh cho cửa sổ thông báo
            }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `/admin/customer/delete/${customerID}`;
                    }
                });
            });
        });
</script>

@endsection
