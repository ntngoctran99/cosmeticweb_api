<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\TypeProduct;
use App\Models\Supplier;

class ProductController extends Controller
{
    public function index()
    {
        $lists = Product::orderBy('id', 'DESC')->paginate(20);
        //return view('admin.pages.products.index', compact('lists'));

        return response()->json([
            'status' => 1,
            'message'   => 'Load Product success',
            'data' => $lists
        ]);
    }

//    public function create()
//    {
//        $product_type_list = TypeProduct::orderBy('id', 'DESC')->get();
//        $supplier_list = Supplier::orderBy('id', 'DESC')->get();
//
//        return view('admin.pages.products.create', compact('product_type_list', 'supplier_list'));
//    }

    public function store(Request $request)
    {

        if (!isset($request->best_seller)) {
            $request->best_seller = 0;
        }

        if (!isset($request->latest)) {
            $request->latest = 0;
        }

        if (!isset($request->top_rated)) {
            $request->top_rated = 0;
        }

        if (!isset($request->sample_home)) {
            $request->sample_home = 0;
        }

        $product = Product::create([
            'name'            => $request->name,
            'description'     => $request->description,
            'unit_price'      => $request->unit_price,
            'promotion_price' => $request->promotion_price,
            'unit'            => $request->unit,
            'views'           => $request->views,
            'type_id'         => $request->type_id,
            'best_seller'     => $request->best_seller,
            'latest'          => $request->latest,
            'top_rated'       => $request->top_rated,
            'sample_home'     => $request->sample_home,
            'suppliers_id'    => $request->suppliers_id
        ]);

//        return \Redirect::route('admin.product.index')
//            ->with([
//                'flashLevel' => 'success',
//                'flashMes'   => 'Create Products Success'
//            ]);

        return response()->json([
            'status' => 1,
            'data' => $product,
            'flashLevel' => 'success',
            'flashMes' => "Create Product Successful!",
        ]);
    }

    public function show($id)
    {
        $item = Product::find($id);

        if (empty($item)) {
//            return \Redirect::route('admin.product.index')
//                ->with([
//                    'flashLevel' => 'warning',
//                    'flashMes'   => 'Not Found The Product'
//                ]);
            return response()->json([
                'flashLevel' => 'warning',
                'flashMes' => 'Not Found The Product',
            ]);
        }

        return response()->json([
            'status' => 1,
            'data' => $item
        ]);
        //return view ('admin.pages.products.show', compact('item'));
    }

/*    public function editInfo($id)
    {
        $item = Product::find($id);

        $product_type_list = TypeProduct::orderBy('id', 'DESC')->get();
        $supplier_list = Supplier::orderBy('id', 'DESC')->get();

        if (empty($item)) {
            return \Redirect::route('admin.product.index')
                ->with([
                    'flashLevel' => 'warning',
                    'flashMes'   => 'Not Found The Product'
                ]);
        }

        return view ('admin.pages.products.edit', compact('item', 'product_type_list', 'supplier_list'));
    }*/

    public function updateInfo(Request $request, $id)
    {
        $item = Product::find($id);

        if (empty($item)) {
//            return \Redirect::route('admin.product.index')
//                ->with([
//                    'flashLevel' => 'warning',
//                    'flashMes'   => 'Not Found The Product'
//                ]);

            return response()->json([
                'flashLevel' => 'warning',
                'flashMes' => 'Not Found The Product',
            ]);
        }

        if (!isset($request->best_seller)) {
            $request->best_seller = 0;
        }

        if (!isset($request->latest)) {
            $request->latest = 0;
        }

        if (!isset($request->top_rated)) {
            $request->top_rated = 0;
        }

        if (!isset($request->sample_home)) {
            $request->sample_home = 0;
        }

        $product = $item->update([
            'name'            => $request->name,
            'description'     => $request->description,
            'unit_price'      => $request->unit_price,
            'promotion_price' => $request->promotion_price,
            'unit'            => $request->unit,
            'views'           => $request->views,
            'type_id'         => $request->type_id,
            'best_seller'     => $request->best_seller,
            'latest'          => $request->latest,
            'top_rated'       => $request->top_rated,
            'sample_home'     => $request->sample_home,
            'suppliers_id'    => $request->suppliers_id
        ]);

//        return \Redirect::route('admin.product.index')
//            ->with([
//                'flashLevel' => 'success',
//                'flashMes'   => 'Update Product ' .$request->name . ' Info Success'
//            ]);

        return response()->json([
            'status' => 1,
            'flashLevel' => 'success',
            'flashMes' => "Update Product Successful!",
        ]);
    }

    public function destroy($id)
    {
        $item = Product::find($id);

        if (empty($item)) {
//            return \Redirect::route('admin.product.index')
//                ->with([
//                    'flashLevel' => 'warning',
//                    'flashMes'   => 'Not Found The Product'
//                ]);

            return response()->json([
                'flashLevel' => 'warning',
                'flashMes' => 'Not Found The Product',
            ]);
        }

        $product = $item->update([
            'del_flag' => 1
        ]);

//        return \Redirect::route('admin.product.index')
//            ->with([
//                'flashLevel' => 'success',
//                'flashMes'   => 'Delete Product ' .$item->name . ' Success'
//            ]);

        return response()->json([
            'status' => 1,
            'data' => $product,
            'flashLevel' => 'success',
            'flashMes' => "Delete Product".$item->name."Successful!",
        ]);
    }
}
