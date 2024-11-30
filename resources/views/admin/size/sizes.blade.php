@extends('admin.master')
@section('title', 'Quản lý Kích Thước')
@section('main-content')

    <div class="row">
        <div class="col-12">
            <!-- Tiêu đề -->
            <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
                <h4 class="mb-sm-0 font-size-16 fw-bold">DANH MỤC KÍCH THƯỚC</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Danh mục kích thước</li>
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
        <!-- Form thêm mới kích thước -->
        <div>
            <p><span style="font-weight: 400;">Bảng số đo so sánh thông thường:&nbsp;</span></p>
            <table style="border-collapse: collapse; width: 100%; height: 92px;">
                <tbody>
                    <tr style="height: 23px;">
                        <td style="width: 25%; height: 23px;"></td>
                        <td style="width: 25%; height: 23px;">Số đo mắt kính</td>
                        <td style="width: 25%; height: 23px;">Số đo càng kính</td>
                        <td style="width: 25%; height: 23px;">Số đo càng kính</td>
                    </tr>
                    <tr style="height: 23px;">
                        <td style="width: 25%; height: 23px;">Nhỏ</td>
                        <td style="width: 25%; height: 23px;">42-48</td>
                        <td style="width: 25%; height: 23px;">15-18</td>
                        <td style="width: 25%; height: 23px;">135-140</td>
                    </tr>
                    <tr style="height: 23px;">
                        <td style="width: 25%; height: 23px;">Vừa</td>
                        <td style="width: 25%; height: 23px;">48-52</td>
                        <td style="width: 25%; height: 23px;">18-20</td>
                        <td style="width: 25%; height: 23px;">140-145</td>
                    </tr>
                    <tr style="height: 23px;">
                        <td style="width: 25%; height: 23px;">Lớn</td>
                        <td style="width: 25%; height: 23px;">&gt;52</td>
                        <td style="width: 25%; height: 23px;">20-22</td>
                        <td style="width: 25%; height: 23px;">145-150</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <br>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <form method="post"
                        action="{{ isset($size) ? route('size.update', $size->id_size) : route('size.store') }}">
                        @csrf
                        @if (isset($size))
                            @method('PUT')
                        @endif
                        <div class="mb-3">
                            @error('desc_size')
                                <div class="alert alert-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                            <label for="desc_size"
                                class="form-label">{{ isset($size) ? 'Chỉnh sửa kích thước' : 'Tạo mới kích thước' }}
                            </label>
                            <input type="text" class="form-control" id="desc_size" name="desc_size"
                                 value="{{ isset($size) ? $size->desc_size : '' }}" placeholder="Nhập tên kích thước">
                        </div>
                        <br>
                        <button class="btn btn-success" type="submit"><i class="bx bx-save"></i>
                            {{ isset($size) ? 'Cập nhật' : 'Lưu kích thước' }}</button>
                        @if (isset($size))
                            <a href="{{ route('size.index') }}" class="btn btn-danger"><i class="bx bx-x-circle"></i> Huỷ
                                bỏ</a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <br>
        <!-- Danh sách kích thước -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <table id="Tabledatatable" class="table table-bordered dt-responsive nowrap w-100">
                        <!-- Tiêu đề bảng -->
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên kích thước</th>
                                <th>Ngày tạo</th>
                                <th style="width: 90px">Chức năng</th>
                            </tr>
                        </thead>
                        <!-- Dữ liệu bảng -->
                        <tbody>
                            @php
                                $count = 1; // Khởi tạo biến đếm
                            @endphp
                            @foreach ($sizes as $size)
                                <tr>
                                    <td class="text-center">{{ $count++ }}</td>
                                    <td>{{ $size->desc_size }}</td>
                                    <td>{{ \Carbon\Carbon::parse($size->created_at)->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        <div style="display:flex; gap:10px">
                                            <a class="btn btn-primary" href="{{ route('size.edit', $size->id_size) }}"><i
                                                    class="bx bx-edit"></i> Chỉnh sửa</a>
                                            <form id="deleteForm_{{ $size->id_size }}"
                                                action="{{ route('size.destroy', $size) }}" method="POST"
                                                style="display: inline;">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-danger delete-color"
                                                    data-id="{{ $size->id_size }}"> Xóa</button>
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
