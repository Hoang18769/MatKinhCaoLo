@extends('admin.master')
@section('title', 'Quản lý Yêu Thích')
@section('main-content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
                <h4 class="mb-sm-0 font-size-16 fw-bold">QUẢN LÝ FEEDBACK</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Quản lý Feedback</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <br>
                    <table id="Tabledatatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                {{-- <th style="text-align:center;width: 2px">STT</th>
                                <th style="width: 40px">Tên Khách Hàng</th>
                                <th style="text-align: center;width: 120px"> Tên Sản Phẩm</th>
                                <th style="text-align: center; width: 70px"> Màu và Kích thước</th>
                                <th style=" center;width: 140px">Bình luận</th>
                                <th style="text-align: center;width: 0px ">Đánh giá sản phẩm</th>
                                <th style="text-align: center;width: 0px">Trạng thái</th>
                                <th style="text-align: center;width: 40px">Chức năng</th> --}}
                                <th style="width: 10px">STT</th>
                                <th style="width: 40px">Hình ảnh</th>
                                <th>Tên sản phẩm </th>
                                <th style="text-align: center;width: 250px">Tổng đánh giá sản phẩm</th>
                                <th style="text-align: center">Danh sách khách hàng Feedback</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count = 1; // Khởi tạo biến đếm
                            @endphp
                            {{-- @foreach ($feedbacks as $feedback)
                                <tr>
                                    <td class="text-center">{{ $count++ }}</td>
                                    <td> {{ $feedback->customer->name_customer }} </td>
                                    <td>{{ $feedback->product->name_product }} </td>
                                    <td style="text-align: center">{{ $feedback->productVariant->color->desc_color }} <br>
                                        {{ $feedback->productVariant->size->desc_size }} </td>
                                    <td>{{ $feedback->comment }} </td>
                                    <td style="text-align: center"> {{ $feedback->rating }}
                                        <i class="fa-solid fa-star" style="color: #FFD43B;"></i>
                                    </td>
                                    <td >
                                        @if ($feedback->feedback_status == 1)
                                            <small class="btn btn-block btn-success">Hiện bình luận</small>
                                        @elseif($feedback->feedback_status == 0)
                                            <small class="btn btn-block btn-danger">Ẩn bình luận</small>
                                        @else
                                            Trạng thái không xác định
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display:flex; gap:10px">
                                            <a href="{{ route('feedback.activate', $feedback->id_feedback) }}"
                                                class="btn fw-bold update-status
                                        {{ $feedback->feedback_status == 1 ? 'btn btn-block btn-danger' : 'btn btn-block btn-success' }}"
                                                data-id="{{ $feedback->id_feedback }}"
                                                data-status="{{ $feedback->feedback_status == 1 ? 0 : 1 }}">
                                                <i class="bx {{ $feedback->feedback_status == 1 ? ' ' : 'bx-check' }}"></i>
                                                {{ $feedback->feedback_status == 1 ? 'Ẩn bình luận' : 'Hiện bình luận' }}
                                            </a>
                                        </div>
                                    </td>
                                    {{-- <td style="text-align: center">
                                        <a href="#" class="btn btn-danger delete-feedback"
                                            data-id="{{ $feedback->id_feedback }}"><i class="bx bx-trash"></i> Xoá</a>
                                    </td> --}}
                            {{-- </tr>
                            @endforeach --}}
                            @foreach ($productsWithFeedback as $feedback)
                                @php
                                    $product = $feedback->product;
                                    $productId = $feedback->id_product; // Lấy ID sản phẩm từ feedback
                                    $customers = \App\Models\Feedback::where('id_product', $feedback->id_product)
                                        ->with('customer')
                                        ->get()
                                        ->pluck('customer');
                                    $customerIds = $customers->pluck('id_customer')->toArray();
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $count++ }}</td>
                                    <td> <img width="80" height="60" style="object-fit: contain"
                                            src="{{ $feedback->product->avt_product }}"
                                            alt="{{ $feedback->product->name_product }}">
                                    </td>
                                    <td>{{ $feedback->product->name_product }}</td>
                                    <td class="text-center">
                                        {{ rtrim(rtrim(number_format($feedback->average_rating, 2), '0'), '.') }}
                                        <i class="fa-solid fa-star" style="color: #FFD43B;"></i>
                                    </td>
                                    <td style="text-align: center">
                                        {{-- @foreach ($customers as $customer) --}}
                                        @if ($customers->isNotEmpty())
                                            <a class="btn btn-primary"
                                                href="{{ route('feedback.show1', ['id' => implode(',', $customerIds),'productId' => $productId]) }}">
                                                <i class="bx bx-show-alt"></i> Xem Khách Hàng Feedback
                                            </a>
                                        @else
                                            <span class="text-danger">Không có khách hàng</span>
                                        @endif
                                        {{-- @endforeach --}}
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
    <script>
        const deleteLinks = document.querySelectorAll('.delete-feedback');
        deleteLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const feedbackID = this.getAttribute('data-id');
                Swal.fire({
                    title: "Thông báo",
                    text: "Bạn có chắc muốn xoá feedback này không?.",
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
                        window.location.href = `/admin/feedback/delete/${feedbackID}`;
                    }
                });
            });
        });
    </script>
@endsection
