@extends('admin.master')
@section('title', 'Quản lý Sản Phẩm')
@section('main-content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
                <h4 class="mb-sm-0 font-size-16 fw-bold">QUẢN LÝ SẢN PHẨM</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Quản lý sản phẩm</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
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
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-bold">Bộ lọc</h5>
                    <form class="border-bottom mb-3" action="{{ route('product.index') }}" method="GET">
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <input type="text" class="form-control" placeholder="Tên sản phẩm" id="name_filter"
                                    name="name_filter" value="{{ request('name_filter') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" placeholder="Mã sản phẩm" id="sku_filter"
                                    name="sku_filter" value="{{ request('sku_filter') }}">
                            </div>
                            <div class="col-md-2">
                                <select class="form-select form-control select2" id="category_filter"
                                    name="category_filter">
                                    <option value="">Chọn danh mục</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->name_category }}"
                                            {{ request('category_filter') == $category->name_category ? 'selected' : '' }}>
                                            {{ $category->name_category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select select2" id="status_filter" name="status_filter">
                                    <option value="">Chọn trạng thái</option>
                                    <option value="active" {{ request('status_filter') == 'active' ? 'selected' : '' }}>Hoạt
                                        động</option>
                                    <option value="inactive"
                                        {{ request('status_filter') == 'inactive' ? 'selected' : '' }}>Ngừng hoạt động
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select select2" id="stock_filter" name="stock_filter">
                                    <option value="">Chọn tồn kho</option>
                                    <option value="available"
                                        {{ request('stock_filter') == 'available' ? 'selected' : '' }}>Còn hàng</option>
                                    <option value="out_of_stock"
                                        {{ request('stock_filter') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary"><i class="bx bx-filter-alt"></i> Lọc</button>
                                <a href="{{ route('product.index') }}" class="btn btn-primary"><i class="bx bx-reset"></i>
                                    Reset</a>
                            </div>
                        </div>

                    </form>
                    <br>
                    <p class="card-title-desc">
                        <a href="{{ route('product.create') }}" class="btn btn-primary waves-effect waves-light"><i
                                class="bx bx-plus"></i> Tạo mới sản phẩm</a>
                    </p>
                    <table id="Tabledatatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th style="width: 10px">STT</th>
                                <th style="width: 40px">Hình ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Mã</th>
                                <th>Danh mục</th>
                                <th>Giá bán</th>
                                <th>Giá giảm</th>
                                <th style="width: 30px">Lượt mua</th>
                                <th style="width: 30px">Tồn kho</th>
                                <th>Trạng thái</th>
                                <th style="width: 40px">Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count = 1; // Khởi tạo biến đếm
                            @endphp
                            @foreach ($products as $product)
                                <tr>
                                    <td class="text-center">{{ $count++ }}</td>
                                    <td> <img width="80" height="60" style="object-fit: contain"
                                        src="{{ $product->avt_product }}"
                                        alt="{{ $product->name_product }}">
                                    </td>

                                    <td>{{ $product->name_product }}</td>
                                    <td>{{ $product->sku }}</td>
                                    <td>{{ $product->category->name_category }}</td>
                                    <td style="font-weight:700;">{{ number_format($product->price_product, 0, '.', ',') }}
                                    </td>
                                    <td style="font-weight:700;">
                                        {{ number_format($product->sellprice_product, 0, '.', ',') }}</td>
                                    <td>{{ $product->number_buy }}</td>
                                    <td>{{ $product->total_stock }}</td>
                                    <td>
                                        @if ($product->status_product == 1)
                                            <small class="label label-success badge-pill">Hoạt động</small>
                                        @elseif($product->status_product == 0)
                                            <small class="label label-warning badge-pill">Ngừng hoạt động</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:10px">
                                            <a class="btn btn-primary"
                                                href="{{ route('product.edit', $product->id_product) }}"><i
                                                    class="bx bx-edit"></i> Chỉnh sửa</a>
                                            {{-- <a href="#" class="btn btn-danger delete-product" data-id="{{ $product->id_product }}"><i
                                            class="bx bx-trash"></i> Xoá</a> --}}
                                            <form id="deleteForm_{{ $product->id_product }}"
                                                action="{{ route('product.destroy', $product) }}" method="POST"
                                                style="display: inline;">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-danger delete-product"
                                                    data-id="{{ $product->id_product }}"> Xóa</button>
                                            </form>
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
    {{ $products->links() }}

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
