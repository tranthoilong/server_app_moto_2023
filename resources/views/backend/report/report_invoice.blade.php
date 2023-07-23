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

                        </ol>
                    </div>
                    <h4 class="page-title">Report Invoice</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('report_invoice') }}" method="GET">
                            <div class="row align-items-center">
                                <div class="col-md-3 mb-3">
                                    <label for="filter-month" class="form-label">Filter by Month</label>
                                    <select class="form-select" id="filter-month" name="filter_month">
                                        <option value="">Select Month</option>
                                        <option value="1">January</option>
                                        <option value="2">February</option>
                                        <option value="3">March</option>
                                        <option value="4">April</option>
                                        <option value="5">May</option>
                                        <option value="6">June</option>
                                        <option value="7">July</option>
                                        <option value="8">August</option>
                                        <option value="9">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                        <!-- Thêm các tháng khác vào đây -->
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="filter-quarter" class="form-label">Filter by Quarter</label>
                                    <select class="form-select" id="filter-quarter" name="filter_quarter">
                                        <option value="">Select Quarter</option>
                                        <option value="1">Q1</option>
                                        <option value="2">Q2</option>
                                        <option value="3">Q3</option>
                                        <option value="4">Q4</option>
                                        <!-- Thêm các quý khác vào đây -->
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="filter-year" class="form-label">Filter by Year</label>
                                    <select class="form-select" id="filter-year" name="filter_year">
                                        <option value="">Select Year</option>
                                        <option value="2021">2021</option>
                                        <option value="2022">2022</option>
                                        <option value="2023">2023</option>
                                        <option value="2024">2024</option>
                                        <option value="2025">2025</option>
                                        <!-- Thêm các năm khác vào đây -->
                                    </select>
                                </div>
                                <div class="col-md-3 mb-7 d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-md-3 mb-3">
                                        <label for="from-date" class="form-label">From Date</label>
                                        <input type="date" class="form-control" id="from-date" name="from_date">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="to-date" class="form-label">To Date</label>
                                        <input type="date" class="form-control" id="to-date" name="to_date">
                                    </div>
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
                                <th>Image</th>
                                <th>Name</th>
                                <th>Order Date</th>
                                <th>Payment</th>
                                <th>Invoice</th>
                                <th>Pay</th>
                                <th>Status</th>
                            </tr>
                            </thead>


                            <tbody>
                            @foreach($orders as $key => $item)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td><img src="{{ asset($item->customer->image) }}" style="width:50px; height: 40px;"></td>
                                <td>{{ $item['customer']['name'] }}</td>
                                <td>{{ $item->order_date }}</td>
                                <td>{{ $item->payment_status }}</td>
                                <td>{{ $item->invoice_no }}</td>
                                <td>{{ $item->pay }}</td>
                                <td><span class="badge bg-success">{{ $item->order_status }}</span></td>
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
