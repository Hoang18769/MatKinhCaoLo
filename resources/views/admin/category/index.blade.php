@extends('admin.master')
@section('title','Danh Mục Sản Phẩm')
@section('main-content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
            <h4 class="mb-sm-0 font-size-16 fw-bold"> DANH MỤC SẢN PHẨM</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Danh mục sản phẩm</li>
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
                    <a href="{{ route('category.create') }}" class="btn btn-primary waves-effect waves-light"><i class="bx bx-plus"></i> Tạo mới danh mục</a>
                    <form action="" class="form-inline" style="display: inline-block; margin-right: 5px;">
                        @csrf
                        @method('GET')
                        <div class="form-group">
                            <input name="key" class="form-control" placeholder="Tìm kiếm tên danh mục..." style="width: auto;" />
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                </p>

                <table id="Tabledatatable" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                    <tr>
                        <th style="width: 10px">STT</th>
                        <th>Tên danh mục</th>
                        <th>Danh mục cha</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        {{-- <th>Số lượng sản phẩm</th> --}}
                        <th style="width: 40px">Chức năng</th>
                    </tr>
                    </thead>


                    <tbody>
                        @php
                        $count = 1; // Khởi tạo biến đếm
                        @endphp
                        @foreach($categories as $category)
                        <tr>
                            <td class="text-center">{{ $count++ }}</td>
                            <td>{{ $category->name_category }}</td>
                            <td> @if($category->id_parent)
                                {{ $category->parentCategory->name_category }}
                                @endif</td>

                            <td>@if($category->status_category == 1)
                                <small class="label label-success badge-pill">Hoạt động</small>
                                @elseif($category->status_category == 0)
                                <small class="label label-warning badge-pill">Ngừng hoạt động</small>
                                @else
                                Trạng thái không xác định
                                @endif</td>
                                <td>{{ \Carbon\Carbon::parse($category->created_at)->format('d/m/Y H:i:s') }}</td>
                                {{-- <td>{{ $category->products_count }}</td> --}}
                            <td>

                                 <div style="display:flex;gap:10px">
                                    <a class="btn btn-primary" href="{{ route('category.edit', $category->id_category) }}"><i class="bx bx-edit"></i> Chỉnh sửa</a>
                                    <form id="deleteForm_{{ $category->id_category }}" action="{{ route('category.destroy', $category) }}" method="POST"
                                            style="display: inline;">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-danger delete-category" data-id="{{ $category->id_category }}"> Xóa</button>
                                        </form>
                                 </div>

                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $categories->appends(request()->all())->links() }}
            </div>
            <!-- end card-body -->
        </div>
        <!-- end card -->
    </div> <!-- end col -->
</div>
{{-- <style>
    .custom-swal-popup {
    background-color: #ffffff !important; /* Thiết lập màu nền của cửa sổ thông báo thành màu trắng */
}
</style> --}}

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
