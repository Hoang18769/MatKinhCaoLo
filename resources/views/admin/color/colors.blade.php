@extends('admin.master')
@section('title', 'Quản lý Màu Sắc')
@section('main-content')

    <div class="row">
        <div class="col-12">
            <!-- Tiêu đề -->
            <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
                <h4 class="mb-sm-0 font-size-16 fw-bold">DANH MỤC MÀU SẮC</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Danh mục màu sắc</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Thông báo -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Form thêm mới màu sắc -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <form method="post"
                        action="{{ isset($color) ? route('color.update', $color->id_color) : route('color.store') }}">
                        @csrf
                        @if (isset($color))
                            @method('PUT')
                        @endif
                        <div class="mb-3">
                            @error('desc_color')
                                <div class="alert alert-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                            <label for="desc_color"
                                class="form-label">{{ isset($color) ? 'Chỉnh sửa màu sắc' : 'Tạo mới màu sắc' }}</label>
                            <input type="text" class="form-control" id="desc_color" name="desc_color"
                                value="{{ isset($color) ? $color->desc_color : '' }}" placeholder="Nhập tên màu sắc">
                        </div>
                        <br>
                        <button class="btn btn-success" type="submit"><i class="bx bx-save"></i>
                            {{ isset($color)
                                ? 'Cập
                                                    nhật'
                                : 'Lưu màu sắc' }}</button>
                        @if (isset($color))
                            <a href="{{ route('color.index') }}" class="btn btn-danger"><i class="bx bx-x-circle"></i>
                                Huỷ
                                bỏ</a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <br>
        <!-- Danh sách màu sắc -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <table id="Tabledatatable" class="table table-bordered dt-responsive nowrap w-100">
                        <!-- Tiêu đề bảng -->
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên màu sắc</th>
                                <th>Ngày tạo</th>
                                <th style="width: 90px">Chức năng</th>
                            </tr>
                        </thead>
                        <!-- Dữ liệu bảng -->
                        <tbody>
                            @php
                                $count = 1; // Khởi tạo biến đếm
                            @endphp
                            @foreach ($colors as $color)
                                <tr>
                                    <td class="text-center">{{ $count++ }}</td>
                                    <td>{{ $color->desc_color }}</td>
                                    <td>{{ \Carbon\Carbon::parse($color->created_at)->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        <div style="display:flex; gap:10px">
                                            <a class="btn btn-primary"
                                                href="{{ route('color.edit', $color->id_color) }}"><i
                                                    class="bx bx-edit"></i> Chỉnh sửa</a>
                                            {{-- <a href="#" class="btn btn-danger delete-color" data-id="{{ $color->id_color }}"><i
                                            class="bx bx-trash"></i> Xoá</a> --}}
                                            <form id="deleteForm_{{ $color->id_color }}"
                                                action="{{ route('color.destroy', $color) }}" method="POST"
                                                style="display: inline;">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-danger delete-color"
                                                    data-id="{{ $color->id_color }}"> Xóa</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.all.min.js
    "></script>
    <script>
        const deleteForms = document.querySelectorAll('form[id^="deleteForm_"]');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const categoryId = this.getAttribute('data-id');
                Swal.fire({
                    title: "Thông báo",
                    text: "Bạn có chắc muốn xoá danh mục này không?",
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
                        // Nếu xác nhận xoá, gửi form để xóa danh mục
                        this.submit(); // Sử dụng this.submit() để gửi form hiện tại
                    }
                });
            });
        });
    </script>
@endsection
