@extends('admin_dashboard')
@section('admin')

<?php
    $discount_code = App\Models\Discount::latest()->get();
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

                        </ol>
                    </div>
                    <h4 class="page-title">Hóa đơn áp dụng mã giảm giá</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('order.discount') }}" method="GET">
                            <div class="row align-items-center">
                                <div class="col-md-3 mb-3">
                                    <label for="discount-code" class="form-label">Mã giảm giá</label>
                                    <select class="form-select" id="discount-code" name="discount_code">
                                        <option value="">Chọn mô tả mã giảm giá</option>
                                        <?php foreach ($discount_code as $discount): ?>
                                            <option value="<?= $discount['discount_code'] ?>"><?= $discount['short_description'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-7 d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary">Lọc</button>
                                </div>

                            </div>
                        </form>

                        <div class="mb-3">
                            <a href="javascript:void(0);" onclick="exportToPDF();" class="btn btn-primary">Export to PDF</a>
                            <a href="javascript:void(0);" onclick="exportToExcel();" class="btn btn-primary">Export to Excel</a>
                        </div>
                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Hình ảnh</th>
                                <th>Tên</th>
                                <th>Ngày tạo hóa đơn</th>
                                <th>Mã giảm giá</th>
                                <th>Mã hóa đơn</th>
                                <th>Số tiền</th>
                                <th>Trạng thái</th>
                            </tr>
                            </thead>


                            <tbody>
                            @foreach($orders as $key => $item)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td><img src="{{ Storage::url('public/customer/'.$item->customer->image) }}" style="width:50px; height: 40px;"></td>
                                <td>{{ $item['customer']['name'] }}</td>
                                <td>{{ $item->created_at }}</td>
                                <td>{{ $item->discount_code }}</td>
                                <td>{{ $item->invoice_no }}</td>
                                <td>{{ $item->total_price }}</td>
                                @if($item->status == 4)
                <td> <span class="badge bg-success">Thành công</span> </td>
                @endif
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

<script>


    function exportToPDF() {
        var fromDate = document.getElementById("from-date").value;
        var toDate = document.getElementById("to-date").value;

        var reportTitle = "Report Invoice";
        var currentTime = new Date().toLocaleString();

        var data = [];
        var tableRows = document.querySelectorAll("#basic-datatable tbody tr");
        tableRows.forEach(function(row) {
            var rowData = [];
            var tableColumns = row.querySelectorAll("td");
            tableColumns.forEach(function(column) {
                rowData.push(column.innerText);
            });
            data.push(rowData);
        });

        var columns = [
            { header: "Sl", key: 0 },
            { header: "Image", key: 1 },
            { header: "Name", key: 2 },
            { header: "Order Date", key: 3 },
            { header: "Payment", key: 4 },
            { header: "Invoice", key: 5 },
            { header: "Pay", key: 6 },
            { header: "Status", key: 7 }
        ];

        var docDefinition = {
            content: [
                { text: reportTitle, style: "header" },
                { text: "From: " + fromDate + " To: " + toDate, style: "subheader" },
                { text: "Date: " + currentTime, style: "subheader", alignment: "right" },
                { text: "\n" },
                {
                    table: {
                        headerRows: 1,
                        widths: ["auto", "auto", "*", "auto", "auto", "auto", "auto", "auto"],
                        body: [columns.map(column => column.header)].concat(data)
                    }
                }
            ],
            styles: {
                header: { fontSize: 16, bold: true, alignment: "center", margin: [0, 0, 0, 10] },
                subheader: { fontSize: 12, bold: true, margin: [0, 0, 0, 5] }
            }
        };

        pdfMake.createPdf(docDefinition).download("report_invoice.pdf");
    }


    function exportToExcel() {
        var fromDate = document.getElementById("from-date").value;
        var toDate = document.getElementById("to-date").value;

        var currentTime = new Date().toLocaleString();

        var data = [];
        var tableRows = document.querySelectorAll("#basic-datatable tbody tr");
        tableRows.forEach(function(row) {
            var rowData = [];
            var tableColumns = row.querySelectorAll("td");
            tableColumns.forEach(function(column) {
                rowData.push(column.innerText);
            });
            data.push(rowData);
        });

        var ws = XLSX.utils.aoa_to_sheet(data);
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Report Invoice");

        wb.Props = {
            Title: "Report Invoice",
            Author: "Your Name",
            CreatedDate: new Date(),
            LastModifiedBy: "Your Name",
            LastModifiedDate: new Date()
        };

        wb.CustomProperties = {
            FromDate: fromDate,
            ToDate: toDate,
            ReportGenerated: currentTime
        };

        XLSX.writeFile(wb, "report_invoice.xlsx");
    }
</script>


@endsection
