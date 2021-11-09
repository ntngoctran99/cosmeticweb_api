<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function detailProduct($id){

        $status = 1;
        $product = Product::with(['images'])->find($id);
        if(!$product || empty($product))
        {
            return response()->json([
                'status' => -1,
                'message'   => 'Product is not exist'
            ]);
        }

        if (!empty($product)) {

            $list_comment = Review::with(['user'])->where('product_id', $product['id'])->get();
        }

        $images = $this->loadImageForProduct($id);
        $avartar = '';
        foreach ($images as $k => $v)
            if ($v->type_image === 1)
                $avartar = $v->image;


        return response()->json([
            'status' => 1,
            'message'   => 'Load detail product success',
            'key1' => $product,
            'key2' => $list_comment,
            'key3' => $images,
            'key4' => $avartar
        ]);
    }

    public function loadImageForProduct($id = null)
    {

        $images = DB::table('images')
            ->select(['image', 'type_image'])
            ->where([
                ['product_id', '=', $id],
                ['images.del_flag', '=', 0],
            ])
            ->get();

        return $images;
    }

    public function getProductByType($id = null)
    {

        $products = DB::table('type_products')
            ->join('products', 'products.type_id', '=', 'type_products.id')
            ->join('images', 'products.id', '=', 'images.product_id')
            ->select('type_products.name as type_product_name', 'images.*', 'products.*')
            ->where([
                ['type_products.del_flag', '=', 0],
                ['type_products.id', '=', $id],
                ['products.del_flag', '=', 0]
            ])
            ->get();

        $products_best_seller = DB::table('type_products')
            ->join('products', 'products.type_id', '=', 'type_products.id')
            ->join('images', 'products.id', '=', 'images.product_id')
            ->select('type_products.name as type_product_name', 'images.*', 'products.*')
            ->where([
                ['type_products.del_flag', '=', 0],
                ['type_products.id', '=', $id],
                ['products.del_flag', '=', 0],
                ['products.best_seller', '=', 1]
            ])
            ->get();

        $products_latest = DB::table('type_products')
            ->join('products', 'products.type_id', '=', 'type_products.id')
            ->join('images', 'products.id', '=', 'images.product_id')
            ->select('type_products.name as type_product_name', 'images.*', 'products.*')
            ->where([
                ['type_products.del_flag', '=', 0],
                ['type_products.id', '=', $id],
                ['products.del_flag', '=', 0],
                ['products.latest', '=', 1]
            ])
            ->get();

        return response()->json([
            'status' => 1,
            'message'   => 'Load Product By Type success',
            'products' => $products,
            'best_seller' => $products_best_seller,
            'latest' => $products_latest
        ]);
    }

    public function getProductBrand() {
        $products = DB::table('products')
            ->join('images', 'products.id', '=', 'images.product_id')
            ->select('images.*', 'products.*')
            ->where([
                ['products.del_flag', '=', 0],
                ['images.del_flag', '=', 0],
                ['images.type_image', '=', 1],
            ])
            ->get();

        return response()->json([
            'status' => 1,
            'message'   => 'Load Product Brand success',
            'data' => $products
        ]);
    }

    public function search($search)
    {
        //$search = $request->input("search");
        $products = DB::table('products')
            ->join('images', 'products.id', '=', 'images.product_id')
            ->join('type_products', 'type_products.id', '=', 'products.type_id')
            ->select('images.*', 'products.*')
            ->where([
                ['products.del_flag', '=', 0],
                ['images.del_flag', '=', 0],
                ['type_products.del_flag', '=', 0],
                ['images.type_image', '=', 1],
                ['products.name', 'LIKE', "%{$search}%"],
            ])
            ->orWhere([
                ['products.del_flag', '=', 0],
                ['images.del_flag', '=', 0],
                ['type_products.del_flag', '=', 0],
                ['images.type_image', '=', 1],
                ['type_products.name', 'LIKE', "%{$search}%"],])
            ->get();

        return response()->json([
            'status' => 1,
            'message'   => 'Search success',
            'search' => $search,
            'products' => $products
        ]);
    }
}
