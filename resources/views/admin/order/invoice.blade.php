{{-- @extends('admin.master')
@section('title', 'Quản Lý Đơn Hàng')
@section('main-content')

@endsection --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hoá đơn</title>
</head>
<body>
    <div style="
    font-size: 13px;
    color: #000;
  ">

        <p style="font-weight: bold; color: #000; margin-top: 30px; font-size: 18px; text-align:center">
            PHIẾU MUA HÀNG
        </p>
        <div style="margin: 5px auto 30px auto; text-align:center">
            <p><b>Hóa đơn: </b> #{{ $order->code_order  }}</p>
        </div>

        <p>
            <b>Khách hàng:</b> {{ $order->name_order }}
        </p>
        <p>
            <b>Số điện thoại:</b> {{ $order->phone_order }}
        </p>
        <p>
            <b>Thời gian đặt: </b> {{ \Carbon\Carbon::parse($order->date_order)->format('d/m/Y H:i:s') }}
        </p>

        <p>
            <b>Địa chỉ: </b> {{ $order->address_order }}
        </p>
        <hr style="border: 1px dashed rgb(131, 131, 131); margin: 25px auto">
    </div>
    <table style="width: 100%; table-layout: fixed; font-size:13px">

        <tr>
            <th>Sản phẩm</th>
            <th style="text-align: right">Size/Màu</th>
            <th style="text-align:right">Đơn giá</th>
            <th style="text-align:right">Thành tiền</th>
        </tr>

        @foreach($orderDetails as $detail)
        <tr class="invoice-items">
            <td>{{ $detail->product->name_product }} <span style="font-weight: 700">x{{ $detail->quantity }}</span></td>
            <td style="text-align:right">{{ $detail->productVariant->size->desc_size }} - {{ $detail->productVariant->color->desc_color }}</td>
            <td style="text-align:right">@if ($detail->product->sellprice_product > 0)
                @php
                $formattedPrice = number_format($detail->product->sellprice_product, 0, '.',
                ',');
                $formattedPriceold = number_format($detail->product->price_product, 0, '.', ',');
                @endphp
                <p class="fw-bold" style="line-height: 10px;">{{ $formattedPrice }} đ</p>
                <del style="font-style: italic;color:#616161dd">{{ $formattedPriceold }} đ</del>
                @endif
                @php
                $setemptysell = number_format($detail->product->sellprice_product, 0, '.', ',');
                @endphp
                @if ($setemptysell === '0')
                @php
                $formattedPrice = number_format($detail->product->price_product, 0, '.', ',');
                @endphp

                <p class="fw-bold" style="    line-height: 10px;">{{ $formattedPrice }} đ</p>
                @endif</td>
            <td style="text-align: right"> @if ($detail->product->sellprice_product > 0)
                @php
                $formattedPrice = number_format($detail->quantity * $detail->product->sellprice_product, 0, '.',
                ',');
                $formattedPriceold = number_format($detail->product->price_product, 0, '.', ',');
                @endphp
                {{ $formattedPrice }} đ
                @endif
                @php
                $setemptysell = number_format($detail->product->sellprice_product, 0, '.', ',');
                @endphp
                @if ($setemptysell === '0')
                @php
                $formattedPrice = number_format($detail->quantity * $detail->product->price_product, 0, '.', ',');
                @endphp
                {{ $formattedPrice }} đ
                @endif</td>
        </tr>

        @endforeach


    </table>
    <hr style="border: 1px dashed rgb(131, 131, 131); margin: 10px auto">
		<table>
			<thead>
				<tr>
					<td><b>Thành tiền đơn hàng:</b> </td>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<td style="text-align: right;">
                        @if($order->discount_code && $order->discount)
                        <span class="fw-bold" style="float: right;">{{ number_format($order->total_order + $order->discount->discount) }} đ</span>
                    @else
                    <span style="float: right;">{{ number_format($order->total_order) }} đ</span>
                    @endif
                    </td>
				</tr>
                    <tr>
					<td><b>Mã giảm giá:</b> </td>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<td style="text-align: right;">
                        @if($order->id_discount && $order->discount)
                        <span style="float: right;">- {{ number_format($order->discount->discount) }} đ</span>
                    @else
                        <span style="float: right;">0</span>
                    @endif
                    </td>
				</tr>
				<tr>
					<th>Tổng tiền thanh toán: </th>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th style="text-align: right;">{{ number_format($order->total_order - $order->discount->discount) }} đ</th>
				</tr>
				<tr>
					<th>Phương thức: </th>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<td style="text-align: right;">{{ $order->paymentMethod->name_payment }}</td>
				</tr>
				<tr>
					<th>Ghi chú: </th>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<td style="text-align: right;">{{ $order->note }}</td>
				</tr>
                <tr>
					<th>Thời gian in: </th>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<td style="text-align: right;">{{ \Carbon\Carbon::parse(now())->format('d/m/Y H:i:s') }}</td>
				</tr>

			</thead>

		</table>
    <div style="font-size: 11px;text-align:center;margin-top:50px;">
        <p><i>Xin cảm ơn quý khách và hẹn gặp lại !</i></p>
        <p><i>Mọi thắc mắc xin hãy liên hệ về hotline: 0768029939</i></p>
        <p><i>180 Cao Lỗ, P.4, Q.8, HCM</i></p>

        <p> <span>Hệ Thống Quản Lý Bán Mắt Kính Cao Lỗ</span></p>

    </div>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap');

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #ddd;
            padding: 10px;
            word-break: break-all;
        }

        .h4-14 h4 {
            font-size: 12px;
            margin-top: 0;
            margin-bottom: 5px;
        }

        .img {
            margin-left: "auto";
            margin-top: "auto";
            height: 30px;
        }

        pre,
        p {
            /* width: 99%; */
            /* overflow: auto; */
            /* bpicklist: 1px solid #aaa; */
            padding: 0;
            margin: 0;
            line-height: 23px;
            margin-bottom: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            padding: 1px;
        }

        .hm-p p {
            text-align: left;
            padding: 1px;
            padding: 5px 4px;
        }

        td,
        th {
            text-align: left;
            padding: 5px 0px;
            font-size: 11px;
        }

        .table-b td,
        .table-b th {
            border: 1px solid #ddd;
        }

        th {
            /* background-color: #ddd; */
        }

        .hm-p td,
        .hm-p th {
            padding: 3px 0px;
        }

        .cropped {
            float: right;
            margin-bottom: 20px;
            height: 100px;
            /* height of container */
            overflow: hidden;
        }

        .cropped img {
            width: 400px;
            margin: 8px 0px 0px 80px;
        }

        .main-pd-wrapper {
            background-color: #fff;
            border-radius: 10px;
            padding: 15px;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 14px;
        }

        .invoice-items {
            font-size: 14px;
            border-top: 1px dashed #ddd;
        }
        @media print {
            #goBackButton {
                display: none;
            }
        }
        .continue-btn{
            padding: 10px 20px;
            background: #242021 !important;
            color: #ffffff !important;
            border: 2px solid #242021 !important;
            box-shadow: none !important;
            border-radius: 3px;
            font-weight: 700;
        }
    </style>
    <!-- Nút Quay lại -->
    <button id="goBackButton" class="continue-btn">Quay lại trang Quản lý</button>

    <!-- JS để điều hướng khi nút được nhấp -->
    <script>
        // Lắng nghe sự kiện khi nút Quay lại được nhấp
        document.getElementById('goBackButton').addEventListener('click', function () {
            // Điều hướng trang quay lại trang quản lý đơn hàng
            window.location.href = "{{ route('order.index') }}";
        });
    </script>
    <!-- JS để in hoá đơn -->
    <script>
        window.onload = function() {
            window.print();
        };

    </script>
    <script>
        window.onbeforeunload = function() {
            // Đóng tab hiện tại
            window.close();
            // Ngăn chặn cửa sổ popup in từ việc mở trang mới khi tab bị đóng
            if (window.opener && !window.opener.closed) {
                window.opener.focus();
            }
        };
    </script>
</body>
</html>

