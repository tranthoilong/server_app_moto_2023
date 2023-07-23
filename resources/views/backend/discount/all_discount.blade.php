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
                            <a href="{{ route('add.discount') }}" class="btn btn-primary rounded-pill waves-effect waves-light">+</a>
                        </ol>
                    </div>
                    <h4 class="page-title">Mã giảm giá</h4>
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
                                <th>Mã giảm giá</th>
                                <th>Phần trăm giảm giá</th>
                                <th>Số tiền tối thiểu</th>
                                <th>Số lần giới hạn nhập</th>
                                <th>Hạn nhập</th>
                                <th>Trạng thái</th>
                                <th>Action</th>
                            </tr>
                            </thead>


                            <tbody>
                            @foreach($discount as $key=> $item)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $item->discount_code }}</td>
                                <td>{{ $item->discount_percent }}%</td>
                                <td>{{ $item->minimum_amount }}</td>
                                <td>{{ $item->limited_entry }}</td>
                                <td>{{ $item->entry_deadline }}</td>
                                @if($item->status == 'active')
                                <td><span class="badge bg-success">Hiệu lực</span></td>
                                @else
                                <td><span class="badge bg-danger">Không hiệu lực</span></td>
                                @endif
                                <td>
                                    <a href="{{ route('edit.discount',$item->id) }}" class="btn btn-blue rounded-pill waves-effect waves-light">Edit</a>

                                    <a href="{{ route('delete.discount',$item->id) }}" class="btn btn-danger rounded-pill waves-effect waves-light" id="delete">Delete</a>

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


@endsection
