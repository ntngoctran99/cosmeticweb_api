<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        $lists = Supplier::orderBy('id', 'DESC')->paginate(20);
        return view('admin.pages.suppliers.index', compact('lists'));
    }

    public function create()
    {
        return view('admin.pages.suppliers.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|max:50',
            'address'      => 'required|max:255',
            'email'        => 'required|email|max:255',
            'phone_number' => 'required|max:20',
        ], [
            //Name
            'name.required' => 'Please enter a name',
            'name.max'      => 'Your name can only be up to 50 characters long',
            //Address
            'address.required' => 'Please enter a address',
            'address.max'      => 'Your address can only be up to 255 characters long',
            //Email
            'email.required' => 'Please enter a email',
            'email.email'    => 'Please enter a vaild email address',
            'email.max'    => 'Your email can only be up to 255 characters long',
            //Phone Number
            'phone_number.required' => 'Please enter a phone',
            'phone_number.max'      => 'Your phone can only be up to 20 characters long',
        ]);

        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator)->withInput();
        }

        Supplier::create([
            'name'         => $request->name,
            'address'      => $request->address,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        return \Redirect::route('admin.supplier.index')
            ->with([
                'flashLevel' => 'success',
                'flashMes'   => 'Create Supplier Success'
            ]);
    }

    public function edit($id)
    {
        $item = Supplier::find($id);

        if (empty($item)) {
            return \Redirect::route('admin.supplier.index')
                ->with([
                    'flashLevel' => 'warning',
                    'flashMes'   => 'Not Found The Supplier'
                ]);
        }

        return view ('admin.pages.suppliers.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Supplier::find($id);

        if (empty($item)) {
            return \Redirect::route('admin.supplier.index')
                ->with([
                    'flashLevel' => 'warning',
                    'flashMes'   => 'Not Found The Supplier'
                ]);
        }

        $item->update([
            'name'         => $request->name,
            'address'      => $request->address,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        return \Redirect::route('admin.supplier.index')
            ->with([
                'flashLevel' => 'success',
                'flashMes'   => 'Update Supplier ' .$request->name . ' Info Success'
            ]);

    }
}
