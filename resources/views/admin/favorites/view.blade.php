@extends('admin.master')
@section('title', 'Danh Sách Khách Hàng Yêu Thích')
@section('main-content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
            <h4 class="mb-sm-0 font-size-16 fw-bold">DANH SÁCH YÊU THÍCH</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Quản lý yêu thích</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <h1>Danh Sách Khách Hàng Yêu Thích</h1>
    <table class="table">
        <thead>
            <tr>
                <th> ID</th>
                <th>Tên Khách Hàng</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Địa chỉ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
                <tr>
                    <td>{{ $customer->id_customer }}</td>
                    <td>{{ $customer->name_customer }}</td>
                    <td>{{ $customer->email_customer }}</td>
                    <td>{{ $customer->phone_customer }}</td>
                    <td>{{ $customer->address_customer }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
