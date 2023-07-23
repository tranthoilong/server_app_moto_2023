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

                                        </ol>
                                    </div>
                                    <h4 class="page-title">Hóa đơn</h4>
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
                                <th>Mã hóa đơn</th>
                                <th>Hình ảnh</th>
                                <th>Tên khách hàng</th>
                                <th>Ngày tạo hóa đơn</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>


        <tbody>
        	@foreach($orders as $key=> $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td> <img src="{{ Storage::url('public/user/'.$item->user->image) }}" style="width:50px; height: 40px;"> </td>
                <td>{{ $item['user']['name'] }}</td>
                <td>{{ $item->created_at }}</td>
                <td>{{ $item->total_price }}</td>
                @if($item->status == 1)
                <td> <span class="badge bg-danger">Chưa duyệt</span> </td>
                @elseif($item->status == 3)
                <td> <span class="badge bg-success">Đã duyệt</span> </td>
                @elseif($item->status == 4)
                <td> <span class="badge bg-success">Thành công</span> </td>
                @elseif($item->status == 2)
                <td> <span class="badge bg-danger">Đã hủy</span> </td>
                @endif
                <td>
                    @if($item->status == 1)
                    <form method="post" action="{{ route('order.browsing',$item->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary" name="approve">Duyệt đơn hàng</button>
                    </form>
                    <form method="post" action="{{ route('order.cancel',$item->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary" name="approve">Hủy đơn</button>
                    </form>
                    @elseif($item->status == 3)
                    <form method="post" action="{{ route('order.check',$item->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary" name="approve">Giao hàng thành công</button>
                    </form>
                    <form method="post" action="{{ route('order.cancel',$item->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary" name="approve">Hủy đơn</button>
                    </form>
                    @endif
                    
                    <a href="{{ url('order/invoice-download/'.$item->id) }}" class="btn btn-blue rounded-pill waves-effect waves-light"> Chi tiết </a>
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
