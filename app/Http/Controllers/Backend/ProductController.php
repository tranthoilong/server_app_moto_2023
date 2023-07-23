<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;

use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductImport;

class ProductController extends Controller
{
    function fetch(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');
            $data = DB::table('products')
                ->where('name', 'LIKE', "%{$query}%")
                ->orWhere('id', 'LIKE', "%{$query}%")
                ->get();
            $output = '<ul class="dropdown-menu" style="display:block; position:relative;width:100%;">';
            foreach ($data as $row) {
                $output .= '
                <li data-id="' . $row->id . '" data-price="' . $row->price . '"><a class="dropdown-item" href="#">' . $row->name . ' - ' . $row->id . '</a></li>
                ';
            }
            $output .= '</ul>';
            echo $output;
        }
    }

    public function AllProduct()
    {
        $product = Product::latest()
            ->get();

        return view('backend.product.all_product', compact('product'));
    } // End Method

    public function AddProduct()
    {
        $productCount = Product::count();
        $nextNumber = $productCount + 1;
        $pcode = 'PC' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $category = Category::latest()->get();
        $supplier = Supplier::latest()->get();
        return view('backend.product.add_product', compact('category', 'supplier', 'pcode'));
    } // End Method


    public function StoreProduct(Request $request)
    {

        $productCount = Product::count();
        $nextNumber = $productCount + 1;
        $pcode = 'PC' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $file = $request->file('product_image');
        $filename = $file->getClientOriginalName();
        $file->storeAs('public/product', $filename);
        Product::insert([

            'name' => $request->product_name,
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'product_code' => $pcode,
            'description' => $request->description,
            'number' => $request->product_garage,
            'price' => $request->price,
            'image' => $filename,
            'status' => 1,
            'created_at' => Carbon::now(),

        ]);

        $notification = array(
            'message' => 'Product Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.product')->with($notification);
    } // End Method



    public function EditProduct($id)
    {
        $product = Product::findOrFail($id);
        $category = Category::latest()->get();
        $supplier = Supplier::latest()->get();
        return view('backend.product.edit_product', compact('product', 'category', 'supplier'));
    } // End Method



    public function UdateProduct(Request $request)
    {
        $product_id = $request->id;
        $product = Product::find($product_id);
        if ($request->file('product_image')) {

            $file = $request->file('product_image');
            $filename = $file->getClientOriginalName();
            Storage::delete('public/product/' . $product->image);
            $file->storeAs('public/product', $filename);
            Product::findOrFail($product_id)->update([

                'name' => $request->product_name,
                'category_id' => $request->category_id,
                'supplier_id' => $request->supplier_id,
                'description' => $request->description,
                'number' => $request->product_garage,
                'price' => $request->price,
                'image' => $filename,
                'status' => 1,
                'created_at' => Carbon::now(),

            ]);

            $notification = array(
                'message' => 'Product Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.product')->with($notification);
        } else {

            Product::findOrFail($product_id)->update([

                'name' => $request->product_name,
                'category_id' => $request->category_id,
                'supplier_id' => $request->supplier_id,
                'description' => $request->description,
                'number' => $request->product_garage,
                'price' => $request->price,
                'status' => 1,
                'created_at' => Carbon::now(),

            ]);

            $notification = array(
                'message' => 'Product Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.product')->with($notification);
        } // End else Condition


    } // End Method

    public function DeleteProduct($id)
    {

        $product_img = Product::findOrFail($id);
        $img = $product_img->product_image;
        Storage::delete('public/product/' . $img);

        Product::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Product Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method


    public function BarcodeProduct($id)
    {

        $product = Product::findOrFail($id);
        return view('backend.product.barcode_product', compact('product'));
    } // End Method


    public function ImportProduct()
    {

        return view('backend.product.import_product');
    } // End Method


    public function Export()
    {

        return Excel::download(new ProductExport, 'products.xlsx');
    } // End Method


    public function Import(Request $request)
    {

        Excel::import(new ProductImport, $request->file('import_file'));

        $notification = array(
            'message' => 'Product Imported Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method


}
