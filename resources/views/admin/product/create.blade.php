@extends('admin.master')
@section('title','Thêm Sản Phẩm')
@section('main-content')
<style>
    #imageContainer {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .avt-img {
        width: 250px; /* Adjust the width as needed */
        height: auto; /* Maintain aspect ratio */
        border: 1px solid #ccc;
        margin-bottom: 10px;
    }
    /* .button-add-avt, .remove-add-avt {
        background-color: red ;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
    } */

    /* #chooseBtn, #removeBtn {
        margin-top: 10px;
    } */
</style>

{{-- <style>
    #multiImageContainer {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .choose-multi-img {
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
        border: 1px dashed #007bff;
        padding: 20px;
        border-radius: 5px;
    }
    .choose-multi-img label {
        cursor: pointer;
        color: #007bff;
    }
    .choose-multi-img input {
        display: none;
    }
    #imagePreview {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }
    .preview-image {
        width: 50px; /* Điều chỉnh chiều rộng ảnh */
        height: 50px; /* Điều chỉnh chiều cao ảnh */
        border: 1px solid #ccc;
        border-radius: 5px;
        object-fit: cover;
    }
</style> --}}

<style>
    /* Đặt kích thước cho các hình ảnh */
    #imagePreview img {
        width: 100px;
        height: 100px;
        margin: 5px; /* Để tạo khoảng cách giữa các hình ảnh */
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
            <h4 class="mb-sm-0 font-size-16 fw-bold">THÊM MỚI SẢN PHẨM</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{route('product.index')}}">Danh sách sản phẩm</a></li>
                    <li class="breadcrumb-item active">Thêm mới sản phẩm</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container">
    {{-- @if ($errors->any())
@foreach ($errors->all() as $error)
<div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
    {{ $error }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endforeach
@endif --}}
    <form method="post" action="{{ route('product.store') }}" enctype="multipart/form-data"
        onsubmit="return validateForm()">
        @csrf
        <div bis_skin_checked="1" class="mb-3">
            <button class="btn btn-success" type="submit"><i class="bx bx-save"></i> Lưu sản phẩm</button>
            <a href="{{ route('product.index') }}" class="btn btn-danger"><i class="bx bx-x-circle"></i> Huỷ bỏ</a>
        </div>
        <br>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row" bis_skin_checked="1">
                            <div class="col-md-12" bis_skin_checked="1">
                                <div class="mb-3" bis_skin_checked="1">
                                    <label for="name_product" class="form-label">Tên sản phẩm</label>
                                    <input type="text" class="form-control" id="name_product" name="name_product"
                                        value="{{ old('name_product') }}">
                                </div>
                                @error('name_product')
                                    <p class="alert alert-danger"> {{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-md-6" bis_skin_checked="1">
                                <br>
                                <div class="mb-3" bis_skin_checked="1">
                                    <label for="sku" class="form-label">Mã sản phẩm</label>
                                    <a style="float: right;font-weight:600;color:rgb(0, 116, 194);cursor: pointer;"
                                        onclick="generateSKU()">Tự động tạo mã</a>
                                    <input type="text" class="form-control" id="sku" name="sku"
                                        value="{{ old('sku') }}">

                                </div>
                                @error('sku')
                                    <p class="alert alert-danger"> {{ $message }}</p>
                                @enderror
                            </div>
                            {{-- <div class="col-md-6" bis_skin_checked="1">
                                <div class="mb-3" bis_skin_checked="1">
                                    <label for="link_product" class="form-label">Liên kết đường dẫn</label>
                                    <input type="text" class="form-control" id="link_product"
                                        value="{{ old('link_product') }}" name="link_product" readonly>
                                </div>
                            </div> --}}


                        </div>

                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row" bis_skin_checked="1">
                            <div class="col-md-12" bis_skin_checked="1">
                                <br>
                                <label for="variant" class="form-label">Các thuộc tính</label>
                                <div class="mb-3">
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <select class="form-control select2" id="colorSelect">
                                                <option value="" disabled selected hidden>Chọn màu</option>
                                                @foreach ($colors as $color)
                                                    <option value="{{ $color->id_color }}">{{ $color->desc_color }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-control select2" id="sizeSelect">
                                                <option value="" disabled selected hidden>Chọn kích thước</option>
                                                @foreach ($sizes as $size)
                                                    <option value="{{ $size->id_size }}">{{ $size->desc_size }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 " style="gap: 20px">
                                            <input class="form-control" type="number" id="quantity"
                                                placeholder="Số lượng">

                                        </div>
                                        <div class="col-md-3 d-flex">
                                            <a class="btn btn-primary fw-bold" onclick="addVariant()">Thêm</a>
                                        </div>
                                    </div>
                                    <br>
                                    <div id="variants">
                                    </div>
                                    <div id="variantsInput">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- fsdfsdfdsfsdf --}}
                <div class="card">
                    <div class="card-body">
                        <div class="row" bis_skin_checked="1">
                            <div class="col-md-12" bis_skin_checked="1">
                            <div class="mb-3" bis_skin_checked="1">
                                <label for="sortdect_product" class="form-label">Mô tả ngắn</label>
                                <textarea class="form-control ckeditor" id="sortdect_product" name="sortdect_product" rows="8 ">{{ old('sortdect_product') }}</textarea>
                        </div>
                            <br>
                        </div>
                            <div class="mb-3" bis_skin_checked="1">
                                <label for="desc_product" class="form-label">Mô tả sản phẩm</label>
                                <textarea class="form-control ckeditor" id="desc_product" name="desc_product" rows="8">{{ old('desc_product') }}</textarea>
                            </div>
                            @error('desc_product')
                                <p class="alert alert-danger"> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>


            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row" bis_skin_checked="1">
                            <div class="col-md-12" bis_skin_checked="1">
                                <div class="mb-3" bis_skin_checked="1">
                                    <label for="category" class="form-label">Danh mục sản phẩm</label>
                                    <select class="form-control select2" name="id_category" id="category">
                                        <option value="" disabled selected hidden>Chọn danh mục</option>
                                        @foreach ($categoriesTree as $category)
                                            <option value="{{ $category->id_category }}">{{ $category->name_category }}
                                            </option>
                                            @if ($category->children)
                                                @foreach ($category->children as $child)
                                                    @include('admin.product.child_category', [
                                                        'child' => $child,
                                                        'prefix' => '-',
                                                    ])
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </select>

                                </div>
                                @error('id_category')
                                    <p class="alert alert-danger"> {{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <br>
                                    <label for="price_product" class="form-label">Giá bán</label>
                                    <input type="text" value="{{ old('price_product') }}"
                                        oninput="formatNumber(this); calculateDiscount();" class="form-control"
                                        id="price_product" name="price_product" value="{{ old('price_product') }}">

                                </div>
                                @error('price_product')
                                    <p class="alert alert-danger"> {{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <br>
                                    <label for="discount_percent" class="form-label">% Giảm</label>
                                    <input type="text" value="{{ old('discount_percent') }}"
                                        oninput="formatNumber(this); calculateDiscount();" class="form-control"
                                        id="discount_percent" name="discount_percent" value="{{ old('discount_percent') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <br>
                                    <label for="sellprice_product" class="form-label">Giá giảm</label>
                                    <input type="text" value="{{ old('sellprice_product') }}"
                                        oninput="formatNumber(this); calculateSellPrice();" class="form-control"
                                        id="sellprice_product" name="sellprice_product" value="{{ old('sellprice_product') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row" bis_skin_checked="1">
                            <div class="col-md-12" bis_skin_checked="1">
                                <div class="mb-3">
                                    <br>
                                    <label for="desc_product" class="form-label">Hình đại diện sản phẩm</label>
                                    <div id="imageContainer">
                                        <input type="text" id="avt_product" name="avt_product" placeholder="Nhập URL ảnh" style="margin-top: 10px; width: 80%;">
                                        <br>
                                        <img id="productImage" width="200" height="200" style="object-fit: contain; display:none;"
                                        src="" alt="Hình ảnh không tồn tại">
                                    </div>
                                    @error('avt_product')
                                    <p class="alert alert-danger"> {{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row" bis_skin_checked="1">
                            {{-- <div class="col-md-12" bis_skin_checked="1">
                                <div class="mb-3" id="multiImageWrapper">
                                    <br>
                                    <label for="image_product" class="form-label">Album ảnh sản phẩm</label>
                                    <div id="multiImageContainer">
                                        <div class="choose-multi-img">
                                            <br>
                                            <label for="image_product">Nhấn để tải ảnh lên</label>
                                            <input type="file" class="form-control" id="image_product"
                                                name="image_product[]"
                                                onchange="previewMultipleImages(event, 'image_product', 'imagePreview')"
                                                accept="image/*" multiple>
                                        </div>
                                        <div id="imagePreview"></div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-md-12" bis_skin_checked="1">
                                <div class="mb-3" id="multiImageWrapper">
                                    <br>
                                    <label for="image_product">Album ảnh sản phẩm</label>
                                    <div id="multiImageContainer">
                                        <div class="choose-multi-img">
                                            {{-- <label >Nhập URL ảnh</label> --}}
                                            <input type="text" id="image_product" name="image_product[]" placeholder="Nhập URL ảnh"
                                            style="margin-top: 10px; width: 100%;" multiple="multiple" accept="image/*">

                                        </div>
                                        <br>
                                        <button type="button" id="addImageButton">Thêm ảnh</button>
                                        <div id="imagePreview"></div>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('image_product')
                                    <p class="alert alert-danger"> {{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
{{-- =============================================================== --}}

<script>
    CKEDITOR.replace('sortdect_product');
    CKEDITOR.replace('desc_product');
</script>

{{-- =============================================================== --}}
{{-- =======================HÌNH ẢNH CHÍNH===================================== --}}

<script>
    document.getElementById('avt_product').addEventListener('input', function() {
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
        deleteButton.style.top = '55px';
        deleteButton.style.right = '80px';
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
        document.getElementById('avt_product').value = '';
        });

        // Thêm nút xóa vào phần tử chứa hình ảnh
        imageContainer.appendChild(deleteButton);

    }
});
</script>

{{-- =============================================================== --}}
{{-- ======================ALBUM ẢNH================================= --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addImageButton').addEventListener('click', function() {
        var imageUrlInput = document.getElementById('image_product');
        var imageUrl = imageUrlInput.value.trim();
        var imagePreview = document.getElementById('imagePreview');
        var imageCount = imagePreview.querySelectorAll('img').length; // Đếm số lượng ảnh đã thêm

        // Biểu thức chính quy kiểm tra URL hợp lệ
        var urlPattern = /^(https?|ftp):\/\/[^\s/$.?#].[^\s]*$/i;

        if (imageCount >= 5) {
            showErrorToast('Bạn chỉ được phép thêm tối đa 5 ảnh.');
            return; // Dừng lại nếu đã thêm đủ 5 ảnh
        }

        if (imageUrl === '') {
            showErrorToast('Vui lòng nhập URL ảnh trước khi thêm.');
        } else if (!urlPattern.test(imageUrl)) {
            showErrorToast('Vui lòng nhập một URL hợp lệ.');
        } else if (isDuplicateImage(imageUrl)) {
            showErrorToast('Ảnh này đã tồn tại trong album.');
            imageUrlInput.value = '';
        } else {
            var imgContainer = document.createElement('div');
            imgContainer.style.display = 'inline-block';
            imgContainer.style.position = 'relative';
            imgContainer.style.margin = '5px';

            var img = document.createElement('img');
            img.src = imageUrl;
            img.style.width = '150px';
            img.style.height = '150px';
            img.style.display = 'block';

            var deleteButton = document.createElement('button');
            deleteButton.textContent = 'Xóa';
            deleteButton.style.position = 'absolute';
            deleteButton.style.top = '5px';
            deleteButton.style.right = '5px';
            deleteButton.style.backgroundColor = 'red';
            deleteButton.style.color = 'white';
            deleteButton.style.border = 'none';
            deleteButton.style.borderRadius = '3px';
            deleteButton.style.padding = '5px';
            deleteButton.style.cursor = 'pointer';
            deleteButton.addEventListener('click', function() {
                imgContainer.remove();
                removeHiddenInput(imageUrl); // Xóa input ẩn tương ứng
            });

            imgContainer.appendChild(img);
            imgContainer.appendChild(deleteButton);
            imagePreview.appendChild(imgContainer);

            // Tạo input ẩn để lưu trữ URL ảnh
            var hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'image_product[]';
            hiddenInput.value = imageUrl;
            imagePreview.appendChild(hiddenInput);

            imageUrlInput.value = '';
        }
    });
});

function showErrorToast(message) {
    toastr.error(message);
}
function isDuplicateImage(url) {
    var images = document.querySelectorAll('#imagePreview img');
    for (var i = 0; i < images.length; i++) {
        if (images[i].src === url) {
            return true;
        }
    }
    return false;
}
function removeHiddenInput(url) {
    var hiddenInputs = document.querySelectorAll('input[type="hidden"][name="image_product[]"]');
    for (var i = 0; i < hiddenInputs.length; i++) {
        if (hiddenInputs[i].value === url) {
            hiddenInputs[i].remove();
            break;
        }
    }
}
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- =============================================================== --}}
    {{-- =============================================================== --}}
    {{-- HÀM RANDOM MÃ SẢN PHẨM --}}
    {{-- =============================================================== --}}
    {{-- =============================================================== --}}
    <script>
        function generateSKU() {
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            const length = 8; // Độ dài của mã sản phẩm

            let sku = '';
            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length);
                sku += characters.charAt(randomIndex);
            }

            // Đặt giá trị mã sản phẩm vào input
            document.getElementById('sku').value = sku;
        }
    </script>
    {{-- =============================================================== --}}
    {{-- =============================================================== --}}
    {{-- HÀM LOẠI BỎ CÁC KÝ TỰ CHO LINK PRODUCT --}}
    {{-- =============================================================== --}}
    {{-- =============================================================== --}}
    {{-- =============================================================== --}}
    {{-- <script>
        $(document).ready(function() {
            $('#name_product').on('input', function() {
                var nameCategory = $(this).val();
                var linkCategory = nameCategory
                    .toLowerCase()
                    .normalize("NFD") // Sử dụng Unicode normalization để loại bỏ dấu
                    .replace(/[\u0300-\u036f]/g, "") // Loại bỏ các ký tự diacritic
                    .replace(/[^\w\s]/gi, '-') // Thay thế các ký tự không phải chữ cái hoặc số bằng dấu '-'
                    .replace(/\s+/g, '-') // Thay thế khoảng trắng bằng dấu '-'
                    .replace(/-+/g, '-') // Loại bỏ các dấu '-' liên tiếp
                    .replace(/^-|-$/g, ''); // Loại bỏ dấu '-' ở đầu và cuối chuỗi
                $('#link_product').val(linkCategory);
            });
        });
    </script> --}}

    {{-- =============================================================== --}}
    {{-- FORMAT SỐ VÀ TÍNH % GIẢM GIÁ --}}
    {{-- =============================================================== --}}
    {{-- =============================================================== --}}
    <script>
        function formatNumber(input) {
            let value = input.value.replace(/\D/g, '');
            if (value !== '') {
                value = new Intl.NumberFormat('en-US').format(value);
            }
            input.value = value;
        }

            function calculateDiscount() {
            const priceInput = document.getElementById('price_product');
            const discountInput = document.getElementById('discount_percent');
            const sellPriceInput = document.getElementById('sellprice_product');

            let price = parseFloat(priceInput.value.replace(/,/g, ''));
            let discountPercent = parseFloat(discountInput.value.replace(/,/g, ''));

            // Kiểm tra nếu price không có giá trị hoặc là NaN
            if (!price || isNaN(price)) {
                // Nếu price không có giá trị, clear discount_percent và sellprice_product
                discountInput.value = '';
                sellPriceInput.value = '';
                return;
            }

            // Kiểm tra nếu discountPercent lớn hơn 100 hoặc nhỏ hơn 0, hoặc không phải là một số
            if (discountInput.value.trim() !== '' && (isNaN(discountPercent) || discountPercent > 100 || discountPercent < 0)) {
                $.toast({
                    heading: 'Cảnh báo',
                    text: 'Vui lòng nhập giá trị từ 0 đến 100',
                    hideAfter: 3000,
                    icon: 'error',
                    position: 'top-right',
                    loader: false,
                });
                discountInput.value = ''; // Xóa giá trị nhập nếu không hợp lệ
                sellPriceInput.value = '';
                return;
            }

            if (!isNaN(price) && !isNaN(discountPercent) && price !== 0) {
                let sellPrice = price - (price * discountPercent / 100);
                sellPriceInput.value = sellPrice.toLocaleString('en-US');
            }
        }
        function calculateSellPrice() {
        const priceInput = document.getElementById('price_product');
        const discountInput = document.getElementById('discount_percent');
        const sellPriceInput = document.getElementById('sellprice_product');

        let price = parseFloat(priceInput.value.replace(/,/g, ''));
        let sellPrice = parseFloat(sellPriceInput.value.replace(/,/g, ''));

        if (!isNaN(price) && !isNaN(sellPrice)) {
            if (sellPrice > price) {
                // Nếu sellPrice lớn hơn price, reset sellPrice và discountPercent
                $.toast({
                    heading: 'Cảnh báo',
                    text: 'Giá giảm không thể lớn hơn giá bán',
                    hideAfter: 3000,
                    icon: 'error',
                    position: 'top-right',
                    loader: false,
                });
                sellPriceInput.value = ''; // Reset sellPrice thành null
                discountInput.value = ''; // Reset discountPercent thành null
                return;
            }

            let discountPercent = ((price - sellPrice) / price) * 100;
            // Làm tròn số về nguyên
            discountInput.value = Math.round(discountPercent).toLocaleString('en-US');
        }
    }
 // Khi trang tải xong, tính toán giá giảm tự động
    document.addEventListener('DOMContentLoaded', function() {
                calculateDiscount();
                calculateSellPrice(); // Tính toán và hiển thị % giảm khi trang tải xong
            });
        </script>
    {{-- =============================================================== --}}
    {{-- =============================================================== --}}
    {{-- HÀM DÀNH CHO ẢNH ĐẠI DIỆN --}}
    {{-- =============================================================== --}}
    {{-- =============================================================== --}}
    {{-- <script>
        function previewImage(event) {
            const reader = new FileReader();
            const preview = document.getElementById('preview');
            const removeBtn = document.getElementById('removeBtn');
            const chooseBtn = document.getElementById('chooseBtn');
            const imageWrapper = document.getElementById('imageWrapper');

            reader.onload = function() {
                preview.src = reader.result;
                preview.style.display = 'block';
                removeBtn.style.display = 'block';
                chooseBtn.style.display = 'none';
                imageWrapper.style.paddingTop = '0'; // Ẩn khoảng trắng dư thừa khi hiển thị hình ảnh
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        function removeImage() {
            const preview = document.getElementById('preview');
            const removeBtn = document.getElementById('removeBtn');
            const fileInput = document.getElementById('avt_product');
            const imageContainer = document.getElementById('imageContainer');
            const imageWrapper = document.getElementById('imageWrapper');

            preview.src = '{{ URL::to('backend_area/assets/images/empty.jpg') }}';
            preview.style.display = 'block';
            removeBtn.style.display = 'none';
            chooseBtn.style.display = 'block';
            fileInput.value = ''; // Xóa giá trị đã chọn trong input file
            imageContainer.style.display = 'block';
            imageWrapper.style.paddingTop = '10px'; // Hiển thị khoảng trắng dư thừa khi không có hình
        }
    </script> --}}
    {{-- =============================================================== --}}
    {{-- =============================================================== --}}
    {{-- HÀM DÀNH CHO ALBUM ẢNH --}}
    {{-- =============================================================== --}}
    {{-- =============================================================== --}}
    {{-- <script>
        function previewMultipleImages(event, inputId, previewId) {
            const files = event.target.files;
            const imagePreview = document.getElementById(previewId);

            if (files && files.length > 8) {
                $.toast({
                    heading: 'Cảnh báo',
                    text: 'Chỉ được chọn tối đa 8 ảnh',
                    hideAfter: 3000,
                    icon: 'error',
                    position: 'top-right',
                    loader: false,
                });
                document.getElementById(inputId).value = '';
                return;
            }

            if (files) {
                const existingImages = imagePreview.querySelectorAll('.imgmulti').length;
                const remainingSlots = 8 - existingImages;

                if (files.length > remainingSlots) {
                    $.toast({
                        heading: 'Cảnh báo',
                        text: `Bạn chỉ còn thêm được ${remainingSlots} hình ảnh`,
                        hideAfter: 3000,
                        icon: 'error',
                        position: 'top-right',
                        loader: false,
                    });
                    return;
                }

                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;

                        const removeBtn = document.createElement('button');
                        removeBtn.textContent = 'Xoá';
                        removeBtn.onclick = function() {
                            imagePreview.removeChild(divImgMulti);
                            document.getElementById(inputId).value = '';
                        };

                        const divImgMulti = document.createElement('div');
                        divImgMulti.classList.add('imgmulti');
                        divImgMulti.appendChild(img);
                        divImgMulti.appendChild(removeBtn);

                        imagePreview.appendChild(divImgMulti);
                    };
                    reader.readAsDataURL(files[i]);
                }
            }
        }
    </script> --}}
    {{-- =============================================================== --}}
    {{-- =============================================================== --}}
    {{-- CÁC HÀM DÀNH CHO BIẾN THỂ --}}
    {{-- =============================================================== --}}
    {{-- =============================================================== --}}
    <script>
        let selectedVariants = [];
        let prevValidValue = '';

        function addVariant() {
            const colorSelect = document.getElementById('colorSelect');
            const sizeSelect = document.getElementById('sizeSelect');
            const quantityInput = document.getElementById('quantity');

            const selectedColor = colorSelect.value;
            const selectedSize = sizeSelect.value;
            const quantity = quantityInput.value;

            // Tạo một đối tượng JSON chứa thông tin biến thể
            const variantData = {
                color: selectedColor,
                size: selectedSize,
                quantity: quantity
            };


            // Kiểm tra có giá trị nào được chọn chưa
            if (!selectedColor || !selectedSize || !quantity) {
                $.toast({
                    heading: 'Cảnh báo',
                    text: 'Vui lòng chọn màu, kích thước và nhập số lượng',
                    hideAfter: 3000,
                    icon: 'error',
                    position: 'top-right',
                    loader: false,
                });
                return;
            }

            if (selectedColor === '0' || selectedSize === '0') {
                $.toast({
                    heading: 'Cảnh báo',
                    text: 'Vui lòng chọn màu và kích thước',
                    hideAfter: 3000,
                    icon: 'error',
                    position: 'top-right',
                    loader: false,
                });
                return;
            }

            if (quantity <= 0 || isNaN(quantity)) {
                $.toast({
                    heading: 'Cảnh báo',
                    text: 'Số lượng phải là số dương và không được là 0.',
                    hideAfter: 3000,
                    icon: 'error',
                    position: 'top-right',
                    loader: false,
                });
                return;
            }

            const exists = selectedVariants.find(variant => variant.color === selectedColor && variant.size ===
                selectedSize);

            if (exists) {
                $.toast({
                    heading: 'Cảnh báo',
                    text: 'Màu và kích thước này đã được chọn',
                    hideAfter: 3000,
                    icon: 'error',
                    position: 'top-right',
                    loader: false,
                });
            } else {
                selectedVariants.push({
                    color: selectedColor,
                    size: selectedSize,
                    quantity: quantity
                });

                const variantsContainer = document.getElementById('variants');
                const variantRow = document.createElement('div');
                variantRow.classList.add('variant-row');

                const colorDiv = document.createElement('div');
                colorDiv.classList.add('color-name');
                colorDiv.textContent = `${colorSelect.options[colorSelect.selectedIndex].text}`;

                const sizeDiv = document.createElement('div');
                sizeDiv.classList.add('size-name');
                sizeDiv.textContent = `${sizeSelect.options[sizeSelect.selectedIndex].text}`;

                const quantityInputDiv = document.createElement('input');
                quantityInputDiv.classList.add('quantity-input');
                quantityInputDiv.setAttribute('type', 'text');
                quantityInputDiv.setAttribute('value', quantity);
                quantityInputDiv.setAttribute('readonly', 'true');

                quantityInputDiv.addEventListener('input', function(event) {
                    const inputValue = event.target.value;
                    if (!(/^\d*\.?\d*$/.test(inputValue))) {
                        $.toast({
                            heading: 'Cảnh báo',
                            text: 'Vui lòng nhập số.',
                            hideAfter: 3000,
                            icon: 'error',
                            position: 'top-right',
                            loader: false,
                        });
                        event.target.value =
                        prevValidValue; // Sử dụng giá trị trước đó nếu giá trị nhập không hợp lệ
                    } else {
                        prevValidValue = inputValue; // Lưu trữ giá trị hợp lệ
                    }
                });

                const editButton = document.createElement('a');
                editButton.classList.add('edit-quantity');
                editButton.textContent = 'Chỉnh sửa';

                const removeButton = document.createElement('a');
                removeButton.classList.add('remove-variant');
                removeButton.textContent = 'Xóa';


                const confirmButton = document.createElement('a');
                confirmButton.classList.add('confirm-edit');
                confirmButton.textContent = 'Xác nhận';
                confirmButton.style.display = 'none';

                const cancelButton = document.createElement('a');
                cancelButton.classList.add('cancel-edit');
                cancelButton.textContent = 'Hủy bỏ';
                cancelButton.style.display = 'none';


                variantRow.appendChild(colorDiv);
                variantRow.appendChild(sizeDiv);
                variantRow.appendChild(quantityInputDiv);
                variantRow.appendChild(editButton);
                variantRow.appendChild(removeButton);
                variantRow.appendChild(confirmButton);
                variantRow.appendChild(cancelButton);

                // Tạo một input ẩn để lưu thông tin biến thể và thêm vào form
                const variantsInput = document.getElementById('variantsInput');
                const variantInput = document.createElement('input');
                variantInput.setAttribute('type', 'hidden');
                variantInput.setAttribute('name', 'variants[]'); // Đặt tên cho biến thể để server có thể nhận diện
                variantInput.setAttribute('value', JSON.stringify(
                variantData)); // Lưu thông tin biến thể dưới dạng chuỗi JSON
                variantsInput.appendChild(variantInput);

                variantsContainer.appendChild(variantRow);
                // Reset các dropdown và input sau khi thêm
                colorSelect.value = '0';
                sizeSelect.value = '0';
                quantityInput.value = '';

                // Làm mới lại các Select2
                $('#colorSelect').val(null).trigger('change');
                $('#sizeSelect').val(null).trigger('change');
            }
        }

        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('edit-quantity')) {
                const parentRow = e.target.parentNode;
                const quantityInput = parentRow.querySelector('.quantity-input');
                const editButton = parentRow.querySelector('.edit-quantity');
                const removeButton = parentRow.querySelector('.remove-variant');
                const cancelButton = parentRow.querySelector('.cancel-edit');
                const confirmButton = parentRow.querySelector('.confirm-edit');

                editButton.style.display = 'none';
                removeButton.style.display = 'none';
                cancelButton.style.display = 'inline-block';
                confirmButton.style.display = 'inline-block';

                quantityInput.removeAttribute('readonly');
            }

            if (e.target && e.target.classList.contains('cancel-edit')) {
                const parentRow = e.target.parentNode;
                const quantityInput = parentRow.querySelector('.quantity-input');
                const editButton = parentRow.querySelector('.edit-quantity');
                const removeButton = parentRow.querySelector('.remove-variant');
                const cancelButton = parentRow.querySelector('.cancel-edit');
                const confirmButton = parentRow.querySelector('.confirm-edit');

                editButton.style.display = 'inline-block';
                removeButton.style.display = 'inline-block';
                cancelButton.style.display = 'none';
                confirmButton.style.display = 'none';

                quantityInput.setAttribute('readonly', 'true');
                quantityInput.value = quantityInput.defaultValue;
            }

            if (e.target && e.target.classList.contains('confirm-edit')) {
                const parentRow = e.target.parentNode;
                const quantityInput = parentRow.querySelector('.quantity-input');
                const editButton = parentRow.querySelector('.edit-quantity');
                const removeButton = parentRow.querySelector('.remove-variant');
                const cancelButton = parentRow.querySelector('.cancel-edit');
                const confirmButton = parentRow.querySelector('.confirm-edit');

                const inputValue = quantityInput.value;

                if (inputValue === '0') {
                    $.toast({
                        heading: 'Cảnh báo',
                        text: 'Không thể lưu giá trị 0.',
                        hideAfter: 3000,
                        icon: 'error',
                        position: 'top-right',
                        loader: false,
                    });
                } else {
                    editButton.style.display = 'inline-block';
                    removeButton.style.display = 'inline-block';
                    cancelButton.style.display = 'none';
                    confirmButton.style.display = 'none';

                    quantityInput.setAttribute('readonly', 'true');
                    quantityInput.defaultValue = inputValue;
                    prevValidValue = inputValue;
                }
            }
            if (e.target && e.target.classList.contains('remove-variant')) {
                const parentRow = e.target.parentNode;
                const colorDiv = parentRow.querySelector('.color-name').textContent;
                const sizeDiv = parentRow.querySelector('.size-name').textContent;


                const confirmDelete = Swal.fire({
                    title: "Thông báo",
                    text: `Bạn có chắc chắn muốn xóa biến thể có màu ${colorDiv} và kích thước ${sizeDiv} không?`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#34c3af",
                    cancelButtonColor: "#f46a6a",
                    confirmButtonText: "Đồng ý xoá",
                    cancelButtonText: "Huỷ bỏ",
                    customClass: {
                    popup: 'custom-swal-popup'}// Đặt lớp CSS tùy chỉnh cho cửa sổ thông báo

                }).then((result) => {
                    if (result.isConfirmed) {
                        parentRow.remove(); // Xóa hàng biến thể

                        // Xóa thông tin biến thể khỏi mảng selectedVariants
                        const indexToRemove = selectedVariants.findIndex(variant => variant.color ===
                            colorDiv && variant.size === sizeDiv);
                        if (indexToRemove !== -1) {
                            selectedVariants.splice(indexToRemove, 1);
                        }

                        // Cảnh báo đã xóa thành công
                        $.toast({
                            heading: 'Thông báo',
                            text: `Đã xóa biến thể có màu ${colorDiv} và kích thước ${sizeDiv}.`,
                            hideAfter: 3000,
                            icon: 'success',
                            position: 'top-right',
                            loader: false,
                        });
                    }
                });

            }
        });
    </script>
    {{-- =============================================================== --}}
    {{-- =============================================================== --}}
    {{-- CÁC validateSubmit --}}
    {{-- =============================================================== --}}
    {{-- =============================================================== --}}
    <script>
        function validateForm() {
            const avtProductInput = document.getElementById('avt_product');
            const imageProductInput = document.getElementById('image_product');
            const variants = document.querySelectorAll('.variant-row');

            if (!avtProductInput || !avtProductInput.files || avtProductInput.files.length === 0) {
                $.toast({
                    heading: 'Lỗi',
                    text: 'Vui lòng chọn hình đại diện sản phẩm.',
                    hideAfter: 3000,
                    icon: 'error',
                    position: 'top-right',
                    loader: false,
                });
                return false; // Ngăn form submit nếu chưa chọn hình đại diện
            }
            if (!variants || variants.length === 0) {
                $.toast({
                    heading: 'Lỗi',
                    text: 'Vui lòng thêm ít nhất một thuộc tính màu và kích thước.',
                    hideAfter: 3000,
                    icon: 'error',
                    position: 'top-right',
                    loader: false,
                });
                return false; // Ngăn form submit nếu chưa thêm thuộc tính màu và kích thước
            }
            if (!imageProductInput || !imageProductInput.files || imageProductInput.files.length === 0) {
                $.toast({
                    heading: 'Lỗi',
                    text: 'Vui lòng chọn album ảnh sản phẩm.',
                    hideAfter: 3000,
                    icon: 'error',
                    position: 'top-right',
                    loader: false,
                });
                return false; // Ngăn form submit nếu chưa chọn album ảnh sản phẩm
            }

            return true; // Cho phép submit form nếu đã chọn cả hình đại diện và album ảnh sản phẩm
        }
    </script>

    <script>
        // Hàm ẩn thông báo sau một khoảng thời gian
        function hideAlert() {
            document.querySelectorAll('.alert').forEach(alert => {
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 4000);
            });
        }

        // Gọi hàm ẩn thông báo khi trang được tải
        window.onload = function() {
            hideAlert();
        };
    </script>
@endsection
