@extends('admin.master')
@section('title', 'Chi Tiết Đơn Hàng')
@section('main-content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
                <h4 class="mb-sm-0 font-size-16 fw-bold">CHI TIẾT ĐƠN HÀNG <a
                        style="color: #1d6cd9;font-weight: 700;">#{{ $order->code_order }}</a></h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Danh sách đơn hàng</a></li>
                        <li class="breadcrumb-item active">Chỉnh sửa đơn hàng</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="flex-button">
            <div style="display: flex;gap:10px;order: -9999;">
                <a class="text-white btn btn-primary fw-bold text-center mb-3" href="{{ route('order.index') }}"><i
                        class='bx bx-left-arrow-alt'></i> Trở về</a>
                <button id="printInvoice" class="btn btn-block btn-default btn-sm fw-bold mb-3" id="printInvoice">
                    <i class="bx bxs-printer"></i> In hoá đơn
                </button>
            </div>
            <div style="display: flex;gap:10px;order: 9999;">
                @if ($order->status_order == 1)
                    <a data-id="{{ $order->id_order }}" class="btn btn-danger btn-sm update-status mb-3" data-status="0"><i
                            class="bx bxs-x-circle"></i> Huỷ đơn</a>
                    <a data-id="{{ $order->id_order }}" class="btn btn-block btn-success btn-sm update-status mb-3"
                        data-status="2"><i class="bx bxs-check-circle"></i> Xác nhận đơn</a>
                @elseif($order->status_order == 2)
                    <a data-id="{{ $order->id_order }}" class="btn btn-danger btn-sm update-status mb-3" data-status="0"><i
                            class="bx bxs-x-circle"></i> Huỷ đơn</a>
                    <a data-id="{{ $order->id_order }}" class="btn btn-block btn-info btn-sm update-status mb-3"
                        data-status="3"><i class="bx bxs-truck"></i> Đang giao hàng</a>
                @elseif($order->status_order == 3)
                    <a data-id="{{ $order->id_order }}" class="btn btn-block btn-warning btn-sm fw-bold update-status mb-3"
                        data-status="5"><i class="bx bxs-left-arrow-square"></i> Hoàn trả</a>
                    <a data-id="{{ $order->id_order }}" class="btn btn-block btn-success btn-sm fw-bold update-status mb-3"
                        data-status="4"><i class="bx bxs-package"></i> Giao thành công</a>
                @endif
                <a href="#" class="btn btn-danger btn-sm fw-bold delete-order mb-3" data-id="{{ $order->id_order }}"><i
                        class="bx bxs-trash"></i> Xoá đơn hàng</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold border-bottom pb-3 mb-3 !text-black">Thông tin sản phẩm</h5>
                        <div class="table-responsive">
                            @if ($orderDetails->isNotEmpty())
                                <table class="table table-responsive table-bordered text-left my-orders-table">
                                    <thead>
                                        <tr class="first last">
                                            <th class="text-center last" style="width: 20px">Hình ảnh</th>
                                            <th class="text-center last">Tên sản phẩm</th>
                                            <th class="text-center last">Mã sản phẩm</th>
                                            <th class="text-center last">Size</th>
                                            <th class="text-center last">Màu</th>
                                            <th class="text-center last">Giá bán</th>
                                            <th class="text-center last">Số lượng</th>
                                            <th class="text-center last">Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orderDetails as $detail)
                                            {{-- <li>{{ $detail->product->name_product }} - {{ $detail->quantity }} - {{ $detail->totalprice }}</li>
                                <p>Size: {{ $detail->sizes->desc_size }}</p>
                                <p>Color: {{ $detail->colors->desc_color }}</p> --}}
                                            <tr>
                                                <td> <img width="80" height="60" style="object-fit: contain"
                                                        src="{{ $detail->product->avt_product }}"
                                                        alt="{{ $detail->product->name_product }}">
                                                </td>
                                                <td>{{ $detail->product->name_product }}</td>
                                                {{-- <td class="text-center last"> <a style="color: black !important" href="{{ route('products.show', ['linkProduct' => $detail->product->link_product]) }}">{{ $detail->product->name_product }}</a></td> --}}
                                                <td class="text-center last" width="150px">{{ $detail->product->sku }}
                                                </td>
                                                <td class="text-center last" width="150px">
                                                    {{ $detail->productVariant->size->desc_size }}</td>
                                                <td class="text-center last" width="150px">
                                                    {{ $detail->productVariant->color->desc_color }}</td>
                                                <td class="text-center last" width="150px">
                                                    @if ($detail->product->sellprice_product > 0)
                                                        @php
                                                            $formattedPrice = number_format(
                                                                $detail->product->sellprice_product,
                                                                0,
                                                                '.',
                                                                ',',
                                                            );
                                                            $formattedPriceold = number_format(
                                                                $detail->product->price_product,
                                                                0,
                                                                '.',
                                                                ',',
                                                            );
                                                        @endphp
                                                        <p class="fw-bold" style="line-height: 10px">{{ $formattedPrice }}
                                                            đ</p>
                                                        <del style="font-style: italic;color:#616161dd">{{ $formattedPriceold }}
                                                            đ</del>
                                                    @endif
                                                    @php
                                                        $setemptysell = number_format(
                                                            $detail->product->sellprice_product,
                                                            0,
                                                            '.',
                                                            ',',
                                                        );
                                                    @endphp
                                                    @if ($setemptysell === '0')
                                                        @php
                                                            $formattedPrice = number_format(
                                                                $detail->product->price_product,
                                                                0,
                                                                '.',
                                                                ',',
                                                            );
                                                        @endphp

                                                        <p class="fw-bold" style="line-height: 10px">{{ $formattedPrice }}
                                                            đ</p>
                                                    @endif
                                                </td>
                                                <td class="text-center last" width="100px">{{ $detail->quantity }}</td>
                                                <td class="text-center last fw-bold" width="150px">
                                                    @if ($detail->product->sellprice_product > 0)
                                                        @php
                                                            $formattedPrice = number_format(
                                                                $detail->quantity * $detail->product->sellprice_product,
                                                                0,
                                                                '.',
                                                                ',',
                                                            );
                                                            $formattedPriceold = number_format(
                                                                $detail->product->price_product,
                                                                0,
                                                                '.',
                                                                ',',
                                                            );
                                                        @endphp
                                                        {{ $formattedPrice }} đ
                                                    @endif
                                                    @php
                                                        $setemptysell = number_format(
                                                            $detail->product->sellprice_product,
                                                            0,
                                                            '.',
                                                            ',',
                                                        );
                                                    @endphp
                                                    @if ($setemptysell === '0')
                                                        @php
                                                            $formattedPrice = number_format(
                                                                $detail->quantity * $detail->product->price_product,
                                                                0,
                                                                '.',
                                                                ',',
                                                            );
                                                        @endphp
                                                        {{ $formattedPrice }} đ
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            @else
                                <p>Không có chi tiết đơn hàng nào.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body text-black">
                        <h5 class="fw-bold border-bottom pb-3 mb-3 !text-black">Thông tin giao hàng</h5>
                        <p> <strong>Mã đơn hàng: </strong> <a
                                style="color: #1d6cd9;font-weight: 600;">#{{ $order->code_order }}</a></p>
                        <p> <strong>Trạng thái đơn hàng: </strong>
                            <span
                                class="badge @if ($order->status_order == 1) badge-soft-dark @elseif($order->status_order == 0) badge-soft-danger @elseif($order->status_order == 2) badge-soft-primary @elseif($order->status_order == 3) badge-soft-info @elseif($order->status_order == 4) badge-soft-success @elseif($order->status_order == 5) badge-soft-warning @endif">
                                @if ($order->status_order == 1)
                                    Chờ xác nhận
                                @elseif($order->status_order == 0)
                                    Đã huỷ
                                @elseif($order->status_order == 2)
                                    Đã xác nhận
                                @elseif($order->status_order == 3)
                                    Đang giao hàng
                                @elseif($order->status_order == 4)
                                    Giao thành công
                                @elseif($order->status_order == 5)
                                    Hoàn trả
                                @endif
                            </span>
                        </p>
                        <p> <strong>Phương thức thanh toán: </strong> {{ $order->paymentMethod->name_payment }}</p>
                        <p> <strong>Họ tên khách hàng: </strong> {{ $order->name_order }}</p>
                        <p> <strong>Số điện thoại: </strong> {{ $order->phone_order }}</p>
                        <p> <strong>Thời gian đặt hàng: </strong>
                            {{ \Carbon\Carbon::parse($order->date_order)->format('d/m/Y H:i:s') }}</p>
                        <p> <strong>Địa chỉ giao hàng: </strong> {{ $order->address_order }}</p>
                        <p> <strong>Ghi chú đơn hàng: </strong> {{ $order->note }}</p>
                    </div>
                </div>
            </div>
            {{-- @if ($order)
                {{ dd($order->discounts->code) }}
            @else
                {{ 'Mã giảm giá không tồn tại' }}
            @endif --}}
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body text-black">
                        <h5 class="fw-bold border-bottom pb-3 mb-3 !text-black">Thông tin thanh toán</h5>
                        <p> <strong> Tổng tiền đơn hàng: </strong>
                            @if ($order->id_discount && $order->discount)
                                <span class="fw-bold"
                                    style="float: right;">{{ number_format($order->total_order ) }}
                                    đ</span>
                            @else
                                <span style="float: right;">{{ number_format($order->total_order) }} đ</span>
                            @endif
                        </p>
                        <p>
                            <strong>Mã giảm giá :</strong>
                            @if ($order->id_discount && $order->discount)
                                <span style="float: right;">- {{ number_format($order->discount->discount) }} đ</span>
                            @else
                                <span style="float: right;">Không có mã giảm giá</span>
                            @endif
                        </p>
                        <p>
                            <strong>Phí vận chuyển: </strong>
                            <span style="float: right;">+ {{ number_format($order->shippingfee) }} đ</span>
                        </p>
                        <p>
                            <strong>Thành tiền:</strong>
                            <span style="float: right;">
                                <span style="color: red; font-size: 20px; font-weight: 800">
                                    @if ($order->discount)
                                        {{ number_format($order->total_order - $order->discount->discount + $order->shippingfee) }} đ
                                    @else
                                        {{ number_format($order->total_order + $order->shippingfee) }} đ
                                    @endif
                                </span>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        const printUrl = "{{ route('orders.print', $order->id_order) }}";

        document.getElementById('printInvoice').addEventListener('click', function() {
            const printWindow = window.open(printUrl, '_blank');
            if (printWindow) {
                // Lắng nghe sự kiện từ cửa sổ in để biết khi nào nó đóng
                const closePrintWindow = function() {
                    printWindow.close();
                };
                window.addEventListener('beforeunload', closePrintWindow);

                // Đóng tab in khi nó mất focus (được tắt)
                printWindow.onblur = function() {
                    this.close();
                    window.removeEventListener('beforeunload', closePrintWindow);
                };
                printWindow.focus();
            } else {
                alert('Vui lòng cho phép cửa sổ popup để in hoá đơn.');
            }
        });
    </script>

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
                    text: `Bạn có chắc muốn thực hiện thao tác này ?`,
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#34c3af",
                    cancelButtonColor: "#f46a6a",
                    confirmButtonText: "Đồng ý",
                    cancelButtonText: "Huỷ bỏ",
                    customClass: {
                        popup: 'custom-swal-popup' // Đặt lớp CSS tùy chỉnh cho cửa sổ thông báo
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href =
                            `/admin/order/update_status/${orderID}/${newStatus}`;
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
                    title: "Thông báo",
                    text: "Bạn có chắc muốn xoá đơn hàng này không?.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#34c3af",
                    cancelButtonColor: "#f46a6a",
                    confirmButtonText: "Đồng ý xoá",
                    cancelButtonText: "Huỷ bỏ",
                    customClass: {
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
