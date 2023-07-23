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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Chỉnh sửa sản phẩm</a></li>

                                        </ol>
                                    </div>
                                    <h4 class="page-title">Chỉnh sửa sản phẩm</h4>
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
        <form id="myForm" method="post" action="{{ route('product.update') }}" enctype="multipart/form-data">
        	@csrf

            <input type="hidden" name="id" value="{{ $product->id }}">

            <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle me-1"></i> Chỉnh sửa sản phẩm</h5>

            <div class="row">


    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="firstname" class="form-label">Tên sản phẩm</label>
            <input type="text" name="product_name" class="form-control" value="{{ $product->name }}"   >

        </div>
    </div>


              <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="firstname" class="form-label">Loại sản phẩm </label>
            <select name="category_id" class="form-select" id="example-select">
                    <option selected disabled >Select Category </option>
                    @foreach($category as $cat)
        <option value="{{ $cat->id }}" {{ $cat->id == $product->category_id ? 'selected' : ''  }} >{{ $cat->name }}</option>
                     @endforeach
                </select>

        </div>
    </div>

          <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="firstname" class="form-label">Nhà cung cấp </label>
            <select name="supplier_id" class="form-select" id="example-select">
                    <option selected disabled >Select Supplier </option>
                    @foreach($supplier as $sup)
        <option value="{{ $sup->id }}"  {{ $sup->id == $product->supplier_id ? 'selected' : ''  }}>{{ $sup->name }}</option>
                     @endforeach
                </select>

        </div>
    </div>




              <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="firstname" class="form-label">Mã sản phẩm    </label>
            <input type="text" name="product_code" class="form-control "  value="{{ $product->product_code }}"  disabled >

           </div>
        </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="firstname" class="form-label">Số lượng    </label>
                        <input type="text" name="product_garage" class="form-control " value="{{ $product->number }}">

                    </div>
                </div>


              <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="firstname" class="form-label">Giá    </label>
            <input type="text" name="price" class="form-control "  value="{{ $product->price }}"   >

           </div>
        </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea id="description" name="description" class="form-control ckeditor">{{$product->description}}</textarea>
                    </div>
                </div>


   <div class="col-md-12">
<div class="form-group mb-3">
        <label for="example-fileinput" class="form-label">Hình ảnh</label>
        <input type="file" name="product_image" id="image" class="form-control">

    </div>
 </div> <!-- end col -->


   <div class="col-md-12">
<div class="mb-3">
        <label for="example-fileinput" class="form-label"> </label>
        <img id="showImage" src="{{ Storage::url('product/'.$product->image) }}" class="rounded-circle avatar-lg img-thumbnail"
                alt="profile-image">
    </div>
 </div> <!-- end col -->



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
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                product_name: {
                    required : true,
                },
                category_id: {
                    required : true,
                },
                supplier_id: {
                    required : true,
                },
                product_code: {
                    required : true,
                },
                product_garage: {
                    required : true,
                },
                product_store: {
                    required : true,
                },
                buying_date: {
                    required : true,
                },
                expire_date: {
                    required : true,
                },
                buying_price: {
                    required : true,
                },
                selling_price: {
                    required : true,
                },

            },
            messages :{
                product_name: {
                    required : 'Please Enter Product Name',
                },
                category_id: {
                    required : 'Please Select Category',
                },
                supplier_id: {
                    required : 'Please Select Supplier',
                },
                product_code: {
                    required : 'Please Enter Product Code',
                },
                product_garage: {
                    required : 'Please Enter Product Garage',
                },
                product_store: {
                    required : 'Please Enter Product Store',
                },
                buying_date: {
                    required : 'Please Slect Buying Date',
                },
                expire_date: {
                    required : 'Please Slect Expire Date',
                },
                buying_price: {
                    required : 'Please Enter Buying Price',
                },
                selling_price: {
                    required : 'Please Enter Selling Price',
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

<script src="https://cdn.ckeditor.com/ckeditor5/38.1.0/classic/ckeditor.js"></script>
<script>

    ClassicEditor
        .create( document.querySelector( '#description' ),{
            ckfinder:
                {
                    uploadUrl:"{{route('img.post',['_token'=>csrf_token()])}}",
                }
        } )
        .then( content => {
            console.log( content );
        } )
        .catch( error => {
            console.error( error );
        } );
</script>


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
