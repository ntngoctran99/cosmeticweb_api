<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Blog;

class HomePageController extends Controller
{
    public function listProducts()
    {
        $productSamples = DB::table('products')
            ->join('images', 'products.id', '=', 'images.product_id')
            ->select('products.*', 'images.id as image_id','images.product_id', 'images.image', 'images.type_image', 'images.del_flag')
            ->where([
                ['products.del_flag', '=', 0],
                ['images.del_flag', '=', 0],
                ['images.type_image', '=', 1]
            ])
            ->orderBy('updated_at','DESC')
            ->limit(8)->get();
        $latestProducts = DB::table('products')
            ->join('images', 'products.id', '=', 'images.product_id')
            ->select('products.*', 'images.id as image_id','images.product_id', 'images.image', 'images.type_image', 'images.del_flag')
            ->where([
                ['products.del_flag', '=', 0],
                ['products.latest', '=', 1],
                ['images.type_image', '=', 1]
            ])
            ->orderBy('updated_at','DESC')
            ->limit(4)->get();

        $topRatedProducts = DB::table('products')
            ->join('images', 'products.id', '=', 'images.product_id')
            ->select('products.*', 'images.id as image_id','images.product_id', 'images.image', 'images.type_image', 'images.del_flag')
            ->where([
                ['products.del_flag', '=', 0],
                ['images.del_flag', '=', 0],
                ['products.top_rated', '=', 1],
                ['images.type_image', '=', 1]
            ])
            ->orderBy('updated_at','DESC')
            ->limit(4)->get();

        $typeProducts = DB::table('type_products')
            ->where([
                ['type_products.del_flag', '=', 0],
            ])
            ->get();

        //$typeProducts = TypeProduct::all();
        // $blogs = $this->blogsHome();
        // return view('Products.homepage', compact('typeProducts', 'productSamples', 'latestProducts', 'topRatedProducts', 'blogs'));

        $blogs = Blog::latest()->paginate(3);

        return response()->json([
            'status' => 1,
            'message'   => 'Load data success',
            'key1' => $typeProducts,
            'key2' => $productSamples,
            'key3' => $latestProducts,
            'key4' => $topRatedProducts,
            'key5' => $blogs
        ]);
    }
}
