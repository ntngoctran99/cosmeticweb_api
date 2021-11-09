<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use Session;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status = 1;
        $data = Cart::all();
        if ($data->isEmpty()) {
            $status = -1;
            $message = "No Data";
        }
        else {
            $message = "Successful!";
            return response()->json([
                'status' => $status,
                'message' => $message,
                'data' => $data,
            ]);
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'quantity' => 'required',
        //     'product_id' => 'required|exists:products,id',
        //     'customer_id' => 'required|exists:customers,id',
        // ], [
        //     //Required
        //     'quantity.required' => 'Please enter the quantity!',
        //     'product_id.required' => 'Please enter the product!',
        //     'customer_id.required' => 'Please enter the customer!',
        //     //Exists
        //     'product_id.exists' => 'Please enter a correct Product!',
        //     'customer_id.exists' => 'Please enter a correct Customer!',
        // ]);
        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => -2,
        //         'errors' => $validator->errors()->toArray(),
        //     ]);
        // }

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $status = 1;
        $cart = Cart::find($id);
        if ($cart == null) {
            $status = -1;
            $message = "Cannot find this Cart!";
        }
        else {
            $message = "Successful!";
            return response()->json([
                'status' => $status,
                'message' => $message,
                'data' => $cart,
            ]);
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Check Number Product have in cart
     * Response Number
     */
    public function checkCart(Request $request)
    {
        $cart = $request->session()->get('cart');
        return response()->json([
            'total_items' =>  sizeof($cart)
        ]);
    }
}
