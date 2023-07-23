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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Edit Category </a></li>

                                        </ol>
                                    </div>
                                    <h4 class="page-title">Edit Category</h4>
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
        <form method="post" action="{{ route('category.update') }}" enctype="multipart/form-data">
        	@csrf

            <input type="hidden" name="id" value="{{ $category->id }}">

            <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle me-1"></i> Edit Category</h5>

            <div class="row">


    <div class="col-md-6">
        <div class="mb-3">
            <label for="firstname" class="form-label">Category Name</label>
            <input type="text" name="category_name" class="form-control" value="{{ $category->name }}"   >

        </div>
    </div>


            </div> <!-- end row -->
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label for="example-fileinput" class="form-label">Customer Image</label>
                    <input type="file" name="category_image" id="image" class="form-control">

                </div>
            </div> <!-- end col -->


            <div class="col-md-12">
                <div class="mb-3">
                    <label for="example-fileinput" class="form-label"> </label>
                    <img id="showImage" src="{{ Storage::url('category/'.$category->image) }}" class="rounded-circle avatar-lg img-thumbnail"
                         alt="profile-image">
                </div>
            </div> <!-- end col -->

            <div class="text-end">
                <button type="submit" class="btn btn-success waves-effect waves-light mt-2"><i class="mdi mdi-content-save"></i> Save</button>
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
            var file = e.target.files[0];
            console.log(file);
            var reader = new FileReader();
            reader.onload =  function(e){
                $('#showImage').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        });
    });
</script>



@endsection
