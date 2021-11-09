<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status = 1;
        $data = Product::all();
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
            'unit_price' => 'required',
            'image' => 'required',
            'unit' => 'required',
            'views' => 'required',
            'type_id' => 'required|exists:type_products,id',
        ], [
            //Required
            'name.required' => 'Please enter the name!',
            'unit_price.required' => 'Please enter the unit price!',
            'image.required' => 'Please enter the image',
            'unit.required' => 'Please enter the unit!',
            'views.required' => 'Please enter the view!',
            'type_id.required' => 'Please enter the Type Product!',
            //Exists
            'type_id.exists' => 'Please enter a correct Type Product!',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => -2,
                'errors' => $validator->errors()->toArray(),
            ]);
        }
        $product = Product::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'unit_price' => $request->input('unit_price'),
            'promotion_price' => $request->input('promotion_price'),
            'image' => $request->input('image'),
            'unit' => $request->input('unit'),
            'views' => $request->input('views'),
            'type_id' => $request->input('type_id'),
        ]);

        return response()->json([
            'status' => 1,
            'data' => $product,
            'message' => "Create Product Successful!",
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
        $product = Product::find($id);
        if ($product == null) {
            $status = -1;
            $message = "Cannot find this Product!";
        }
        else {
            $message = "Successful!";
            return response()->json([
                'status' => $status,
                'message' => $message,
                'data' => $product,
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
        $product = Product::find($id);
        if ($product == null) {
            $status = -1;
            $message = "Cannot find this Product!";
        }
        else {
            $product->update($request->all());
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
    public function destroy($id)
    {
        $status = 1;
        $product = Product::find($id);
        if ($product == null) {
            $status = -1;
            $message = "Cannot find this Product!";
        }
        else {
            $product->delete();
            $message = "Delete Successful!";
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }
}
