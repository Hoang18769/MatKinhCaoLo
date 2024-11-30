@extends('admin.master')
@section('title', 'Quản lý Yêu Thích')
@section('main-content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
            <h4 class="mb-sm-0 font-size-16 fw-bold">QUẢN LÝ YÊU THÍCH</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Quản lý yêu thích</li>
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
                <br>
                <table id="Tabledatatable" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th style="width: 10px">STT</th>
                            <th style="width: 40px">Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th style="text-align: center" >Số lượng khách hàng yêu thích</th>
                            <th style="text-align: center" >Danh Sách Khách Hàng Yêu Thích</th>
                            {{-- <th style="width: 40px">Chức năng</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $count = 1; // Khởi tạo biến đếm
                        @endphp
                        @foreach ($favoriteCounts as $favorite)
                            @php
                            // Lấy thông tin sản phẩm và khách hàng
                            $product = \App\Models\Product::find($favorite->id_product);
                            $customers = \App\Models\Favorite::where('id_product', $favorite->id_product)
                                ->with('customer')
                                ->get()
                                ->pluck('customer');
                            $customerIds = $customers->pluck('id_customer')->toArray();
                            @endphp
                            <tr>
                                <td class="text-center">{{ $count++ }}</td>
                                <td> <img width="80" height="60" style="object-fit: contain"
                                    src="{{ $favorite->product->avt_product }}"
                                    alt="{{ $favorite->product->name_product }}">
                                </td>
                                <td>{{$favorite->product->name_product }}</td>
                                <td style="text-align: center">{{ $favorite->favorite_count }} </td>
                                {{-- <td>
                                    {{ dd($favorite->customer) }}
                                </td> --}}
                                <td style="text-align: center">
                                    {{-- @foreach ($customers as $customer) --}}
                                    @if($customers->isNotEmpty())
                                        <a class="btn btn-primary" href="{{ route('favorite.show', ['favorite' => implode(',', $customerIds)]) }}">
                                            <i class="bx bx-show-alt"></i> Xem Khách Hàng Yêu Thích
                                        </a>
                                    @else
                                        <span class="text-danger">Không có khách hàng</span>
                                    @endif
                                    {{-- @endforeach --}}
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

@endsection
