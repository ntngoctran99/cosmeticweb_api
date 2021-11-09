<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status = 1;
        $data = Supplier::all();
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'phone_number' => 'required',
        ], [
            //Required
            'total.required' => 'Please enter the total!',
            'payment.required' => 'Please enter the payment!',
            'status.required' => 'Please enter the status',
            'fullname.required' => 'Please enter the fullname!',
            'address.required' => 'Please enter the address!',
            'phone_number.required' => 'Please enter the phone number!',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => -2,
                'errors' => $validator->errors()->toArray(),
            ]);
        }
        $order = Order::create([
            'total' => $request->input('total'),
            'payment' => $request->input('payment'),
            'note' => $request->input('note'),
            'status' => $request->input('status'),
            'fullname' => $request->input('fullname'),
            'address' => $request->input('address'),
            'phone_number' => $request->input('phone_number'),
            'staff_id' => $request->input('staff_id'),
            'customer_id' => $request->input('customer_id'),
        ]);

        return response()->json([
            'status' => 1,
            'data' => $order,
            'message' => "Create Order Successful!",
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
        //
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
}
