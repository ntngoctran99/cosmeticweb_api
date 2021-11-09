<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
//use Auth;

class OrderController extends Controller
{
    public function index(Request $user_id)
    {
        /*if (Auth::user()) {
            $order = Order::where('user_id', Auth::user()->id)->get();
            if (!empty($order)) {
                $orderDetail = Order::with(['orderDetail.product'])->get();
                return view('Order.index', ['orderDetail' => $orderDetail]);
            }else{
                return view('Order.index', ['orderDetail' => $orderDetail]);
            }
        }else{
            return redirect('/home-page');
        }*/


        $order = Order::where(['user_id' => $user_id])->get();
        $orderDetail = Order::with(['orderDetail.product'])->get();
        //$order = Order::find($user_id);
        if ($order != null) {
            $orderDetail = Order::with(['orderDetail.product'])->get();
            return response()->json([
                'status' => 1,
                'message' => 'Load Order Detail',
                'data' => $orderDetail,
            ]);
        }else{
            return response()->json([
                'status' => -1,
                'message' => 'Not found'
            ]);
        }
    }
}
