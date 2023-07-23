@extends('admin_dashboard')
@section('admin')

<?php
$data['invoice_no'] = 'SPOS'.mt_rand(10000000,99999999);
?>


 <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Customer Invoice</a></li>


                                        </ol>
                                    </div>
                                    <h4 class="page-title">Customer Invoice</h4>
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
                <h4 class="m-0 d-print-none">Invoice</h4>
            </div>
        </div>

                <div class="row">
                    <div class="col-md-6">


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
                                    <p><span class="badge bg-danger">Pending</span></p>
                                    <p style="margin-bottom: 0; white-space: nowrap;"><?php echo $data['invoice_no']; ?></p>

                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            <!-- end row -->
        <form class="px-3" id="myForm" method="post" action="">
            @csrf
        <div class="row">
            <div class="col-sm-6">
                <h6>Billing Address</h6>
                <address>
                    @if ($customer->name == "walk-in-customer")
                    <abbr title="Name">Name:</abbr>
                    <input type="text" name="name" class="form-control">
                    <br>
                    <abbr title="Phone">Address:</abbr>
                    <textarea name="address" class="form-control" rows="3"></textarea>
                    <br>
                    <abbr title="Phone">Phone:</abbr>
                    <input type="text" name="phone" class="form-control">
                    <br>
                    <abbr title="Email">Email:</abbr>
                    <input type="email" name="email" class="form-control">
                    @else
                    <abbr title="Name">Name:</abbr>
                    <input type="text" name="name" class="form-control" value="{{ $customer->name }}">
                    <br>
                    <abbr title="Address">Address:</abbr>
                    <textarea name="address" class="form-control" rows="3">{{ $customer->address }}</textarea>
                    <br>
                    <abbr title="Phone">Phone:</abbr>
                    <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}">
                    <br>
                    <abbr title="Email">Email:</abbr>
                    <input type="email" name="email" class="form-control" value="{{ $customer->email }}">
                    @endif
                </address>
            </div> <!-- end col -->
        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table mt-4 table-centered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th style="width: 15%">Qty</th>
                            <th style="width: 20%">Unit Cost</th>
                            <th style="width: 20%" class="text-end">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                        $sl = 1;
                        @endphp

                        @foreach($contents as $key=> $item)
                        <tr>
                            <td>{{ $sl++ }}</td>
                            <td>
                                <b>{{ $item->name }}</b> <br/>
                            </td>
                            <td style="width: 15%">{{ $item->qty }}</td>
                            <td style="width: 20%">{{ number_format($item->price, 0, ',', ',') }} VND</td>
                            <td style="width: 20%" class="text-end">{{ number_format($item->price*$item->qty, 0, ',', ',') }} VND</td>
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


                    </div>
                </div> <!-- end col -->
                <div class="col-sm-6">
                    <div class="float-end">
    <p><b>Sub-total:</b> <span class="float-end"> {{ Cart::subtotal() }} VND</span></p>
    <p><b>Vat (8%):</b> <span class="float-end"> {{ Cart::tax() }} VND</span></p>
                        <p><b>Discount:</b> <span class="float-end"> {{ Cart::discount() }} VND</span></p>

    <h3>{{ Cart::total() }} VND</h3>
                    </div>
                    <div class="clearfix"></div>
                </div> <!-- end col -->
            </div>
            <!-- end row -->

            <div class="mt-4 mb-1">
                <div class="text-end d-print-none">

                        <!-- Các input hidden khác -->
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                        <input type="hidden" name="order_date" value="{{ date('d-F-Y') }}">
                        <input type="hidden" name="order_status" value="pending">
                        <input type="hidden" name="total_products" value="{{ Cart::count() }}">
                        <input type="hidden" name="sub_total" value="{{ str_replace(',', '', Cart::subtotal()) }}">
                        <input type="hidden" name="vat" value="{{ str_replace(',', '', Cart::tax()) }}">
                        <input type="hidden" name="discount" value="{{ str_replace(',', '', Cart::discount()) }}">
                        <input type="hidden" name="total" value="{{ str_replace(',', '', Cart::total()) }}">
                        <input type="hidden" name="order_id" value="{{ $data['invoice_no'] }}">

                        <button class="btn btn-primary payment-button" data-value="HandCash" type="submit">HandCash</button>
                        <button class="btn btn-primary payment-button" data-value="Debit or Credit card" type="submit">Debit or Credit card</button>
                        <button class="btn btn-primary payment-button" data-value="Vnpay" type="submit">VNPAY</button>
                        <button class="btn btn-primary payment-button" data-value="Momo QR" type="submit">MOMO QR</button>
                        <button class="btn btn-primary payment-button" data-value="Momo QP" type="submit">MOMO QP</button>
                        <button class="btn btn-primary payment-button" data-value="Zalo Pay" type="submit">Zalo Pay</button>


                </div>
            </div>
        </div>
    </div> <!-- end card -->
</div> <!-- end col --></form>
</div>
                        <!-- end row -->

                    </div> <!-- container -->

                </div> <!-- content -->


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('myForm');
        var buttons = document.getElementsByClassName('payment-button');

        // Lặp qua tất cả các nút thanh toán và thêm sự kiện click
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].addEventListener('click', function() {
                var paymentMethod = this.getAttribute('data-value');
                var action = '';

                if (paymentMethod === 'HandCash') {
                    action = "{{ url('/final-invoice') }}"; // Thay đổi thành action tương ứng cho HandCash
                } else if (paymentMethod === 'Debit or Credit card') {
                    action = "{{ url('/stripe_checkout') }}"; // Thay đổi thành action tương ứng cho Debit or Credit card
                } else if (paymentMethod === 'Vnpay') {
                    action = "{{ url('/payment_vnpay') }}"; // Thay đổi thành action tương ứng cho VNPAY
                } else if (paymentMethod === 'Momo QR') {
                    action = "{{ url('/payment_qr_momo') }}"; // Thay đổi thành action tương ứng cho MOMO QR
                } else if (paymentMethod === 'Momo QP') {
                    action = "{{ url('/payment_qp_momo') }}"; // Thay đổi thành action tương ứng cho MOMO QP
                } else if (paymentMethod === 'Zalo Pay') {
                    action = "{{ url('/payment_zalo') }}"; // Thay đổi thành action tương ứng cho Zalo Pay
                } else {
                    // Xử lý các phương thức thanh toán khác nếu cần
                }

                form.action = action; // Đặt action mới cho form
            });
        }
    });
</script>






@endsection
