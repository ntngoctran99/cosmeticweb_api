<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $lists = Order::orderBy('id', 'DESC')->paginate(20);

        //return view('admin.pages.orders.index', compact('lists'));

        return response()->json([
            'status' => 1,
            'message'   => 'Load Order List success',
            'data' => $lists
        ]);
    }

    public function show($id)
    {
        $item = Order::find($id);

        if (empty($item)) {
            /*return \Redirect::route('admin.order.index')
                ->with([
                    'flashLevel' => 'warning',
                    'flashMes'   => 'Not Found The Order'
                ]);*/

            return response()->json([
                'status' => -1,
                'flashLevel' => 'warning',
                'flashMes' => 'Not Found The Order',
            ]);
        }

        //return view('admin.pages.orders.show', compact('item'));

        return response()->json([
            'status' => 1,
            'data' => $item
        ]);
    }

    public function updateStatus($id, $status)
    {
        $item = Order::find($id);

        if (empty($item) || !in_array($status, \Config::get('define.order.status'))) {
            /*return \Redirect::route('admin.order.index')
                ->with([
                    'flashLevel' => 'warning',
                    'flashMes'   => 'Not Found The Order'
                ]);*/

            return response()->json([
                'status' => -1,
                'flashLevel' => 'warning',
                'flashMes' => 'Not Found The Order',
            ]);
        }

        $item->update([
            'status' => $status
        ]);

        /*return \Redirect::route('admin.order.show', $id)
            ->with([
                'flashLevel' => 'success',
                'flashMes'   => 'Update Status Success'
            ]);*/

        return response()->json([
            'status' => 1,
            'flashLevel' => 'success',
            'flashMes' => "Update Status Success",
        ]);
    }
}
