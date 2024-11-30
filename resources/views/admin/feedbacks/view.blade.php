@extends('admin.master')
@section('title', 'Danh Sách Khách Hàng Yêu Thích')
@section('main-content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
                <h4 class="mb-sm-0 font-size-16 fw-bold">DANH SÁCH KHÁCH HÀNG FEEDBACK</h4>
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
                    <h1>Danh Sách Khách Hàng Feedback</h1>
                    <table id="Tabledatatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th style="text-align:center;width: 2px">STT</th>
                                <th style="width: 80px">Tên Khách Hàng</th>
                                <th style="text-align: center;width: 120px"> Tên Sản Phẩm</th>
                                <th style="text-align: center; width: 70px"> Màu và Kích thước</th>
                                <th style="text-align: center;width: 140px">Bình luận</th>
                                <th style="text-align: center;width: 100px ">Đánh giá sản phẩm</th>
                                <th style="text-align: center;width: 0px">Trạng thái</th>
                                <th style="text-align: center;width: 40px">Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count = 1; // Khởi tạo biến đếm
                            @endphp
                            @foreach ($feedbacks as $feedback)
                            {{-- @foreach ($customer->feedbackCustomers as $feedback) --}}
                                <tr>
                                    <td class="text-center">{{ $count++ }}</td>
                                    <td>{{ $feedback->customer->name_customer }}</td>
                                    <td>{{ $feedback->product->name_product }}</td>
                                    <td style="text-align: center">{{ $feedback->productVariant->color->desc_color }} <br>
                                        {{ $feedback->productVariant->size->desc_size }} </td>
                                    <td>{{ $feedback->comment }} </td>
                                    <td style="text-align: center" >{{ $feedback->rating }} <i class="fa-solid fa-star" style="color: #FFD43B;"></i>  </td>
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
                                </tr>
                                {{-- @endforeach --}}
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <!-- end card-body -->
            </div>
            <!-- end card -->
        </div> <!-- end col -->
    </div>
@endsection
