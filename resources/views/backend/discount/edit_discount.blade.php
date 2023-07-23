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
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Chỉnh sửa mã giảm giá</a></li>

                        </ol>
                    </div>
                    <h4 class="page-title">Chỉnh sửa mã giảm giá</h4>
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
                            <form method="post" action="{{ route('discount.update') }}" enctype="multipart/form-data">
                                @csrf

                                <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle me-1"></i>Chỉnh sửa giảm giá</h5>

                                <input type="hidden" name="id" value="{{ $discount->id }}">

                                <div class="row">


                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstname" class="form-label">Mã giảm giá</label>
                                            <input type="text" name="discount_code" class="form-control @error('name') is-invalid @enderror" value="{{$discount->discount_code}}" >
                                            @error('name')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstname" class="form-label">Số tiền đơn hàng tối thiểu được áp dụng</label>
                                            <input type="text" name="minimum_amount" class="form-control @error('name') is-invalid @enderror" value="{{$discount->minimum_amount}}">
                                            @error('name')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="discount_percent" class="form-label">Phần trăm giảm giá</label>
                                            <select name="discount_percent" class="form-select @error('discount_percent') is-invalid @enderror">
                                                <option value="10" @if($discount->discount_percent == 10) selected @endif>10%</option>
                                                <option value="20" @if($discount->discount_percent == 20) selected @endif>20%</option>
                                                <option value="30" @if($discount->discount_percent == 30) selected @endif>30%</option>
                                                <!-- Thêm các tùy chọn phần trăm giảm giá khác tại đây -->
                                            </select>
                                            @error('discount_percent')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>



                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstname" class="form-label">Ngày giới hạn nhập</label>
                                            <input type="date" class="form-control" name="entry_deadline" value="{{$discount->entry_deadline}}">
                                            @error('name')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstname" class="form-label">Tổng số lần nhập</label>
                                            <input type="text" name="limited_entry" class="form-control @error('name') is-invalid @enderror" value="{{$discount->limited_entry}}">
                                            @error('name')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstname" class="form-label">Mô tả ngắn</label>
                                            <input type="text" name="short_description" class="form-control @error('name') is-invalid @enderror" value="{{$discount->short_description}}">
                                            @error('name')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstname" class="form-label">Số lần đã nhập</label>
                                            <input type="text" name="number_entry" class="form-control @error('name') is-invalid @enderror" value="{{$discount->number_entry}}" disabled style="background-color: #D3D3D3;">
                                            @error('name')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Trạng thái</label>
                                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                                <option value="active" @if($discount->status == 'active') selected @endif>Hiệu lực</option>
                                                <option value="deactivate" @if($discount->status == 'deactivate') selected @endif>Không hiệu lực</option>
                                            </select>
                                            @error('status')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstname" class="form-label">Số lần còn lại</label>
                                            <input type="text" name="remaining_entry" class="form-control @error('name') is-invalid @enderror" value="{{$discount->remaining_entry}}" disabled style="background-color: #D3D3D3;">
                                            @error('name')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>

                                    @php
                                    $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
                                    @endphp
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstname" class="form-label">Mã BarCode</label>
                                            <h3> {!! $generator->getBarcode($discount->discount_code,$generator::TYPE_CODE_128)  !!} </h3>
                                        </div>
                                    </div>
                                </div>

                                </div> <!-- end row -->



                                <div class="text-end">
                                    <button type="submit" class="btn btn-success waves-effect waves-light mt-2"><i class="mdi mdi-content-save"></i> Lưu</button>
                                </div>
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

    $(document).ready(function(){
        $('#image').change(function(e){
            var reader = new FileReader();
            reader.onload =  function(e){
                $('#showImage').attr('src',e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        });
    });

</script>

@endsection
