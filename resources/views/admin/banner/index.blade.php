@extends('admin.master')
@section('title', 'Quản lý Banner')
@section('main-content')

    <div class="row">
        <div class="col-12">
            <!-- Tiêu đề -->
            <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
                <h4 class="mb-sm-0 font-size-16 fw-bold">DANH MỤC BANNER</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Danh mục banner</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Form thêm mới màu sắc -->
        <div class="col-xl-4">
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
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('banner.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row" bis_skin_checked="1">
                            <div class="col-md-12" bis_skin_checked="1">
                                <div class="mb-3" bis_skin_checked="1">
                                    <label for="name" class="form-label">Hình ảnh</label>

                                    <div id="imageContainer">
                                        <input type="text" id="image" name="image" placeholder=" Nhập URL ảnh"
                                            style="margin-top: 10px; width: 100%;">
                                        <br>
                                        <img id="productImage" width="50%" height="400"
                                            style="object-fit: contain; display:none;" src=""
                                            alt="Hình ảnh không tồn tại">
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-md-12" bis_skin_checked="1">
                            <div class="mb-3" bis_skin_checked="1">
                                <label for="link" class="form-label">Liên kết</label>
                                <input type="text" class="form-control" id="link" name="link">
                            </div>
                        </div> --}}

                        </div>
                        <div class="row" bis_skin_checked="1">
                            <div class="col-md-12" bis_skin_checked="1">
                                <div class="mb-3" bis_skin_checked="1">
                                    <br>
                                    <label for="parent" class="form-label">Loại banner</label>
                                    <select class="form-control select2" id="banner_type" name="banner_type">
                                        <option value="main">Banner chính</option>
                                        <option value="secon1">Banner phụ 1</option>
                                        {{-- <option value="secon2">Banner phụ 2</option>
                                    <option value="secon3">Banner phụ 3</option>
                                    <option value="secon4">Banner phụ 4</option> --}}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <button class="btn btn-success" type="submit"><i class="bx bx-save"></i> Lưu banner</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Danh sách màu sắc -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <br>
                    <form class="border-bottom mb-3" action="{{ route('banner.index') }}" method="GET">
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <select class="form-select select2" id="type_filter" name="type_filter">
                                    <option value="">Chọn loại</option>
                                    <option value="main" {{ request('type_filter') == 'main' ? 'selected' : '' }}>Banner
                                        chính</option>
                                    <option value="secon1" {{ request('type_filter') == 'secon1' ? 'selected' : '' }}>Banner
                                        phụ 1</option>
                                    {{-- <option value="secon2" {{ request('type_filter') == 'secon2' ? 'selected' : '' }}>Banner phụ 2</option>
                                <option value="secon3" {{ request('type_filter') == 'secon3' ? 'selected' : '' }}>Banner phụ 3</option>
                                <option value="secon4" {{ request('type_filter') == 'secon4' ? 'selected' : '' }}>Banner phụ 4</option> --}}
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select select2" id="status_filter" name="status_filter">
                                    <option value="">Chọn trạng thái</option>
                                    <option value="active" {{ request('status_filter') == 'active' ? 'selected' : '' }}>Hoạt
                                        động</option>
                                    <option value="inactive" {{ request('status_filter') == 'inactive' ? 'selected' : '' }}>
                                        Ngừng hoạt động</option>
                                </select>
                            </div>
                            <div class="col-md-2" style ="margin-right: -75px">
                                <button type="submit" class="btn btn-primary"><i class="bx bx-filter-alt"></i> Lọc</button>

                            </div>
                            <div class="col-md-2">

                                <a href="{{ route('banner.index') }}" class="btn btn-primary"><i class="bx bx-reset"></i>
                                    Reset</a>
                            </div>
                        </div>

                    </form>
                    <br>
                    <table id="Tabledatatable" class="table table-bordered dt-responsive nowrap w-100">
                        <!-- Tiêu đề bảng -->
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Hình ảnh</th>
                                <th>Loại banner</th>
                                <th>Ngày tạo</th>
                                <th>Trạng thái</th>
                                <th style="width: 90px">Chức năng</th>
                            </tr>
                        </thead>
                        <!-- Dữ liệu bảng -->
                        <tbody>
                            @php
                                $count = 1; // Khởi tạo biến đếm
                            @endphp
                            @foreach ($banners as $banner)
                                <tr>
                                    <td class="text-center">{{ $count++ }}</td>
                                    <td>
                                        @php
                                            // Xác định loại banner
                                            switch ($banner->type) {
                                                case 'main':
                                                    $bannerType = 'Banner chính';
                                                    break;
                                                case 'secon1':
                                                    $bannerType = 'Banner phụ 1';
                                                    break;
                                                // Bỏ chú thích nếu cần các loại banner khác
                                                // case 'secon2':
                                                //     $bannerType = 'Banner phụ 2';
                                                //     break;
                                                // case 'secon3':
                                                //     $bannerType = 'Banner phụ 3';
                                                //     break;
                                                // case 'secon4':
                                                //     $bannerType = 'Banner phụ 4';
                                                //     break;
                                                default:
                                                    $bannerType = 'Không xác định';
                                                    break;
                                            }
                                        @endphp
                                        @if (filter_var($banner->image, FILTER_VALIDATE_URL))
                                            <img style="width: 500px" src="{{ $banner->image }}" alt="Banner Image">
                                        @else
                                            <p>Đường dẫn hình ảnh không hợp lệ</p>
                                        @endif
                                    </td>

                                    <td>{{ $bannerType }}</td>
                                    <td>{{ \Carbon\Carbon::parse($banner->created_at)->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        @if ($banner->status == 1)
                                            <small class="btn btn-block btn-success">Hoạt động</small>
                                        @elseif($banner->status == 0)
                                            <small class="btn btn-block btn-warning">Ngừng hoạt động</small>
                                        @else
                                            Trạng thái không xác định
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display:flex; gap:10px">
                                            <a href="{{ route('banner.activate', $banner->id_banner) }}"
                                                class="btn fw-bold update-status
                                        {{ $banner->status == 1 ? 'btn btn-warning' : 'btn btn-success' }}"
                                                data-id="{{ $banner->id_banner }}"
                                                data-status="{{ $banner->status == 1 ? 0 : 1 }}">
                                                <i class="bx {{ $banner->status == 1 ? 'bx-x' : 'bx-check' }}"></i>
                                                {{ $banner->status == 1 ? 'Ngừng hoạt động' : 'Hoạt động' }}
                                            </a>
                                            <form id="deleteForm_{{ $banner->id_banner }}"
                                                action="{{ route('banner.destroy', $banner) }}" method="POST"
                                                style="display: inline;">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-danger delete-banner"
                                                    data-id="{{ $banner->id_banner }}"><i class="bx bx-trash"></i>
                                                    Xóa</button>
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
        document.getElementById('image').addEventListener('input', function() {
            const imageURL = this.value;
            const productImage = document.getElementById('productImage');
            const imageContainer = document.getElementById('imageContainer');
            const urlPattern = /^(http(s)?:\/\/)?\S+(\.\S{2,})+(\/*)?$/;
            if (imageURL && !urlPattern.test(imageURL)) {
                // Nếu đầu vào không phải là URL hợp lệ, hiển thị thông báo cảnh báo
                showErrorToast('Vui lòng nhập một URL hợp lệ.');
                this.value = '';
                productImage.style.display = 'none';

            } else {
                productImage.src = imageURL;
                productImage.style.display = 'block';

                var deleteButton = document.createElement('button');
                deleteButton.textContent = 'Xóa';
                deleteButton.style.position = 'absolute';
                deleteButton.style.top = '60px';
                deleteButton.style.right = '628px';
                deleteButton.style.backgroundColor = 'red';
                deleteButton.style.color = 'white';
                deleteButton.style.border = 'none';
                deleteButton.style.borderRadius = '3px';
                deleteButton.style.padding = '5px';
                deleteButton.style.cursor = 'pointer';
                deleteButton.addEventListener('click', function() {
                    productImage.src = ''; // Xóa hình ảnh
                    productImage.style.display = 'none'; // Ẩn hình ảnh
                    deleteButton.remove(); // Xóa nút xóa

                    // Xóa giá trị của ô input
                    document.getElementById('image').value = '';
                });
                // Thêm nút xóa vào phần tử chứa hình ảnh
                imageContainer.appendChild(deleteButton);

            }
        });

        function showErrorToast(message) {
            toastr.error(message);
        }
    </script>
    <script>
        // Xác nhận xoá
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
    <script>
    @endsection
