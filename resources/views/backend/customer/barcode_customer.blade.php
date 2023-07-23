@extends('admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

 <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
   <li class="breadcrumb-item"><a  class="btn btn-primary rounded-pill waves-effect waves-light" href="{{ route('all.customer') }}">Back </a></li>

                                        </ol>
                                    </div>
                                    <h4 class="page-title">Bar Code Customer</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

<div class="row">


  <div class="col-lg-8 col-xl-12">
<div class="card">
    <div class="card-body">





    <!-- end timeline content-->

    <div class="tab-pane" id="settings">


            <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle me-1"></i> Bar Code Customer</h5>

            <div class="row">


    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="firstname" class="form-label">Customer Code</label>
             <h3>{{ $customer->customer_code }}</h3>

        </div>
    </div>

   @php
  $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
   @endphp


              <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="firstname" class="form-label">Customer BarCode    </label>
           <h3> {!! $generator->getBarcode($customer->customer_code,$generator::TYPE_CODE_128)  !!} </h3>

           </div>
        </div>




            </div> <!-- end row -->

    </div>
    <!-- end settings content-->


                                    </div>
                                </div> <!-- end card-->

                            </div> <!-- end col -->
                        </div>
                        <!-- end row-->

                    </div> <!-- container -->

                </div> <!-- content -->







@endsection
