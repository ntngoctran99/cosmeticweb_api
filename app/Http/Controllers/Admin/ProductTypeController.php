<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\TypeProduct;

class ProductTypeController extends Controller
{
    public function index()
    {
        $lists = TypeProduct::orderBy('id', 'DESC')->paginate(20);
        //return view('admin.pages.product_type.index', compact('lists'));

        return response()->json([
            'status' => 1,
            'message'   => 'Load Type Product List success',
            'data' => $lists
        ]);
    }

//    public function create()
//    {
//        return view('admin.pages.product_type.create');
//    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|max:255',
            'avatar' => 'required|image|mimes:jpeg,png,jpg',
        ], [
            'name.required'   => 'Please enter a name',
            'name.max'        => 'The name can only be up to 255 characters long',
            'avatar.required' => 'Please choose a image',
            'avatar.image'    => 'Please upload file in these format only (jpg, jpeg, png)',
            'avatar.mimes'    => 'Please upload file in these format only (jpg, jpeg, png)',
        ]);

        if ($validator->fails()) {
            //return \Redirect::back()->withErrors($validator)->withInput();
            return response()->json([
                'status' => -2,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        $imagePath = $this->uploadImage($request);

        if (empty($imagePath)) {
            /*return \Redirect::route('admin.product_type.index')
                ->with([
                    'flashLevel' => 'warning',
                    'flashMes'   => 'Upload Image Fail'
                ]);*/

            return response()->json([
                'status' => -1,
                'flashLevel' => 'warning',
                'flashMes' => 'Upload Image Fail',
            ]);
        }

        $type_product = TypeProduct::create([
            'name'        => $request->name,
            'description' => $request->description,
            'image'       => $imagePath
        ]);

        /*return \Redirect::route('admin.product_type.index')
            ->with([
                'flashLevel' => 'success',
                'flashMes'   => 'Create Product Type Success'
            ]);*/

        return response()->json([
            'status' => 1,
            'data' => $type_product,
            'flashLevel' => 'success',
            'flashMes' => "Create Product Type Successful!",
        ]);
    }

    /*public function edit($id)
    {
        $item = TypeProduct::find($id);

        if (empty($item)) {
            return \Redirect::route('admin.product_type.index')
                ->with([
                    'flashLevel' => 'warning',
                    'flashMes'   => 'Not Found The Product Type'
                ]);
        }

        return view ('admin.pages.product_type.edit', compact('item'));
    }*/

    public function update(Request $request, $id)
    {
        $item = TypeProduct::find($id);

        if (empty($item)) {
            /*return \Redirect::route('admin.product_type.index')
                ->with([
                    'flashLevel' => 'warning',
                    'flashMes'   => 'Not Found The Product Type'
                ]);*/

            return response()->json([
                'status' => -1,
                'flashLevel' => 'warning',
                'flashMes' => 'Not Found The Product Type',
            ]);
        }

        if ($request->hasFile('avatar')) {
            $imagePath = $this->uploadImage($request);

            if (empty($imagePath)) {
                /*return \Redirect::route('admin.product_type.index')
                    ->with([
                        'flashLevel' => 'warning',
                        'flashMes'   => 'Upload Image Fail'
                    ]);*/

                return response()->json([
                    'status' => -1,
                    'flashLevel' => 'warning',
                    'flashMes' => 'Upload Image Fail',
                ]);
            }

            $item->update([
                'name'        => $request->name,
                'description' => $request->description,
                'image'       => $imagePath
            ]);
        }
        else {
            $item->update([
                'name'        => $request->name,
                'description' => $request->description
            ]);
        }

        /*return \Redirect::route('admin.product_type.index')
            ->with([
                'flashLevel' => 'success',
                'flashMes'   => 'Update Product Type ' .$request->name . ' Info Success'
            ]);*/

        return response()->json([
            'status' => 1,
            'flashLevel' => 'success',
            'flashMes' => "Update Product Type ' .$item->name . ' Info Success",
        ]);

    }

    public function uploadImage($request)
    {
        if ($request->hasFile('avatar')) {
            $imageFile = $request->avatar;

            $uploadFolder = 'uploads';
            if (!is_dir ($uploadFolder)) {
                mkdir($uploadFolder, 0777);
            }

            $folderName = 'uploads/' . date("Y") . '-' . date("m");
            $folderFullName = public_path($folderName);
            if (!is_dir ($folderFullName)) {
                mkdir($folderFullName, 0777);
            }

            $imageName = 'product_type' . time().'.'.$imageFile->extension();

            $imageFile->move($folderFullName, $imageName);

            return $folderName . '/' . $imageName;
        }

        return null;
    }
}
