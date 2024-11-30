@extends('admin.master')
@section('title', 'Trang Chủ Quản Trị')
@section('main-content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
                <h4 class="mb-sm-0 font-size-16 fw-bold">BẢNG ĐIỀU KHIỂN</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Bảng điều khiển</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="float-end">
                        <div class="avatar-sm mx-auto mb-4">
                            <span class=" rounded-circle bg-light font-size-24">
                                <i class="mdi mdi-cash-multiple text-success"></i>
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="text-muted text-uppercase fw-semibold font-size-13">Tổng doanh thu</p>
                        <h4 class="mb-1 mt-1">{{ number_format($totalRevenue, 0, '.', ',') }} VNĐ</h4>
                    </div>
                    </p>
                </div>
            </div>
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="float-end">
                        <div class="avatar-sm mx-auto mb-4">
                            <span class=" rounded-circle bg-light font-size-24">
                                <i class="mdi mdi-refresh-circle text-danger"></i>
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="text-muted text-uppercase fw-semibold font-size-13">Sản phẩm hết hàng</p>
                        <h4 class="mb-1 mt-1">{{ $outOfStockProducts }}</h4>
                    </div>
                    </p>
                </div>
            </div>
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="float-end">
                        <div class="avatar-sm mx-auto mb-4">
                            <span class=" rounded-circle bg-light font-size-24">
                                <i class="mdi mdi-account-group text-primary"></i>
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="text-muted text-uppercase fw-semibold font-size-13">Khách hàng mới</p>
                        <h4 class="mb-1 mt-1">{{ $newCustomers }}</h4>
                    </div>
                    </p>
                </div>
            </div>
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="float-end">
                        <div class="avatar-sm mx-auto mb-4">
                            <span class=" rounded-circle bg-light font-size-24">
                                <i class="mdi mdi-cart-check text-success"></i>
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="text-muted text-uppercase fw-semibold font-size-13">Đơn hàng mới</p>
                        <h4 class="mb-1 mt-1">{{ $newOrders }}</h4>
                    </div>
                </div>
            </div>
        </div> <!-- end col-->
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="float-end">
                        <div class="avatar-sm mx-auto mb-4">
                            <span class=" rounded-circle bg-light font-size-24">
                                <i class="mdi mdi-cash-multiple text-success"></i>
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="text-muted text-uppercase fw-semibold font-size-13">Doanh thu theo tuần </p>
                        <h4 class="mb-1 mt-1">{{ number_format($weeklyRevenue, 0, '.', ',') }} VNĐ</h4>
                    </div>
                </div>
            </div>
        </div> <!-- end col-->
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="float-end">
                        <div class="avatar-sm mx-auto mb-4">
                            <span class=" rounded-circle bg-light font-size-24">
                                <i class="mdi mdi-cash-multiple text-success"></i>
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="text-muted text-uppercase fw-semibold font-size-13">Doanh thu theo ngày </p>
                        <h4 class="mb-1 mt-1">{{ number_format($totalRevenueToday, 0, '.', ',') }} VNĐ</h4>
                    </div>
                </div>
            </div>
        </div> <!-- end col-->
    </div> <!-- end row-->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="font-size-14 mb-4">Doanh thu tuần</h4>
                    <div id="line_chart_datalabel" class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div> <!-- end col-->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="font-size-14 mb-4">Doanh thu ngày</h4>
                    <div id="line_chart_datalabel_2" class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div> <!-- end col-->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="font-size-14 mb-4">Đơn hàng</h4>
                    <div id="column_chart" class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div> <!-- end col-->
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <br>
                    <h4 class="font-size-14 mb-4">Khách hàng mới</h4>
                    <div class="table-responsive">
                        <table class="table align-middle table-striped table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Tên khách hàng</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($latestCustomers as $customer)
                                    <tr>
                                        <td>{{ $customer->name_customer }}</td>
                                        <td>{{ $customer->email_customer }}</td>
                                        <td>{{ $customer->phone_customer }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- end col-->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <br>
                    <h4 class="font-size-14 mb-4">Sản phẩm bán chạy</h4>
                    <div class="table-responsive">
                        <table class="table align-middle table-striped table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Hình</th>
                                    <th style="width: 50px !important">Tên sản phẩm</th>
                                    <th>Mã</th>
                                    <th>Danh mục</th>
                                    <th>Số lượng đã bán</th>
                                    <!-- Các cột khác bạn muốn hiển thị -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topSellingProducts as $product)
                                    <tr>
                                        <td> <img width="80" height="60" style="object-fit: contain"
                                                src="{{ $product->avt_product }}" alt="{{ $product->name_product }}">
                                        </td>
                                        <td style="width: 50px !important">{{ $product->name_product }}</td>
                                        <td>{{ $product->sku }}</td>
                                        <td>{{ $product->category->name_category }}</td>
                                        <td>{{ $product->number_buy }}</td>
                                        <!-- Các cột khác bạn muốn hiển thị -->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- end col-->
    </div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <!-- Doanh Thu theo Tuần-->
    <script>
        var options = {
            chart: {
                height: 350,
                type: "line",
                zoom: {
                    enabled: !1
                },
                toolbar: {
                    show: !1
                },
            },
            colors: ["#0576b9"],
            dataLabels: {
                enabled: !1
            },
            stroke: {
                width: [3],
                curve: "straight"
            },
            series: [{
                name: "Doanh thu",
                data: {!! json_encode($revenues) !!}
            }],
            grid: {
                row: {
                    colors: ["transparent", "transparent"],
                    opacity: 0.2
                },
                borderColor: "#f1f1f1",
            },
            markers: {
                style: "inverted",
                size: 4,
                hover: {
                    size: 6
                }
            },
            xaxis: {
                categories: {!! json_encode($categories) !!},
                title: {
                    text: "Ngày đặt hàng"
                },
            },
            yaxis: {
                title: {
                    text: "Doanh thu tuần"
                },
                min: 0,
                labels: {
                    formatter: function(value) {
                        return value.toLocaleString();
                    }
                }
            },
            legend: {
                position: "top",
                horizontalAlign: "right",
                floating: !0,
                offsetY: -25,
                offsetX: -5,
            },
            responsive: [{
                breakpoint: 600,
                options: {
                    chart: {
                        toolbar: {
                            show: !1
                        }
                    },
                    legend: {
                        show: !1
                    }
                },
            }, ],
        };

        var chart = new ApexCharts(
            document.querySelector("#line_chart_datalabel"),
            options
        );
        chart.render();
    </script>
    <!-- Doanh Thu theo ngày-->
    <script>
        var options = {
            chart: {
                height: 350,
                type: "line",
                zoom: {
                    enabled: !1
                },
                toolbar: {
                    show: !1
                },
            },
            colors: ["#0576b9"],
            dataLabels: {
                enabled: !1
            },
            stroke: {
                width: [3],
                curve: "straight"
            },
            series: [{
                name: "Doanh thu",
                data: {!! json_encode($revenues1) !!}
            }],
            grid: {
                row: {
                    colors: ["transparent", "transparent"],
                    opacity: 0.2
                },
                borderColor: "#f1f1f1",
            },
            markers: {
                style: "inverted",
                size: 4,
                hover: {
                    size: 6
                }
            },
            xaxis: {
                categories: {!! json_encode($categories1) !!},
                title: {
                    text: "Ngày đặt hàng"
                },
            },
            yaxis: {
                title: {
                    text: "Doanh thu ngày"
                },
                min: 0,
                labels: {
                    formatter: function(value) {
                        return value.toLocaleString();
                    }
                }
            },
            legend: {
                position: "top",
                horizontalAlign: "right",
                floating: !0,
                offsetY: -25,
                offsetX: -5,
            },
            responsive: [{
                breakpoint: 600,
                options: {
                    chart: {
                        toolbar: {
                            show: !1
                        }
                    },
                    legend: {
                        show: !1
                    }
                },
            }, ],
        };

        var chart = new ApexCharts(
            document.querySelector("#line_chart_datalabel_2"),
            options
        );
        chart.render();
    </script>

    <script>
        var options2 = {
            chart: {
                height: 350,
                type: "bar",
                toolbar: {
                    show: !1
                }
            },
            plotOptions: {
                bar: {
                    horizontal: !1,
                    columnWidth: "50%",
                    endingShape: "rounded"
                },
            },
            dataLabels: {
                enabled: !1
            },
            stroke: {
                show: !0,
                width: 2,
                colors: ["transparent"]
            },
            series: <?= json_encode($series) ?>,
            colors: ["#495867", "#0576b9", "#f06548", "#0ab39c", "#F7B84B"],
            xaxis: {
                categories: <?= json_encode(array_values($formattedDates)) ?>,
            },
            yaxis: {
                title: {
                    text: "Số lượng đơn hàng"
                },
                labels: {
                    formatter: function(value) {
                        return Math.floor(value); // Hiển thị số nguyên
                    },
                },
            },
            grid: {
                borderColor: "#f1f1f1"
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(e) {
                        return e;
                    },
                },
            },
        };

        var chart2 = new ApexCharts(
            document.querySelector("#column_chart"),
            options2
        );
        chart2.render();
    </script>

@endsection
