<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status = 1;
        $data = Order::all();
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
        try {
            if(!empty($request->all())){
                // check id User có tồn tại không
                $user = User::find($request->input('idUser'))->first();
                if(!empty($user)){
                    $condition_order = [
                        'total' => $request->input('total_product') ? $request->input('total_product') : 0,
                        'payment' => $request->input('payment') ? $request->input('payment') : 0,
                        'note' => $request->input('note') ? $request->input('note') : '',
                        'status' => $request->input('status') ? $request->input('status') : 0,
                        'fullname' => $request->input('fullname') ? $request->input('fullname') : '',
                        'address' => $request->input('address') ? $request->input('address') : '',
                        'phone_number' => $request->input('phone_number') ? $request->input('phone_number') : '',
                        'user_id' => $user['id'],
                    ];
                    $order = Order::create($condition_order);

                    if (!empty($order)) {
                        $oder_detail = [];
                        $result = [];
                        $detail_Cart = json_decode($request->input('cart_detail'), true);

                        if (sizeof($detail_Cart) > 0) {
                            foreach ($detail_Cart as $key => $value) {
                                $oder_detail['order_id']  = $order['id'];
                                $oder_detail['product_id']  = $value['detail']['id'];
                                $oder_detail['quantity']  = $value['quantity'];
                                $oder_detail['unit_price']  = ($value['quantity'] * $value['detail']['unit_price']);
                                array_push($result, $oder_detail); 
                            }
                        }
                        
                        $insert_order = OrderDetail::insert($result);
                        if(!$insert_order){
                            return response()->json([
                                'status'    => false,
                                'message'   => 'Insert order detail not success'
                            ]);
                        }else{
                            return response()->json([
                                'status'    => true,
                                'message'   => 'Insert order detail success'
                            ]);
                        }
                    }else{
                        return response()->json([
                            'status' => 'error',
                            'message'   => 'Insert not success'
                        ]);
                    }
                }
                
            }
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => 'Fail',
                'error' =>  $th
            ]);
        }
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
        $order = Order::find($id);
        if ($order == null) {
            $status = -1;
            $message = "Cannot find this Order!";
        }
        else {
            $message = "Successful!";
            return response()->json([
                'status' => $status,
                'message' => $message,
                'data' => $order,
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
        $status = 1;
        $order = Order::find($id);
        if ($order == null) {
            $status = -1;
            $message = "Cannot find this Order!";
        }
        else {
            $order->update($request->all());
            $message = "Update Successful!";
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $status = 1;
        $order = Order::find($request->id);
        if ($order == null) {
            $status = -1;
            $message = "Cannot find this Order!";
        }
        else {
            $order->delete();
            $message = "Delete Successful!";
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }
}
