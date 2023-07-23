@extends('admin_dashboard')
@section('admin')

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>

 <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">

                                    </div>
                                    <h4 class="page-title">Trang chủ</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

<div class="row">

    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle bg-soft-danger border-danger border">
                            <i class="fe-shopping-bag font-22 avatar-title text-danger"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="mt-1"><span data-plugin="counterup">{{ count($total_protuct) }}</span></h3>
                            <p class="text-muted mb-1 text-truncate">Sản phẩm</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle bg-success border-success border shadow">
                            <i class="fe-shopping-cart font-22 avatar-title text-white"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="mt-1"><span data-plugin="counterup">{{ count($total_post) }}</span></h3>
                            <p class="text-muted mb-1 text-truncate">Bài viết</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->
    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle bg-info border-info border shadow">
                            <i class="fe-bar-chart-line- font-22 avatar-title text-white"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"> <span data-plugin="counterup">{{ count($completeorder)  }}</span></h3>
                            <p class="text-muted mb-1 text-truncate">Hóa đơn </p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

<div class="col-md-6 col-xl-3">
    <div class="widget-rounded-circle card">
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="avatar-lg rounded-circle bg-warning border-warning border shadow">
                        <i class="fe-eye font-22 avatar-title text-white"></i>
                    </div>
                </div>
                <div class="col-6">
                   <div class="text-end">
                        <h3 class="text-dark mt-1"> <span data-plugin="counterup">{{ count($pendingorder)  }}</span></h3>
                        <p class="text-muted mb-1 text-truncate">Khách hàng </p>
                    </div>
                </div>
            </div> <!-- end row-->
        </div>
    </div> <!-- end widget-rounded-circle-->
</div> <!-- end col-->
</div>
                        <!-- end row-->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <form action="{{ route('dashboard') }}" method="get" class="d-flex align-items-center mb-3">
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" name="year" id="year" value="{{ request('year', date('Y')) }}" />
                                            </div>

                                            <button type="submit" class="btn btn-blue btn-sm ms-1">
                                                <i class="mdi mdi-filter-variant"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">


                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title mb-3">Bán hàng</h4>
                                        <div>
                                            <canvas id="revenueChart"></canvas>
                                        </div>
                                    </div>
                                </div> <!-- end card -->
                            </div> <!-- end col-->
                        </div>
                        <!-- end row -->

                        <!-- end row-->

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title mb-3">Doanh Thu</h4>

                                        <div class="text-center">
                                            <h5 class="mb-3">Tổng Doanh Thu: {{ number_format(array_sum($chartData)) }} VND</h5>
                                        </div>

                                        <div>
                                            <canvas id="salesChart"></canvas>
                                        </div>
                                    </div>
                                </div> <!-- end card -->
                            </div> <!-- end col-->
                        </div>

                        <!-- end row -->

                        <div class="row">


                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="header-title mb-3">Top 5 sản phẩm bán chạy</h4>

                                        <div class="table-responsive">
                                            <table class="table table-borderless table-nowrap table-hover table-centered m-0">
                                                <thead class="table-light">
                                                <tr>
                                                    <th>Sản phẩm</th>
                                                    <th>Số lượng</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php

                                                foreach ($topProducts as $product) {
                                                    echo '<tr>';
                                                    echo '<td><h5 class="m-0 fw-normal">' . $product->name . '</h5></td>';
                                                    echo '<td>' . $product->total_quantity . '</td>';
                                                    // Các thông tin khác về sản phẩm
                                                    echo '</tr>';
                                                }

                                                function getStatusColorClass($status)
                                                {
                                                    if ($status == "Upcoming") {
                                                        return "warning";
                                                    } elseif ($status == "Paid") {
                                                        return "success";
                                                    } elseif ($status == "Overdue") {
                                                        return "danger";
                                                    } else {
                                                        return "";
                                                    }
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div> <!-- end card-->
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->

                    </div> <!-- container -->

                </div> <!-- content -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('revenueChart').getContext('2d');

        var labels = {!! json_encode($chartLabels) !!};
        var orderCountData = {!! json_encode($chartOrderCount) !!};
        var productCountData = {!! json_encode($chartProductCount) !!};


        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Bán ra',
                        data: productCountData,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)', // Màu xanh
                        borderColor: 'rgba(54, 162, 235, 1)', // Màu xanh
                        borderWidth: 2,
                        hoverBackgroundColor: 'rgba(54, 162, 235, 0.4)', // Màu xanh khi di chuột qua
                        hoverBorderColor: 'rgba(54, 162, 235, 1)', // Màu xanh khi di chuột qua
                        yAxisID: 'count-y-axis'
                    },
                    {
                        label: 'Hóa đơn',
                        data: orderCountData,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)', // Màu đỏ
                        borderColor: 'rgba(255, 99, 132, 1)', // Màu đỏ
                        borderWidth: 2,
                        hoverBackgroundColor: 'rgba(255, 99, 132, 0.4)', // Màu đỏ khi di chuột qua
                        hoverBorderColor: 'rgba(255, 99, 132, 1)', // Màu đỏ khi di chuột qua
                        yAxisID: 'count-y-axis'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: [
                        {
                            id: 'count-y-axis',
                            type: 'linear',
                            position: 'left',
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                callback: function (value, index, values) {
                                    if (Number.isInteger(value)) {
                                        return value.toLocaleString();
                                    }
                                }
                            }
                        }
                    ],
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

    });
</script>

<script>
    // Dữ liệu biểu đồ
    var chartData = {!! json_encode($chartData) !!};
    var chartLabels = {!! json_encode($chartLabels) !!};

    // Tạo biểu đồ
    var ctx = document.getElementById('salesChart').getContext('2d');
    var revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Doanh Thu',
                data: chartData,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                pointRadius: 4,
                pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                pointBorderColor: 'rgba(75, 192, 192, 1)',
                pointHoverRadius: 6,
                pointHoverBackgroundColor: 'rgba(75, 192, 192, 1)',
                pointHoverBorderColor: 'rgba(75, 192, 192, 1)',
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>

<script>
    document.getElementById('dashboard-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting normally

        // Retrieve the selected date range
        var dateRange = document.getElementById('dash-daterange').value;
        var dateParts = dateRange.split(' to ');
        var fromDate = dateParts[0];
        var toDate = dateParts[1];

        // Construct the URL with the selected date range as query parameters
        var url = "{{ route('dashboard') }}" + "?fromDate=" + encodeURIComponent(fromDate) + "&toDate=" + encodeURIComponent(toDate);

        // Redirect to the constructed URL
        window.location.href = url;
    });
</script>
<script>
    $("#year").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose:true //to close picker once year is selected
    });
</script>

@endsection
