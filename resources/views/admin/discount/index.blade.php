@extends('admin.master')
@section('title', 'Quản lý Mã Giảm Giá')
@section('main-content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
            <h4 class="mb-sm-0 font-size-16 fw-bold">QUẢN LÝ MÃ GIẢM</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Quản lý mã giảm</li>
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
                <p class="card-title-desc">
                    <a href="{{ route('discount.create') }}" class="btn btn-primary waves-effect waves-light"><i class="bx bx-plus"></i> Tạo mới mã giảm</a>
                </p>
                <br>
                <table id="Tabledatatable" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th style="width: 10px">STT</th>
                            <th>Mã giảm</th>
                            <th>Số tiền giảm</th>
                            <th>Giới hạn số lượt</th>
                            <th>Đã sử dụng</th>
                            <th>Ngày hết hạn</th>
                            <th>Thanh toán tối thiểu</th>
                            <th style="width: 40px">Chức năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $count = 1; // Khởi tạo biến đếm
                        @endphp
                        @foreach($discounts as $discount)
                        <tr>
                            <td class="text-center">{{ $count++ }}</td>
                            <td>{{ $discount->code }}</td>
                            <td>{{ number_format($discount->discount, 0, '.', ',') }}</td>
                            <td>{{ number_format($discount->limit_number, 0, '.', ',') }}</td>
                            <td>{{ number_format($discount->number_used, 0, '.', ',') }}</td>
                            <td>{{ \Carbon\Carbon::parse($discount->expiration_date)->format('d/m/Y H:i:s') }}</td>
                            <td>{{ number_format($discount->payment_limit, 0, '.', ',') }}</td>
                            <td>
                                <div style="display:flex;gap:10px">
                                    <a class="btn btn-primary"
                                        href="{{ route('discount.edit', $discount->id_discount) }}"><i
                                            class="bx bx-edit"></i> Chỉnh sửa</a>
                                    <a href="#" class="btn btn-danger delete-discount" data-id="{{ $discount->id_discount }}"><i
                                            class="bx bx-trash"></i> Xoá</a>
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
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.all.min.js
"></script>
<script>
    const deleteLinks = document.querySelectorAll('.delete-discount');
        deleteLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const discountID = this.getAttribute('data-id');
                Swal.fire({
                    title: "Thông báo",
                    text: "Bạn có chắc muốn xoá mã giảm này không?",
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
                        window.location.href = `/admin/discount/delete/${discountID}`;
                    }
                });
            });
        });
    </script>

@endsection
