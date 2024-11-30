<!-- create.blade.php -->
@extends('admin.master')
@section('title', 'Chỉnh Sửa Sản Phẩm')
@section('main-content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
                <h4 class="mb-sm-0 font-size-16 fw-bold">CHỈNH SỬA SẢN PHẨM</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Danh sách sản phẩm</a></li>
                        <li class="breadcrumb-item active">Chỉnh sửa sản phẩm</li>
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
        <form method="post" action="{{ route('product.update', $product->id_product) }}" enctype="multipart/form-data"
            onsubmit="return validateForm()">
            @csrf
            @method('PUT')
            <div bis_skin_checked="1" class="mb-3">
                <button class="btn btn-success" type="submit"><i class="bx bx-save"></i> Lưu sản phẩm</button>
                <a href="{{ route('product.index') }}" class="btn btn-danger"><i class="bx bx-x-circle"></i> Huỷ bỏ</a>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <br>
                                        <label for="name_product" class="form-label">Tên sản phẩm</label>
                                        <input type="text" class="form-control" id="name_product" name="name_product"
                                            value="{{ $product->name_product ?? old('name_product') }}">
                                    </div>
                                    @error('name_product')
                                        <p class="alert alert-danger"> {{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <br>
                                        <label for="sku" class="form-label">Mã sản phẩm</label>
                                        <a style="float: right;font-weight:600;color:rgb(0, 116, 194);cursor: pointer;"
                                            onclick="generateSKU()">Tự động tạo mã</a>
                                        <input type="text" class="form-control" id="sku" name="sku"
                                            value="{{ $product->sku ?? old('sku') }}">
                                    </div>
                                    @error('sku')
                                        <p class="alert alert-danger"> {{ $message }}</p>
                                    @enderror
                                </div>


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
                                                        <option value="{{ $size->id_size }}">{{ $size->desc_size }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3" style="gap: 20px">
                                                <input class="form-control" type="number" id="quantity"
                                                    placeholder="Số lượng">
                                                {{-- <a class="btn btn-primary fw-bold" onclick="addVariant()">Thêm</a> --}}
                                            </div>
                                            <div class="col-md-3 d-flex">
                                                <a class="btn btn-primary fw-bold" onclick="addVariant()">Thêm</a>
                                            </div>
                                        </div>

                                        <br>
                                        <div id="variants">
                                            @foreach ($product->variants as $variant)
                                                <div class="variant-row"
                                                    data-variant-id="{{ $variant->id_product_variants }}">
                                                    <div class="color-name">{{ $variant->color->desc_color }}</div>
                                                    <div class="size-name">{{ $variant->size->desc_size }}</div>
                                                    <input class="quantity-input" type="text"
                                                        value="{{ $variant->quantity }}" readonly>
                                                    <a class="edit-quantity">Chỉnh sửa</a>
                                                    <a class="remove-variant">Xóa</a>
                                                    <a class="confirm-edit" style="display: none;">Xác nhận</a>
                                                    <a class="cancel-edit" style="display: none;">Hủy bỏ</a>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div id="variantsInput">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row" bis_skin_checked="1">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <br>
                                        <label for="sortdesc_product" class="form-label">Mô tả ngắn</label>
                                        <textarea class="form-control ckeditor" id="sortdect_product" name="sortdect_product" rows="8">{{ $product->sortdect_product ?? old('sortdect_product') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <br>
                                        <label for="desc_product" class="form-label">Mô tả sản phẩm</label>
                                        <textarea class="form-control ckeditor" id="desc_product" name="desc_product" rows="8">{{ $product->desc_product ?? old('desc_product') }}</textarea>
                                    </div>
                                    @error('desc_product')
                                        <p class="alert alert-danger"> {{ $message }}</p>
                                    @enderror
                                </div>
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
                                        <label for="parent" class="form-label">Trạng thái</label>
                                        <div class="form-check form-switch form-switch-lg mb-lg-3" dir="ltr"
                                            bis_skin_checked="1">
                                            <input class="form-check-input" type="checkbox" id="status_product"
                                                name="status_product"
                                                {{ $product->status_product == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="status_product">{{ $product->status_product == 1 ? 'Hoạt động' : 'Ngừng hoạt động' }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" bis_skin_checked="1">
                                    <div class="mb-3" bis_skin_checked="1">
                                        <br>
                                        <label for="category" class="form-label">Danh mục sản phẩm</label>
                                        <select class="form-control select2" name="id_category" id="category">
                                            <option value="" disabled hidden>Chọn danh mục</option>
                                            @foreach ($categoriesTree as $category)
                                                @if ($category->id_parent)
                                                    @continue
                                                @endif
                                                <option value="{{ $category->id_category }}"
                                                    {{ $product->id_category == $category->id_category ? 'selected' : '' }}>
                                                    {{ $category->name_category }}
                                                </option>
                                                @if ($category->children)
                                                    @foreach ($category->children as $child)
                                                        <option value="{{ $child->id_category }}"
                                                            {{ $product->id_category == $child->id_category ? 'selected' : '' }}>
                                                            - {{ $child->name_category }}
                                                        </option>
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
                                        <input type="text"
                                            value="{{ number_format($product->price_product, 0, ',', ',') ?? old('price_product') }}"
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
                                            id="discount_percent" name="discount_percent">

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <br>
                                        <label for="sellprice_product" class="form-label">Giá giảm</label>
                                        <input type="text"
                                            value="{{ number_format($product->sellprice_product, 0, ',', ',') ?? old('sellprice_product') }}"
                                            oninput="formatNumber(this); calculateSellPrice();" class="form-control"
                                            id="sellprice_product" name="sellprice_product">
                                    </div>
                                    @error('sellprice_product')
                                        <p class="alert alert-danger"> {{ $message }}</p>
                                    @enderror
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
                                            <input type="text" id="avt_product" name="avt_product"
                                                placeholder="Nhập URL ảnh" style="margin-top: 10px; width: 80%;"
                                                value="{{ $product->avt_product }}"><br>
                                            <img id="productImage" width="200" height="200"
                                                style="object-fit: contain; display:none; margin-top: 10px; margin-left: 50px;"
                                                src="{{ $product->avt_product }}" alt="{{ $product->name_product }} ">
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
                                <div class="col-md-12" bis_skin_checked="1">
                                    <div class="mb-3" id="multiImageWrapper">
                                        <br>
                                        <label for="image_product" class="form-label">Album ảnh sản phẩm</label>
                                        <div id="multiImageContainer">
                                            <div>
                                                {{-- <label for="image_product">Nhấn để tải ảnh lên</label>
                                                <input type="file" class="form-control" id="image_product"
                                                    name="image_product[]"
                                                    onchange="previewMultipleImages(event, 'image_product', 'imagePreview')"
                                                    accept="image/*" multiple> --}}
                                                <input type="text" id="image_product" name="image_product[]"
                                                    placeholder="Nhập URL ảnh" style="margin-top: 10px; width: 100%;"
                                                    multiple="multiple" accept="image/*">
                                            </div>
                                            <br>
                                            <button type="button" id="addImageButton">Thêm ảnh</button>
                                            <div id="imagePreview">
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
            </div>


        </form>
    </div>
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
                deleteButton.style.top = '46px';
                deleteButton.style.right = '110px';
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
        document.addEventListener('DOMContentLoaded', function() {
            // Lấy URL ảnh từ cơ sở dữ liệu
            var imageUrl = document.getElementById('avt_product').value.trim();
            var productImage = document.getElementById('productImage');
            var imageContainer = document.getElementById('imageContainer');
            var urlPattern = /^(http(s)?:\/\/)?\S+(\.\S{2,})+(\/*)?$/;

            if (imageUrl && urlPattern.test(imageUrl)) {
                // Nếu có URL hợp lệ, hiển thị ảnh
                productImage.src = imageUrl;
                productImage.style.display = 'block';

                // Tạo nút xóa
                var deleteButton = document.createElement('button');
                deleteButton.textContent = 'Xóa';
                deleteButton.style.position = 'absolute';
                deleteButton.style.top = '46px';
                deleteButton.style.right = '110px';
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
            // Lấy URL ảnh từ cơ sở dữ liệu
            var imageUrlsFromDB = @json($product->image_product ? explode('#', $product->image_product) : []).filter(Boolean);

            var imagePreview = document.getElementById('imagePreview');

            imageUrlsFromDB.forEach(function(imageUrl) {
                addImageToPreview(imageUrl);
            });

            document.getElementById('addImageButton').addEventListener('click', function() {
                var imageUrlInput = document.getElementById('image_product');
                var imageUrl = imageUrlInput.value.trim();

                // Biểu thức chính quy kiểm tra URL hợp lệ
                var urlPattern = /^(https?|ftp):\/\/[^\s/$.?#].[^\s]*$/i;

                if (imagePreview.querySelectorAll('img').length >= 5) {
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
                    addImageToPreview(imageUrl);
                    imageUrlInput.value = '';
                }
            });

            function addImageToPreview(imageUrl) {
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
                deleteButton.style.top = '1px';
                deleteButton.style.right = '15px';
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
            }
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
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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
            if (discountInput.value.trim() !== '' && (isNaN(discountPercent) || discountPercent > 100 || discountPercent <
                    0)) {
                toastr.error('Vui lòng nhập giá trị từ 0 đến 100');
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
                    toastr.error('Giá giảm không thể lớn hơn giá bán');
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
    {{-- PHẦN PHP ĐỂ GET LẠI CÁC VARIANT --}}
    {{-- =============================================================== --}}
    {{-- =============================================================== --}}
    <?php
    // Tạo mảng trống để lưu trữ biến thể
    $selectedVariants = [];
    // Lặp qua các biến thể và thêm chúng vào mảng
    foreach ($product->variants as $variant) {
        // Chuyển các ID về dạng chuỗi và thêm chúng vào mảng biến thể
        $variantData = ['color' => strval($variant->id_color), 'size' => strval($variant->id_size), 'quantity' => strval($variant->quantity)];

        // Thêm mảng biến thể vào mảng selectedVariants
        $selectedVariants[] = $variantData;
    }
    // Chuyển đổi mảng PHP thành JSON để sử dụng trong JavaScript
    $selectedVariantsJSON = json_encode($selectedVariants);
    ?>
    {{-- =============================================================== --}}
    {{-- =============================================================== --}}
    {{-- CÁC HÀM DÀNH CHO BIẾN THỂ --}}
    {{-- =============================================================== --}}
    {{-- =============================================================== --}}
    <script>
        let selectedVariants = {!! $selectedVariantsJSON !!};
        let prevValidValue = '';
        // Lấy ra phần tử HTML của variantsInput
        const variantsInput = document.getElementById('variantsInput');

        // Duyệt qua mảng selectedVariants để hiển thị thông tin các biến thể đã tồn tại
        selectedVariants.forEach(variant => {
            const variantInput = document.createElement('input');
            variantInput.setAttribute('type', 'hidden');
            variantInput.setAttribute('name', 'variants[]');
            variantInput.setAttribute('value', JSON.stringify(variant));
            variantsInput.appendChild(variantInput);
        });

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

                toastr.error('Vui lòng chọn màu, kích thước và nhập số lượng');
                return;
            }

            if (selectedColor !== '0' && selectedSize === '0' && quantity !== '') {
                toastr.error('Vui lòng thêm kích thước');
                return;
            }

            if (selectedColor === '0' || selectedSize === '0') {

                toastr.error('Vui lòng chọn màu và kích thước');
                return;
            }

            if (quantity <= 0 || isNaN(quantity)) {

                toastr.error('Số lượng phải là số dương và không được là 0.');
                return;
            }
            // Kiểm tra xem biến thể đã tồn tại chưa
            if (selectedVariants.some(variant => variant.color === selectedColor && variant.size === selectedSize)) {

                toastr.error('Màu và kích thước này đã được chọn');
                return;
            }

            selectedVariants.push(variantData);

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
                    event.target.value = prevValidValue; // Sử dụng giá trị trước đó nếu giá trị nhập không hợp lệ
                } else {
                    prevValidValue = inputValue; // Lưu trữ giá trị hợp lệ
                }
            });

            // Tạo các nút chỉnh sửa và xóa
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
            variantInput.setAttribute('value', JSON.stringify(variantData)); // Lưu thông tin biến thể dưới dạng chuỗi JSON
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

        document.addEventListener('input', function(e) {
            if (e.target && e.target.classList.contains('quantity-input')) {
                const parentRow = e.target.parentNode;
                const variantIndex = Array.from(parentRow.parentNode.children).indexOf(parentRow);
                const newValue = e.target.value;

                // Cập nhật giá trị quantity mới vào selectedVariants
                if (variantIndex !== -1) {
                    selectedVariants[variantIndex].quantity = newValue;
                }

                // Cập nhật giá trị quantity mới vào input tương ứng trong variantsInput
                const variantsInput = document.getElementById('variantsInput');
                const variantInputs = variantsInput.querySelectorAll('input[name="variants[]"]');
                if (variantInputs.length > variantIndex) {
                    variantInputs[variantIndex].setAttribute('value', JSON.stringify(selectedVariants[
                        variantIndex]));
                }
            }
        });

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
                const variantId = parentRow.getAttribute('data-variant-id');
                const variantIndex = Array.from(parentRow.parentNode.children).indexOf(parentRow);
                console.log(variantIndex);
                const confirmDelete = Swal.fire({
                    title: "Thông báo",
                    text: `Bạn có chắc chắn muốn xóa biến thể không?`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#34c3af",
                    cancelButtonColor: "#f46a6a",
                    confirmButtonText: "Đồng ý xoá",
                    cancelButtonText: "Huỷ bỏ",
                    customClass: {
                        popup: 'custom-swal-popup'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (variantIndex !== -1) {

                            if (variantId) {

                                // Gọi endpoint để xóa biến thể khỏi database
                                axios.delete(`/admin/product/delete-variant/${variantId}`)
                                    .then(response => {
                                        // Xoá thành công, có thể thực hiện các thao tác cần thiết
                                        toastr.success('Đã xoá thành công');
                                        // Sau khi xóa thành công, xóa biến thể khỏi giao diện
                                        selectedVariants.splice(variantIndex, 1);
                                        parentRow.remove();

                                        // Cập nhật lại giá trị ẩn trong variantsInput
                                        const variantsInput = document.getElementById('variantsInput');
                                        const variantInputs = variantsInput.querySelectorAll(
                                            'input[name="variants[]"]');
                                        if (variantInputs.length > variantIndex) {
                                            variantInputs[variantIndex]
                                                .remove(); // Xóa phần tử ẩn tương ứng với biến thể đã xóa
                                        }
                                    })
                                    .catch(error => {
                                        // Xử lý lỗi nếu có
                                        if (error.response && error.response.status === 400) {
                                            // Hiển thị thông báo lỗi từ server
                                            const errorMessage = error.response.data.error;
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Lỗi',
                                                text: errorMessage,
                                            });
                                        } else {
                                            // Xử lý lỗi không xác định
                                            console.error('Error:', error);
                                            // Hiển thị thông báo lỗi mặc định
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Lỗi',
                                                text: 'Đã xảy ra lỗi khi xóa biến thể.',
                                                customClass: {
                                                    popup: 'custom-swal-popup' // Đặt lớp CSS tùy chỉnh cho cửa sổ thông báo
                                                }
                                            });
                                        }
                                    });
                            } else {
                                toastr.success('Đã xoá thành công');
                                selectedVariants.splice(variantIndex, 1);
                                parentRow.remove();

                                // Cập nhật lại giá trị ẩn trong variantsInput
                                const variantsInput = document.getElementById('variantsInput');
                                const variantInputs = variantsInput.querySelectorAll(
                                    'input[name="variants[]"]');
                                if (variantInputs.length > variantIndex) {
                                    variantInputs[variantIndex]
                                .remove(); // Xóa phần tử ẩn tương ứng với biến thể đã xóa
                                }
                            }

                        }
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
            const imagePreview = document.getElementById('imagePreview');
            const variants = document.getElementById('variants');
            const avtImage = document.getElementById('preview');
            if (!imagePreview || !imagePreview.firstChild) {
                $.toast({
                    heading: 'Lỗi',
                    text: 'Vui lòng chọn album ảnh sản phẩm.',
                    hideAfter: 3000,
                    icon: 'error',
                    position: 'top-right',
                    loader: false,
                });
                return false;
            }
            if (!variants || !variants.children.length) {
                $.toast({
                    heading: 'Lỗi',
                    text: 'Vui lòng thêm ít nhất một thuộc tính sản phẩm.',
                    hideAfter: 3000,
                    icon: 'error',
                    position: 'top-right',
                    loader: false,
                });
                return false;
            }
            if (avtImage.src.includes('empty.jpg')) {
                $.toast({
                    heading: 'Lỗi',
                    text: 'Vui lòng chọn hình đại diện sản phẩm.',
                    hideAfter: 3000,
                    icon: 'error',
                    position: 'top-right',
                    loader: false,
                });
                return false;
            }

            // Nếu các điều kiện đều được đáp ứng, cho phép submit form
            return true;
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

    <script>
        // Sự kiện thay đổi cho tên trạng thái
        const checkbox = document.getElementById('status_product');
        checkbox.addEventListener('change', function() {
            const label = document.querySelector('label[for="status_product"]');
            if (checkbox.checked) {
                label.textContent = 'Hoạt động';
                checkbox.value = '1'
            } else {
                label.textContent = 'Ngừng hoạt động';
                checkbox.value = '0';
            }
        });
    </script>
@endsection
