@extends('admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>




 <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Bán hàng</a></li>

                                        </ol>
                                    </div>
                                    <h4 class="page-title">Bán hàng</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

<div class="row">
    <div class="col-lg-6 col-xl-6">
        <div class="card text-center">
            <div class="card-body">






    <div class="bg-primary">
        <br>
        <p style="font-size:18px; color:#fff"> Số lượng : {{ Cart::count() }} </p>
        <p style="font-size:18px; color:#fff"> Giá tiền : {{ Cart::subtotal() }} VND</p>
        <p style="font-size:18px; color:#fff"> Thuế(vat) : {{ Cart::tax() }} VND</p>
        <p style="font-size:18px; color:#fff"> Giá giảm : {{ Cart::discount() }} VND</p>
        <p><h2 class="text-white"> Tổng tiền </h2> <h1 class="text-white"> {{ Cart::total() }} VND</h1>   </p>
         <br>
    </div>
                <br>
                <div class="form-group mb-3">
                    <form id="discount_form" method="post" action="{{ url('/discount') }}">
                    <label for="discount_code" class="form-label">Mã giảm giá</label>
                    <div class="input-group">

                            @csrf
                            <input type="text" class="form-control" name="discount_code" id="discount_code" value="">
                            <button type="submit" class="btn btn-sm btn-success" style="margin-top:-2px ;"> <i class="fas fa-check"></i> </button>
                    </div>
                    </form>

                </div>



                <br>
    <form id="myForm" method="post" action="{{ url('/create-invoice') }}">
        @csrf

        <div class="form-group mb-3">
            <label for="firstname" class="form-label">Khách hàng </label>
            <select name="customer_id" class="form-select" id="example-select">
                    <option selected disabled >Chọn khách hàng </option>
                    @foreach($customer as $cus)
        <option value="{{ $cus->id }}">{{ $cus->name }}</option>
                     @endforeach
                </select>

        </div>

        @if(isset($discount))
        <input type="hidden" class="form-control" value="{{$discount}}">
        @endif


            <button class="btn btn-blue waves-effect waves-light" {{ Cart::content()->isEmpty() ? 'disabled' : '' }}>Tạo hóa đơn</button>



    </form>








            </div>
        </div> <!-- end card -->



                            </div> <!-- end col-->

                            <div class="col-lg-6 col-xl-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="container box">
                                            <div class="form-group mb-3">
                                                <label for="product_search" class="form-label">Tìm kiếm sản phẩm</label>
                                                <input type="text" id="product_search" name="product_search" class="form-control" autocomplete="off">
                                                <div id="productList"></div>
                                            </div>
                                        {{ csrf_field() }}
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered border-primary mb-1">
                                                <thead>
                                                <tr>
                                                    <th>Tên</th>
                                                    <th>Số lượng</th>
                                                    <th>Giá</th>
                                                    <th>Tổng tiền</th>
                                                    <th>Hành động</th>
                                                </tr>
                                                </thead>

                                                @php
                                                $allcart = Cart::content();
                                                @endphp
                                                <tbody>
                                                @foreach($allcart as $cart)
                                                <tr>
                                                    <td>{{ $cart->name }}</td>
                                                    <td>
                                                        <form method="post" action="{{ url('/cart-update/'.$cart->rowId) }}">
                                                            @csrf

                                                            <input type="number" name="qty" value="{{ $cart->qty }}" style="width:40px;" min="1">
                                                            <button type="submit" class="btn btn-sm btn-success" style="margin-top:-2px ;"> <i class="fas fa-check"></i> </button>

                                                        </form>
                                                    </td>
                                                    <td>{{ $cart->price }}</td>
                                                    <td>{{ $cart->price*$cart->qty }}</td>
                                                    <td> <a href="{{ url('/cart-remove/'.$cart->rowId) }}"><i class="fas fa-trash-alt" style="color:black;"></i></a> </td>
                                                </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

    <!-- end timeline content-->
                                        <style>
                                            .small-size {
                                                width: 320px;
                                                height: 240px;
                                            }
                                        </style>
    <div class="tab-pane" id="settings">
        <div id="barcode-scanner" style="display: none;"></div>
        <form id="barcode-form" method="GET" action="/add-cart-barcode">
            <input type="hidden" id="barcode-input" name="barcode" value="">
        </form>





    </div>
    <!-- end settings content-->


                                    </div>
                                </div> <!-- end card-->

                            </div> <!-- end col -->
                        </div>
                        <!-- end row-->

                    </div> <!-- container -->

                </div> <!-- content -->



<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                customer_code: {
                    required : true,
                },

            },
            messages :{
                customer_id: {
                    required : 'Please Select Customer',
                },


            },
            errorElement : 'span',
            errorPlacement: function (error,element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight : function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
            },
        });
    });

</script>
<script type="text/javascript">
    $(document).ready(function() {
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#barcode-scanner'),
                constraints: {
                    width: 470,
                    height: 210,
                    facingMode: "environment" // "user" nếu muốn sử dụng camera trước:::: "environment" camera sau
                }
            },
            decoder: {
                readers: ["code_128_reader"]
            }
        }, function(err) {
            if (err) {
                console.error(err);
                 return;
            }

            Quagga.start();

            Quagga.onDetected(function(result) {
                var lastCode = result.codeResult.code;
                console.log(lastCode);
                playBeepSound(); // Phát âm thanh bíp

                if (lastCode.startsWith("PC")) {

                    Quagga.stop(); // Dừng quét mã vạch

                    // Điền giá trị mã vạch vào trường dữ liệu
                    document.getElementById('barcode-input').value = lastCode;

                    // Gửi form
                    document.getElementById('barcode-form').submit();
                } else {
                    Quagga.stop(); // Dừng quét mã vạch


                    // Tìm option có giá trị tương ứng với customerCode
                    var selectElement = document.getElementById('example-select');
                    var options = selectElement.options;
                    var customerId = parseInt(lastCode); // Chuyển đổi mã khách hàng thành số nguyên
                            
                    for (var i = 0; i < options.length; i++) {
                        if (parseInt(options[i].value) === customerId) {
                            selectElement.selectedIndex = i;
                            break;
                        }
                    }
                    
                    document.getElementById('discount_code').value = lastCode;
                    document.getElementById('discount_form').submit();
                }
            });
            function playBeepSound() {
                var beepSound = new Audio('/sound/bip.mp3'); // Đường dẫn tương đối đến tệp bip.mp3
                beepSound.play();
            }
        });
    });
</script>
<script>
    $(document).ready(function(){

        $('#product_search').keyup(function(){
            var query = $(this).val();
            if(query != '')
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('search.product') }}",
                    method:"POST",
                    data:{query:query, _token:_token},
                    success:function(data){
                        $('#productList').fadeIn();
                        $('#productList').html(data);
                    }
                });
            }
        });

        $(document).on('click', 'li', function(){
            var productName = $(this).text();
            var productCode = $(this).data('code');

            // Update the input field with the selected product name
            $('#product_search').val(productName);
            $('#productList').fadeOut();

            // Retrieve other necessary data for adding to the cart
            var productId = $(this).data('id');
            var productPrice = $(this).data('price');

            var _token = $('input[name="_token"]').val();

            // Create an object with the product details
            var product = {
                id: productId,
                name: productName,
                code: productCode,
                qty: 1,
                price: productPrice,
                _token: _token // Add the CSRF token to the object
            };

            // Perform an AJAX request to add the product to the cart
            $.ajax({
                url: "{{ url('/add-cart') }}",
                method: "POST",
                data: product,
                success: function(response) {
                    // Handle the success response here
                    console.log(response);
                    // Tải lại trang
                    location.reload();
                },
                error: function(xhr) {
                    // Handle any error that occurred during the request
                    console.log(xhr.responseText);
                }
            });
        });

    });
</script>

@endsection
