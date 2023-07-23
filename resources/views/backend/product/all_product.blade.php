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

   <!-- <a href="{{ route('import.product') }}" class="btn btn-info rounded-pill waves-effect waves-light">Import </a>
   &nbsp;&nbsp;&nbsp;
   <a href="{{ route('export') }}" class="btn btn-danger rounded-pill waves-effect waves-light">Export </a>
   &nbsp;&nbsp;&nbsp; -->

      <a href="{{ route('add.product') }}" class="btn btn-primary rounded-pill waves-effect waves-light">+</a>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Sản phẩm</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div id="table-container">
                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Mã sản phẩm</th>
                                <th>Ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Loại sản phẩm</th>
                                <th>Nhà cung cấp</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Action</th>
                            </tr>
                        </thead>


        <tbody>
        	@foreach($product as $key=> $item)
            <tr>
                <td>{{ $item->id}}</td>
                <td> <img src="{{ Storage::url('product/'.$item->image) }}" style="width:50px; height: 40px;"> </td>
                <td>{{ $item->name }}</td>
                <td>{{ $item['category']['name'] }}</td>
                <td>{{ $item['supllier']['name'] }}</td>
                <td>{{ $item->price }}</td>
                <td>{{ $item->number }}</td>
                <td>
<a href="{{ route('edit.product',$item->id) }}" class="btn btn-blue rounded-pill waves-effect waves-light"><i class="fa fa-pencil" aria-hidden="true"></i></a>
<a href="{{ route('barcode.product',$item->id) }}" class="btn btn-info rounded-pill waves-effect waves-light"><i class="fa fa-barcode" aria-hidden="true"></i></a>
<a href="{{ route('delete.product',$item->id) }}" class="btn btn-danger rounded-pill waves-effect waves-light" id="delete"><i class="fa fa-trash" aria-hidden="true"></i></a>

                </td>
            </tr>
            @endforeach
        </tbody>
                    </table>
                    </div>
                    <div id="pagination-container">
                        <!-- Đặt cấu trúc nút phân trang -->
                    </div>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->




                    </div> <!-- container -->

                </div> <!-- content -->


                
@endsection
