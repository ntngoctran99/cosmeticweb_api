<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index(Request $request)
    {
        /*$cart_detail = $request->session()->get('cart');
        if (!empty($cart_detail) && count($cart_detail) > 0) {
            $total_price = 0;
            // update product if info change when save session time long
            $cart = $this->queryUpdateCartProduct($cart_detail);

            $request->session()->put('cart', $cart);

            foreach ($cart as $key => $item) {
                $total_price += $item['quantity'] * $item['detail']->unit_price;
            }
            // dd($cart_detail);
            return view('Cart.cartDetail', ['cart_detail' => $cart_detail, 'total_price' => $total_price]);
        }else{
            return view('Cart.cartDetail', ['cart_detail' => $cart_detail, 'total_price' => 0]);
        }*/

        $cart_detail = $request->session()->get('cart');
        if (!empty($cart_detail) && count($cart_detail) > 0) {
            $total_price = 0;
            // update product if info change when save session time long
            $cart = $this->queryUpdateCartProduct($cart_detail);

            $request->session()->put('cart', $cart);

            foreach ($cart as $key => $item) {
                $total_price += $item['quantity'] * $item['detail']->unit_price;
            }
            // dd($cart_detail);
            //return view('Cart.cartDetail', ['cart_detail' => $cart_detail, 'total_price' => $total_price]);

            return response()->json([
                'status' => 1,
                'message'   => 'Load Cart detail success',
                'cart_detail' => $cart_detail,
                'total_price' => $total_price
            ]);
        }else{
            //return view('Cart.cartDetail', ['cart_detail' => $cart_detail, 'total_price' => 0]);
            return response()->json([
                'status' => 1,
                'message'   => 'Load Cart detail success',
                'cart_detail' => $cart_detail,
                'total_price' => 0
            ]);
        }
    }

    // update info product except the case
    public function queryUpdateCartProduct($cart)
    {
        $ids = array_keys($cart);

        // check value product have in array ids
        $products = Product::whereIn('id', $ids)->get();

        foreach ($cart as $key => $value) {

            foreach ($products as $k => $val) {
                if($val->id === $key){
                    $data = $val;
                }
            }

            if ($data) {
                $cart[$key]['detail'] = $data;
            }
        }

        return $cart;
    }

    public function checkoutBill(Request $request)
    {
        $cart_detail = $request->session()->get('cart');

        if (!empty($cart_detail) && count($cart_detail) > 0) {
            $total_price = 0;
            // update product if info change when save session time long
            $cart = $this->queryUpdateCartProduct($cart_detail);

            $request->session()->put('cart', $cart);

            foreach ($cart as $key => $item) {
                $total_price += $item['quantity'] * $item['detail']->unit_price;
            }

            return view('Cart.checkout', ['cart_detail' => $cart_detail, 'total_price' => $total_price]);
        }else{
            return view('Cart.checkout', ['cart_detail' => $cart_detail, 'total_price' => 0]);
        }
    }

    public function remove(Request $request)
    {
        try {
            $id = (int)$request->id;
            $cart = $request->session()->get('cart');
            unset($cart[$id]);
            $request->session()->put('cart', $cart);
            return response()->json([
                'status'    => true,
                'message'   => 'remove success'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status'    => false,
                'message'   => $th
            ]);
        }
    }

    public function forgetSession(Request $request)
    {
        try {
            if($request->session()->get('cart')){
                $request->session()->forget('cart');
                return response()->json([
                    'status'    => true,
                    'message'   => 'Success'
                ]);
            }else{
                return response()->json([
                    'status'    => false,
                    'message'   => 'Fail'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status'    => false,
                'message'   => $th
            ]);
        }

    }

    public function add(Request $request)
    {
        try {
            if(!empty($request->all())){

                $id = (int)$request->id;
                $quantity = (int)$request->quantity;

                // get session cart
                $cart = $request->session()->get('cart');

                // check cart exits in session then save quantity
                if (isset($cart[$id])) {
                    $cart[$id]['quantity'] = $cart[$id]['quantity'] + $quantity;
                }else{
                    $cart[$id]['quantity'] = $quantity;
                    // get product with id
                    $product = Product::where('id', $id)->first();

                    if(empty($product)){
                        return [
                            'status' => 'error',
                            'message' => 'Id not exits',
                            'success'  => false
                        ];
                    }

                    // save info product in a array
                    $cart[$id]['detail'] = $product;
                }
                // save session cart
                $request->session()->put('cart', $cart);
                Session::put('carts', $cart);

                return response()->json([
                    'status' => 1,
                    'success' => true,
                    'message' => "Create Cart Successful!",
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Fail',
                'data'  => $th
            ]);
        }

        return response()->json([
            'status' => 1,
            'data' => $cart,
            'message' => "Create Cart Successful!",
        ]);
    }

    public function checkCart(Request $request)
    {
        $cart = $request->session()->get('cart');
        return response()->json([
            'total_items' =>  sizeof($cart)
        ]);
    }


}
