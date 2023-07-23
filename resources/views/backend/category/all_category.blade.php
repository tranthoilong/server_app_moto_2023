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
   <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#signup-modal">+</button>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Loại sản phẩm</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">


                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Hình ảnh</th>
                                <th>Tên loại xe </th>
                                <th>Action</th>
                            </tr>
                        </thead>


        <tbody>
        	@foreach($category as $key=> $item)
            <tr>
                <td>{{ $key+1 }}</td>
                <td> <img src="{{ Storage::url('category/'.$item->image) }}" style="width:50px; height: 40px;"> </td>
                <td>{{ $item->name }}</td>
                <td>
<a href="{{ route('edit.category',$item->id) }}" class="btn btn-blue rounded-pill waves-effect waves-light"><i class="fa fa-pencil" aria-hidden="true"></i></a>
<a href="{{ route('delete.category',$item->id) }}" class="btn btn-danger rounded-pill waves-effect waves-light" id="delete"><i class="fa fa-trash" aria-hidden="true"></i></a>

                </td>
            </tr>
            @endforeach
        </tbody>
                    </table>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->




                    </div> <!-- container -->

                </div> <!-- content -->



        <!-- Signup modal content -->
<div id="signup-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <form class="px-3" method="post" action="{{ route('category.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="username" class="form-label">Tên loại sản phẩm</label>
                        <input class="form-control" type="text" name="category_name" placeholder="Add Category">
                    </div>

                    <div class="mb-3">
                        <label for="photo" class="form-label">Hình ảnh</label>
                        <input type="file" name="photo" id="photo" class="form-control">
                    </div>

                    <div class="mb-3 text-center">
                        <img id="showImage" src="{{ url('upload/no_image.jpg') }}" class="rounded-circle avatar-lg img-thumbnail">
                    </div>



                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" type="submit">Lưu</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
    $(document).ready(function(){
        $('#photo').change(function(e){
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
