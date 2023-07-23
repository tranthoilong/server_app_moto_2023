<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\Backend\DiscountController;
use App\Http\Controllers\Backend\GarageController;
use App\Http\Controllers\Backend\PostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\EmployeeController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\SupplierController;
use App\Http\Controllers\Backend\SalaryController;
use App\Http\Controllers\Backend\AttendenceController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ExpenseController;
use App\Http\Controllers\Backend\PosController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\PaymentController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('login_user', [AuthenticatedSessionController::class, 'store'])->name('login_user');

Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';



Route::get('/admin/logout', [AdminController::class, 'AdminDestroy'])->name('admin.logout');

Route::get('/logout', [AdminController::class, 'AdminLogoutPage'])->name('admin.logout.page');


Route::middleware(['auth'])->group(function () {

    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');

    Route::post('/admin/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');


    Route::get('/change/password', [AdminController::class, 'ChangePassword'])->name('change.password');

    Route::post('/update/password', [AdminController::class, 'UpdatePassword'])->name('update.password');


    /// Garage All Route
    Route::controller(GarageController::class)->group(function () {

        Route::get('/all/garage', 'AllGarage')->name('all.garage');
        Route::get('/add/garage', 'AddGarage')->name('add.garage');
        Route::post('/store/garage', 'StoreGarage')->name('garage.store');
        Route::get('/edit/garage/{id}', 'EditGarage')->name('edit.garage');
        Route::post('/update/garage', 'UpdateGarage')->name('garage.update');
        Route::get('/delete/garage/{id}', 'DeleteGarage')->name('delete.garage');
    });


    /// Employee All Route
    Route::controller(EmployeeController::class)->group(function () {

        Route::get('/all/employee', 'AllEmployee')->name('all.employee')->middleware('permission:employee.all');
        Route::get('/add/employee', 'AddEmployee')->name('add.employee')->middleware('permission:employee.add');
        Route::post('/store/employee', 'StoreEmployee')->name('employee.store');
        Route::get('/edit/employee/{id}', 'EditEmployee')->name('edit.employee');
        Route::post('/update/employee', 'UpdateEmployee')->name('employee.update');
        Route::get('/delete/employee/{id}', 'DeleteEmployee')->name('delete.employee');
    });


    /// Customer All Route
    Route::controller(CustomerController::class)->group(function () {

        Route::get('/all/customer', 'AllCustomer')->name('all.customer');
        Route::get('/add/customer', 'AddCustomer')->name('add.customer');
        Route::post('/store/customer', 'StoreCustomer')->name('customer.store');
        Route::get('/edit/customer/{id}', 'EditCustomer')->name('edit.customer');
        Route::post('/update/customer', 'UpdateCustomer')->name('customer.update');
        Route::get('/delete/customer/{id}', 'DeleteCustomer')->name('delete.customer');
        Route::get('/barcode/customer/{id}', 'BarcodeCustomer')->name('barcode.customer');
    });

    /// Banner All Route
    Route::controller(BannerController::class)->group(function () {

        Route::get('/all/banner', 'AllBanner')->name('all.banner');
        Route::get('/add/banner', 'AddBanner')->name('add.banner');
        Route::post('/store/banner', 'StoreBanner')->name('banner.store');
        Route::get('/edit/banner/{id}', 'EditBanner')->name('edit.banner');
        Route::post('/update/banner', 'UpdateBanner')->name('banner.update');
        Route::get('/delete/banner/{id}', 'DeleteBanner')->name('delete.banner');
    });

    /// Discount All Route
    Route::controller(DiscountController::class)->group(function () {

        Route::get('/all/discount', 'AllDiscount')->name('all.discount');
        Route::get('/add/discount', 'AddDiscount')->name('add.discount');
        Route::post('/store/discount', 'StoreDiscount')->name('discount.store');
        Route::get('/edit/discount/{id}', 'EditDiscount')->name('edit.discount');
        Route::post('/update/discount', 'UpdateDiscount')->name('discount.update');
        Route::get('/delete/discount/{id}', 'DeleteDiscount')->name('delete.discount');
        Route::get('/order/discount', 'OrderUseDiscount')->name('order.discount');
    });

    /// Discount All Route
    Route::controller(PostController::class)->group(function () {

        Route::get('/all/post', 'AllPost')->name('all.post');
        Route::get('/add/post', 'AddPost')->name('add.post');
        Route::post('/store/post', 'StorePost')->name('post.store');
        Route::get('/edit/post/{id}', 'EditPost')->name('edit.post');
        Route::post('/update/post', 'UpdatePost')->name('post.update');
        Route::get('/delete/post/{id}', 'DeletePost')->name('delete.post');
        Route::post('/contentImg', 'ImageUpload')->name('img.post');
    });


    /// Supplier All Route
    Route::controller(SupplierController::class)->group(function () {

        Route::get('/all/supplier', 'AllSupplier')->name('all.supplier');
        Route::get('/add/supplier', 'AddSupplier')->name('add.supplier');
        Route::post('/store/supplier', 'StoreSupplier')->name('supplier.store');
        Route::get('/edit/supplier/{id}', 'EditSupplier')->name('edit.supplier');
        Route::post('/update/supplier', 'UpdateSupplier')->name('supplier.update');
        Route::get('/delete/supplier/{id}', 'DeleteSupplier')->name('delete.supplier');
        Route::get('/details/supplier/{id}', 'DetailsSupplier')->name('details.supplier');
    });


    /// Advance Salary All Route
    Route::controller(SalaryController::class)->group(function () {

        Route::get('/add/advance/salary', 'AddAdvanceSalary')->name('add.advance.salary');
        Route::post('/advance/salary/store', 'AdvanceSalaryStore')->name('advance.salary.store');
        Route::get('/all/advance/salary', 'AllAdvanceSalary')->name('all.advance.salary');

        Route::get('/edit/advance/salary/{id}', 'EditAdvanceSalary')->name('edit.advance.salary');
        Route::post('/advance/salary/update', 'AdvanceSalaryUpdate')->name('advance.salary.update');
    });


    /// Pay Salary All Route
    Route::controller(SalaryController::class)->group(function () {

        Route::get('/pay/salary', 'PaySalary')->name('pay.salary');
        Route::get('/pay/now/salary/{id}', 'PayNowSalary')->name('pay.now.salary');
        Route::post('/employe/salary/store', 'EmployeSalaryStore')->name('employe.salary.store');
        Route::get('/month/salary', 'MonthSalary')->name('month.salary');
    });


    ///Attendence All Route
    Route::controller(AttendenceController::class)->group(function () {

        Route::get('/employee/attend/list', 'EmployeeAttendenceList')->name('employee.attend.list');
        Route::get('/add/employee/attend', 'AddEmployeeAttendence')->name('add.employee.attend');
        Route::post('/employee/attend/store', 'EmployeeAttendenceStore')->name('employee.attend.store');

        Route::get('/edit/employee/attend/{date}', 'EditEmployeeAttendence')->name('employee.attend.edit');
        Route::get('/view/employee/attend/{date}', 'ViewEmployeeAttendence')->name('employee.attend.view');
    });


    ///Category All Route
    Route::controller(CategoryController::class)->group(function () {

        Route::get('/all/category', 'AllCategory')->name('all.category');
        Route::post('/store/category', 'StoreCategory')->name('category.store');
        Route::get('/edit/category/{id}', 'EditCategory')->name('edit.category');
        Route::post('/update/category', 'UpdateCategory')->name('category.update');
        Route::get('/delete/category/{id}', 'DeleteCategory')->name('delete.category');
    });


    ///Product All Route
    Route::controller(ProductController::class)->group(function () {

        Route::get('/all/product', 'AllProduct')->name('all.product');
        Route::get('/add/product', 'AddProduct')->name('add.product');
        Route::post('/store/product', 'StoreProduct')->name('product.store');
        Route::get('/edit/product/{id}', 'EditProduct')->name('edit.product');
        Route::post('/update/product', 'UdateProduct')->name('product.update');
        Route::get('/delete/product/{id}', 'DeleteProduct')->name('delete.product');

        Route::get('/barcode/product/{id}', 'BarcodeProduct')->name('barcode.product');

        Route::get('/import/product', 'ImportProduct')->name('import.product');
        Route::get('/export', 'Export')->name('export');
        Route::post('/import', 'Import')->name('import');
        Route::post('/search-products', 'fetch')->name('search.product');
    });


    ///Expense All Route
    Route::controller(ExpenseController::class)->group(function () {

        Route::get('/add/expense', 'AddExpense')->name('add.expense');
        Route::post('/store/expense', 'StoreExpense')->name('expense.store');
        Route::get('/today/expense', 'TodayExpense')->name('today.expense');
        Route::get('/edit/expense/{id}', 'EditExpense')->name('edit.expense');
        Route::post('/update/expense', 'UpdateExpense')->name('expense.update');
        Route::get('/month/expense', 'MonthExpense')->name('month.expense');
        Route::get('/year/expense', 'YearExpense')->name('year.expense');
    });


    ///Pos All Route
    Route::controller(PosController::class)->group(function () {

        Route::get('/pos', 'Pos')->name('pos');
        Route::post('/add-cart', 'AddCart');
        Route::post('/discount', 'DiscountCart');
        Route::get('/add-cart-barcode', 'AddCart_Barcode');
        Route::get('/allitem', 'AllItem');
        Route::post('/cart-update/{rowId}', 'CartUpdate');
        Route::get('/cart-remove/{rowId}', 'CartRemove');

        Route::post('/create-invoice', 'CreateInvoice');
    });


    ///Report
    Route::controller(\App\Http\Controllers\Backend\ReportController::class)->group(function () {
        Route::get('/report_invoice', 'Report_invoice')->name('report_invoice');
    });


    ///Order All Route
    Route::controller(OrderController::class)->group(function () {
        Route::get('/order_momo_complete', 'order_momo_complete')->name('order_momo_complete');
        Route::get('/order_vnpay_complete', 'order_vnpay_complete')->name('order_vnpay_complete');
        Route::get('/order_stripe_complete', 'order_stripe_complete')->name('order_stripe_complete');
        Route::post('/final-invoice', 'FinalInvoice');
        Route::get('/pending/order', 'PendingOrder')->name('pending.order');
        Route::get('/order/details/{order_id}', 'OrderDetails')->name('order.details');
        Route::post('/order/status/update', 'OrderStatusUpdate')->name('order.status.update');

        Route::get('/complete/order', 'CompleteOrder')->name('complete.order');

        Route::get('/stock', 'StockManage')->name('stock.manage');
        Route::get('/order/invoice-download/{order_id}', 'OrderInvoice');
        Route::post('/order_browsing/{order_id}', 'order_browsing')->name('order.browsing');
        Route::post('/order_check/{order_id}', 'order_check')->name('order.check');

        Route::post('/order_cancel/{order_id}', 'order_cancel')->name('order.cancel');



        //// Due All Route

        Route::get('/pending/due', 'PendingDue')->name('pending.due');
        Route::get('/order/due/{id}', 'OrderDueAjax');
        Route::post('/update/due', 'UpdateDue')->name('update.due');
    });


    ///Permission All Route
    Route::controller(RoleController::class)->group(function () {

        Route::get('/all/permission', 'AllPermission')->name('all.permission');
        Route::get('/add/permission', 'AddPermission')->name('add.permission');
        Route::post('/store/permission', 'StorePermission')->name('permission.store');
        Route::get('/edit/permission/{id}', 'EditPermission')->name('edit.permission');

        Route::post('/update/permission', 'UpdatePermission')->name('permission.update');
        Route::get('/delete/permission/{id}', 'DeletePermission')->name('delete.permission');
    });


    ///Roles All Route
    Route::controller(RoleController::class)->group(function () {

        Route::get('/all/roles', 'AllRoles')->name('all.roles');
        Route::get('/add/roles', 'AddRoles')->name('add.roles');
        Route::post('/store/roles', 'StoreRoles')->name('roles.store');
        Route::get('/edit/roles/{id}', 'EditRoles')->name('edit.roles');

        Route::post('/update/roles', 'UpdateRoles')->name('roles.update');
        Route::get('/delete/roles/{id}', 'DeleteRoles')->name('delete.roles');
    });

    ///Add Roles in Permission All Route
    Route::controller(RoleController::class)->group(function () {

        Route::get('/add/roles/permission', 'AddRolesPermission')->name('add.roles.permission');
        Route::post('/role/permission/store', 'StoreRolesPermission')->name('role.permission.store');

        Route::get('/all/roles/permission', 'AllRolesPermission')->name('all.roles.permission');

        Route::get('/admin/edit/roles/{id}', 'AdminEditRoles')->name('admin.edit.roles');

        Route::post('/role/permission/update/{id}', 'RolePermissionUpdate')->name('role.permission.update');

        Route::get('/admin/delete/roles/{id}', 'AdminDeleteRoles')->name('admin.delete.roles');
    });

    //Payment method
    Route::controller(PaymentController::class)->group(function () {
        Route::post('/payment_vnpay', 'vnpay_payment')->name('vnpay');
        Route::post('/payment_qr_momo', 'momo_qr_payment')->name('momo_qr');
        Route::post('/payment_zalo', 'zalo_payment')->name('zalo');
        Route::post('/payment_qp_momo', 'momo_qp_payment')->name('momo_qp');
        Route::post('stripe_checkout', 'stripe_checkout')->name('stripe_checkout');
    });

    ///Admin User All Route
    Route::controller(AdminController::class)->group(function () {

        Route::get('/all/admin', 'AllAdmin')->name('all.admin');
        Route::get('/add/admin', 'AddAdmin')->name('add.admin');
        Route::post('/store/admin', 'StoreAdmin')->name('admin.store');
        Route::get('/edit/admin/{id}', 'EditAdmin')->name('edit.admin');
        Route::post('/update/admin', 'UpdateAdmin')->name('admin.update');
        Route::get('/delete/admin/{id}', 'DeleteAdmin')->name('delete.admin');

        // Database Backup
        Route::get('/database/backup', 'DatabaseBackup')->name('database.backup');
        Route::get('/backup/now', 'BackupNow');
        Route::get('{getFilename}', 'DownloadDatabase');
        Route::get('/delete/database/{getFilename}', 'DeleteDatabase');
    });
}); // End User Middleware






//Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
