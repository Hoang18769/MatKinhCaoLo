@extends('admin.master')
@section('title', 'Quản Lý Đơn Hàng')
@section('main-content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
            <h4 class="mb-sm-0 font-size-16 fw-bold">QUẢN LÝ ĐƠN HÀNG</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Quản lý đơn hàng</li>
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
                <form class="border-bottom mb-3" action="{{ route('order.index') }}" method="GET">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <input type="text" class="form-control" placeholder="Nhập tên, mã, sđt, email..." id="namecodeemailphone_filter" name="namecodeemailphone_filter" value="{{ request('namecodeemailphone_filter') }}">
                        </div>
                        <div class="col-md-2">
                            <input placeholder="Từ ngày đặt" type="text" class="form-control pickdate" id="start_date_filter" name="start_date_filter" value="{{ request('start_date_filter') }}">
                        </div>
                        {{-- <div class="col-md-2">
                            <input placeholder="Đến ngày đặt" type="text" class="form-control pickdate" id="end_date_filter" name="end_date_filter" value="{{ request('end_date_filter') }}">
                        </div> --}}

                        <div class="col-md-2">
                            <select class="form-select select2" id="payment_filter" name="payment_filter">
                                <option value="">Chọn phương thức</option>
                                @foreach($paymentMethods as $paymentId => $paymentMethod)
                                <option value="{{ $paymentId }}" {{ request('payment_filter') == $paymentId ? 'selected' : '' }}>{{ $paymentMethod }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select select2" id="status_filter" name="status_filter">
                                <option value="">Chọn trạng thái</option>
                                <option value="0" {{ request('status_filter') == '0' ? 'selected' : '' }}>Đã huỷ</option>
                                <option value="1" {{ request('status_filter') == '1' ? 'selected' : '' }}>Chờ xác nhận</option>
                                <option value="2" {{ request('status_filter') == '2' ? 'selected' : '' }}>Đã xác nhận</option>
                                <option value="3" {{ request('status_filter') == '3' ? 'selected' : '' }}>Đang giao hàng</option>
                                <option value="4" {{ request('status_filter') == '4' ? 'selected' : '' }}>Giao thành công</option>
                                <option value="5" {{ request('status_filter') == '5' ? 'selected' : '' }}>Hoàn trả</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary"><i class="bx bx-filter-alt"></i> Lọc</button>
                            <a href="{{ route('order.index') }}" class="btn btn-primary"><i class="bx bx-reset"></i> Reset</a>
                        </div>
                    </div>
                </form>
                <br>
                <table id="Tabledatatable" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th style="width: 10px">STT</th>
                            <th>Mã đơn</th>
                            <th>Ngày đặt</th>
                            <th>Tên khách</th>
                            <th>SĐT</th>
                            <th>Phương thức</th>
                            <th style="width: 100px">Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th style="width: 100px">Chuyển trang thái nhanh</th>
                            <th style="width: 210px">Chức năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $count = 1; // Khởi tạo biến đếm
                        @endphp
                        @foreach($orders as $order)
                        <tr>
                            <td class="text-center">{{ $count++ }}</td>
                            <td style="font-weight:600;color:rgb(201, 13, 0)">{{ $order->code_order }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->date_order)->format('d/m/Y H:i:s') }}</td>
                            <td>{{ $order->name_order }}</td>
                            <td>{{ $order->phone_order }}</td>
                            <td>{{ $order->paymentmethod ? $order->paymentmethod->name_payment : 'N/A' }}</td>

                            {{-- <td style="font-weight:700;">{{ number_format($order->total_order + $order->shippingfee , 0, '.', ',') }} đ</td> --}}
                            @if ($order->discount)
                            <td style="font-weight:700;">{{ number_format($order->total_order - $order->discount->discount + $order->shippingfee , 0, '.', ',') }} đ</td>
                            @else
                            <td style="font-weight:700;">{{ number_format($order->total_order + $order->shippingfee , 0, '.', ',') }} đ</td>
                            @endif
                            <td class="text-center last">
                                <span class="badge @if($order->status_order == 1) badge-soft-dark @elseif($order->status_order == 0) badge-soft-danger @elseif($order->status_order == 2) badge-soft-info @elseif($order->status_order == 3) badge-soft-info @elseif($order->status_order == 4) badge-soft-success @elseif($order->status_order == 5) badge-soft-warning @endif">
                                    @if($order->status_order == 1) Chờ xác nhận @elseif($order->status_order == 0) Đã huỷ @elseif($order->status_order == 2) Đã xác nhận @elseif($order->status_order == 3) Đang giao hàng @elseif($order->status_order == 4) Giao thành công @elseif($order->status_order == 5) Hoàn trả @endif
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 10px;">
                                    @if($order->status_order == 1)
                                    <a data-id="{{ $order->id_order }}" class="btn btn-danger btn-sm update-status" data-status="0"><i class="bx bxs-x-circle"></i> Huỷ đơn</a>
                                    <a data-id="{{ $order->id_order }}" class="btn btn-block btn-success btn-sm update-status" data-status="2"><i class="bx bxs-check-circle"></i> Xác nhận đơn</a>
                                    @elseif($order->status_order == 2)
                                    <a data-id="{{ $order->id_order }}" class="btn btn-danger btn-sm update-status" data-status="0"><i class="bx bxs-x-circle"></i> Huỷ đơn</a>
                                    <a data-id="{{ $order->id_order }}" class="btn btn-block btn-info btn-sm update-status" data-status="3"><i class="bx bxs-truck"></i> Đang giao hàng</a>
                                    @elseif($order->status_order == 3)
                                    <a data-id="{{ $order->id_order }}" class="btn btn-block btn-warning btn-sm fw-bold update-status" data-status="5"><i class="bx bxs-left-arrow-square"></i> Hoàn trả</a>
                                    <a data-id="{{ $order->id_order }}" class="btn btn-block btn-success btn-sm fw-bold update-status" data-status="4"><i class="bx bxs-package"></i> Giao thành công</a>
                                    @endif

                                </div>
                            </td>
                            <td>
                                <a class="btn btn-primary" href="{{ route('order.show', $order->id_order) }}"><i class="bx bx-show-alt"></i> Xem chi tiết</a>
                                <a href="#" class="btn btn-danger delete-order" data-id="{{ $order->id_order }}"><i class="bx bx-trash"></i> Xoá</a>
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
    {{ $orders->links() }}
</div>

<script>
    const statusNames = {
    0: 'Huỷ đơn',
    2: 'Xác nhận đơn',
    3: 'Đang giao hàng',
    4: 'Giao thành công',
    5: 'Hoàn trả'
    };
    const updateStatusLinks = document.querySelectorAll('.update-status');
    updateStatusLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const urlParams = new URLSearchParams(this.getAttribute('href'));
            const orderID = this.getAttribute('data-id');
            const newStatus = this.getAttribute('data-status');
            const statusName = statusNames[newStatus];
            Swal.fire({
                title: `Chuyển sang ${statusName}`,
                text: `Bạn có chắc muốn thực hiện thao tác này ?`
                , showCancelButton: true
                , confirmButtonColor: "#34c3af"
                , cancelButtonColor: "#f46a6a"
                , confirmButtonText: "Đồng ý"
                , cancelButtonText: "Huỷ bỏ", customClass: {
                popup: 'custom-swal-popup' // Đặt lớp CSS tùy chỉnh cho cửa sổ thông báo
            }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/admin/order/update_status/${orderID}/${newStatus}`;
                }
            });
        });
    });


    const deleteLinks = document.querySelectorAll('.delete-order');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const orderID = this.getAttribute('data-id');
            Swal.fire({
                title: "Thông báo"
                , text: "Bạn có chắc muốn xoá đơn hàng này không?."
                , icon: "warning"
                , showCancelButton: true
                , confirmButtonColor: "#34c3af"
                , cancelButtonColor: "#f46a6a"
                , confirmButtonText: "Đồng ý xoá"
                , cancelButtonText: "Huỷ bỏ", customClass: {
                popup: 'custom-swal-popup' // Đặt lớp CSS tùy chỉnh cho cửa sổ thông báo
            }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/admin/order/delete/${orderID}`;
                }
            });
        });
    });

</script>

@endsection
