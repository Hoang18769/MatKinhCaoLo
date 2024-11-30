@extends('admin.master')
@section('title','Chỉnh Sửa Danh Mục Sản Phẩm')
@section('main-content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
            <h4 class="mb-sm-0 font-size-16 fw-bold ">CHỈNH SỬA DANH MỤC SẢN PHẨM</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{route('category.index')}}">Danh mục sản phẩm</a></li>
                    <li class="breadcrumb-item active">Chỉnh sửa danh mục sản phẩm</li>
                </ol>
            </div>
        </div>
    </div>
</div>


<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @error('name_category')
        <p class="alert alert-danger"> {{ $message }}</p>
        @enderror
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('category.update', $category->id_category) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id_category" value="{{$category->id_category}}">
                        <div class="row" bis_skin_checked="1">
                            <div class="col-md-12" bis_skin_checked="1">
                                <div class="mb-3" bis_skin_checked="1">
                                    <label for="name" class="form-label">Tên danh mục</label>
                                    <input type="text" class="form-control" id="name_category" name="name_category" value="{{ old('name_category') ? : $category->name_category }}">
                                </div>
                            </div>

                        </div>
                        <div class="row" bis_skin_checked="1">
                            <div class="col-md-12" bis_skin_checked="1">
                                <div class="mb-3" bis_skin_checked="1">
                                    <label for="parent" class="form-label">Danh mục cha</label>
                                    <select class="form-control select2" name="id_parent">
                                        <option value="">Không có</option>
                                        @foreach($parentCategories as $parentCategory)
                                        @if($parentCategory->id_category != $category->id_category)
                                            <option value="{{ $parentCategory->id_category }}" {{ ($parentCategory->id_category == $category->id_parent) ? 'selected' : '' }}>
                                                {{ $parentCategory->name_category }}
                                            </option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12" bis_skin_checked="1">
                                <div class="mb-3" bis_skin_checked="1">
                                    <label for="parent" class="form-label">Trạng thái</label>
                                    <div class="form-check form-switch form-switch-lg mb-lg-3" dir="ltr" bis_skin_checked="1">
                                        <input class="form-check-input" type="checkbox" id="status_category" name="status_category" {{ ($category->status_category == 1) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status_category">{{ ($category->status_category == 1) ? 'Hoạt động' : 'Ngừng hoạt động' }}</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div bis_skin_checked="1">
                            <button class="btn btn-success" type="submit"><i class="bx bx-save"></i> Lưu danh mục</button>
                            <a href="{{ route('category.index') }}" class="btn btn-danger"><i class="bx bx-x-circle"></i> Huỷ bỏ</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src=" https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.all.min.js"></script>
<script>

    // Sự kiện thay đổi cho tên trạng thái
    const checkbox = document.getElementById('status_category');
    checkbox.addEventListener('change', function() {
        const label = document.querySelector('label[for="status_category"]');
        if (checkbox.checked) {
            label.textContent = 'Hoạt động';
            checkbox.value = '1'
        } else {
            label.textContent = 'Ngừng hoạt động';
            checkbox.value = '0'
        }
    });

    // Sự kiện thay đổi input cho liên kết
    $(document).ready(function() {
        $('#name_category').on('input', function() {
            var nameCategory = $(this).val();
            var linkCategory = nameCategory
                .toLowerCase()
                .normalize("NFD") // Sử dụng Unicode normalization để loại bỏ dấu
                .replace(/[\u0300-\u036f]/g, "") // Loại bỏ các ký tự diacritic
                .replace(/[^\w\s]/gi, '-') // Thay thế các ký tự không phải chữ cái hoặc số bằng dấu '-'
                .replace(/\s+/g, '-') // Thay thế khoảng trắng bằng dấu '-'
                .replace(/-+/g, '-') // Loại bỏ các dấu '-' liên tiếp
                .replace(/^-|-$/g, ''); // Loại bỏ dấu '-' ở đầu và cuối chuỗi
            $('#link_category').val(linkCategory);
        });
    });

</script>

@endsection
