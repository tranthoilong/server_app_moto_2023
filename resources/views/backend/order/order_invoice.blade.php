@extends('admin_dashboard')
@section('admin')



<div class="content">

    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Chi tiết hóa đơn</a></li>


                        </ol>
                    </div>
                    <h4 class="page-title">Chi tiết hóa đơn</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Logo & title -->
                        <div class="clearfix">
                            <div class="float-start">
                                <div class="auth-logo">
                                    <div class="logo logo-dark">
                        <span class="logo-lg">
                            <img src="{{ asset('backend/assets/images/logo-dark.png') }}" alt="" height="22">
                        </span>
                                    </div>

                                    <div class="logo logo-light">
                        <span class="logo-lg">
                            <img src="{{ asset('backend/assets/images/logo-light.png') }}" alt="" height="22">
                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="float-end">
                                <h4 class="m-0 d-print-none">Hóa đơn</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mt-3">

                                </div>

                            </div><!-- end col -->
                            <div class="col-md-4 offset-md-2">
                                <div class="mt-3 float-end">
                                    <div class="row">
                                        <div class="col-6">
                                            <p><strong>Order Date:</strong></p>
                                            <p><strong>Order Status:</strong></p>
                                            <p><strong>Invoice No.:</strong></p>
                                        </div>
                                        <div class="col-6">
                                            <p>{{ date('d-F-Y') }}</p>
                                            @if($order->status == 1)
                                            <p><span class="badge bg-danger">Chưa duyệt</span></p>
                                            @elseif($order->status == 3)
                                            <p><span class="badge bg-success">Đã duyệt</span></p>
                                            @elseif($order->status == 4)
                                            <p><span class="badge bg-success">Thành công</span></p>
                                            @elseif($order->status == 2)
                                            <p><span class="badge bg-success">Đã hủy</span></p>
                                            @endif
                                            <p style="margin-bottom: 0; white-space: nowrap;">{{ $order->id }}</p>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        <div class="row mt-3">
                            <div class="col-sm-6">
                                <h6>Billing Address</h6>
                                <address>
                                    {{ $order->address }}
                                    <br>
                                    <abbr title="Phone">Phone:</abbr> {{ $customer->phone }}<br>
                                    <abbr title="Phone">Email:</abbr> {{ $customer->email }}<br>
                                </address>
                            </div> <!-- end col -->


                        </div>
                        <!-- end row -->

                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table mt-4 table-centered">
                                        <thead>
                                        <tr><th>#</th>
                                            <th>Item</th>
                                            <th style="width: 15%">Qty</th>
                                            <th style="width: 20%">Unit Cost</th>
                                            <th style="width: 20%" class="text-end">Total</th>
                                        </tr></thead>
                                        <tbody>
                                        @php
                                        $sl = 1;
                                        @endphp

                                        @foreach($contents as $key => $item)
                                        <tr>
                                            <td>{{ $sl++ }}</td>
                                            <td>
                                                <b>
                                                    {{ $item->name ?? $item->product_name }}
                                                </b>
                                                <br/>
                                            </td>
                                            <td>
                                                {{ $item->qty ?? $item->quantity }}
                                            </td>
                                            <td>
                                                {{ number_format($item->price ?? $item->product_price ?? 0, 0, ',', ',') }}
                                            </td>
                                            <td class="text-end">
                                                {{ number_format(($item->qty ?? $item->quantity) * ($item->price ?? $item->product_price ?? 0), 0, ',', ',') }} VND
                                            </td>
                                        </tr>
                                        @endforeach



                                        </tbody>
                                    </table>
                                </div> <!-- end table-responsive -->
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="clearfix pt-5">
                                    <h6 class="text-muted">Notes:</h6>


                                </div>
                            </div> <!-- end col -->
                            <div class="col-sm-6">
                                <div class="float-end">
                                    <p><b>Sub-total:</b> <span class="float-end">{{ number_format($data['sub_total'], 0, ',', ',') }} VND</span></p>
                                    <p><b>Vat (8%):</b> <span class="float-end">{{ number_format($data['vat'], 0, ',', ',') }} VND</span></p>
                                    <p><b>Discount:</b> <span class="float-end">{{ number_format($data['discount'], 0, ',', ',') }} VND</span></p>

                                    <h3>{{ number_format($data['total'], 0, ',', ',') }} VND</h3>
                                </div>

                                <div class="clearfix"></div>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->

                        <div class="mt-4 mb-1">
                            <div class="text-end d-print-none">
                                <a href="javascript:window.print()" class="btn btn-primary waves-effect waves-light"><i class="mdi mdi-printer me-1"></i> Print</a>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card -->
            </div> <!-- end col -->
        </div>
        <!-- end row -->

    </div> <!-- container -->

</div> <!-- content -->


<script>
    // Kiểm tra xem trình duyệt có hỗ trợ sự kiện "afterprint" không
    if (window.matchMedia) {
        // Thêm lắng nghe cho sự kiện "afterprint"
        window.matchMedia('print').addListener(function(mql) {
            if (!mql.matches) {
                // Trình duyệt đã kết thúc quá trình in, chuyển hướng về trang POS
                window.location.href = '{{ route('pos') }}';
            }
        });
    } else {
        // Trình duyệt không hỗ trợ sự kiện "afterprint", chuyển hướng trực tiếp
        window.onafterprint = function() {
            window.location.href = '{{ route('pos') }}';
        };
    }
</script>










@endsection
