<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\User;
use App\Models\Product;
use App\Models\Rating;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['data' => 'abc']);
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
            $request = $request->all();
            if(!empty($request)){
                if(!empty($request['userId'])){
                    $user = User::find($request['userId']);
                }
                
                if (empty($user)) {
                    return response()->json([
                        'message'  => 'User Id not exits',
                        'status'    =>  'error'
                    ]);
                }

                if(!empty($request['idProduct'])){
                    $product = Product::find($request['idProduct']);
                }

                if(empty($product)){
                    return response()->json([
                        'message'  => 'Product Id not exits',
                        'status'    =>  'error'
                    ]);
                }

                $comment = Review::create([
                    'user_id' => !empty($request['userId']) ? intval($request['userId']) : 0, 
                    'product_id' => !empty($request['idProduct']) ? intval($request['idProduct']) : 0,
                    'content' => !empty($request['message']) ? $request['message'] : ''
                ]);

                $rating = Rating::create([
                    'user_id'  => !empty($request['userId']) ? intval($request['userId']) : 0,
                    'product_id' => !empty($request['idProduct']) ? intval($request['idProduct']) : 0,
                    'rating'    =>  !empty($request['rating']) ? intval($request['idProduct']) : 0,
                ]);

                return response()->json([
                    'message' => 'Save thành công',
                    'status'  => 'success',
                    // 'data'  => $user,
                ], 201);
            }
            return response()->json([
                'message'   => 'Fail khong them dc',
                'status'    => 'error',
            ]);
            
        } catch (\Throwable $th) {
            dd($th);
            return response()->json([
                'message' => 'Fail',
                'status' => 'error',
                'error' => $th
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
