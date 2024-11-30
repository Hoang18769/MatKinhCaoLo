@extends('admin.master')
@section('title','Thêm Danh Mục Sản Phẩm')
@section('main-content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
            <h4 class="mb-sm-0 font-size-16 fw-bold">THÊM MỚI DANH MỤC SẢN PHẨM</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{route('category.index')}}">Danh mục sản phẩm</a></li>
                    <li class="breadcrumb-item active">Thêm mới danh mục sản phẩm</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            {{-- @if ($errors->any())
        @foreach ($errors->all() as $error)
        <div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
            {{ $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endforeach
        @endif --}}
            @error('name_category')
                <p class="alert alert-danger"> {{ $message }}</p>
            @enderror
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('category.store') }}">
                        @csrf
                        <div class="row" bis_skin_checked="1">
                            <div class="col-md-12" bis_skin_checked="1" >
                                <div class="mb-3" bis_skin_checked="1">
                                    <label for="name" class="form-label">Tên danh mục</label>
                                    <input type="text" class="form-control" id="name_category" name="name_category">
                                </div>
                            </div>
                            <div class="col-md-12" bis_skin_checked="1">
                                <div class="mb-3" bis_skin_checked="1">
                                    <br>
                                    <label for="parent" class="form-label">Danh mục cha</label>
                                    <select class="form-select select2" id="parent" name="id_parent">
                                        <option value="">Không có</option>
                                        @foreach ($parentCategories as $parentCategory)
                                            <option value="{{ $parentCategory->id }}">
                                                {{ $parentCategory->name_category }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div bis_skin_checked="1">
                            <button class="btn btn-success" type="submit"><i class="bx bx-save"></i> Lưu danh
                                mục</button>
                            <a href="{{ route('category.index') }}" class="btn btn-danger"><i
                                    class="bx bx-x-circle"></i> Huỷ bỏ</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#name_category').on('input', function() {
            // Lấy giá trị từ input 'name_category'
            var nameCategory = $(this).val();
            // Chuyển đổi chuỗi thành URL-friendly và gán giá trị cho 'link_category'
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
