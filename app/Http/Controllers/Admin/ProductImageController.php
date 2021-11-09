<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\ProductImage;

class ProductImageController extends Controller
{
    public function index($id)
    {
        $item = Product::find($id);

        if (empty($item)) {
//            return \Redirect::route('admin.product.index')
//                ->with([
//                    'flashLevel' => 'warning',
//                    'flashMes'   => 'Not Found The Product'
//                ]);

            return response()->json([
                'flashLevel' => 'warning',
                'flashMes' => 'Not Found The Product',
            ]);
        }

        $avatar = ProductImage::where([
                'product_id' => $id,
                'type_image' => 1
            ])
            ->first();

        $default_imgs = ProductImage::where([
                'product_id' => $id,
                'type_image' => 0
            ])
            ->get();

        //return view ('admin.pages.products.images.index', compact('item', 'avatar', 'default_imgs'));

        return response()->json([
            'status' => 1,
            'message'   => 'Load Product Image success',
            'item' => $item,
            'avatar' =>$avatar,
            'default_imgs' =>$default_imgs
        ]);
    }

    public function updateAvatar(Request $request, $productid)
    {
        $item = Product::find($productid);

        if (empty($item)) {
/*            return \Redirect::route('admin.product.index')
                ->with([
                    'flashLevel' => 'warning',
                    'flashMes'   => 'Not Found The Product'
                ]);*/

            return response()->json([
                'flashLevel' => 'warning',
                'flashMes' => 'Not Found The Product',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg',
        ], [
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

        $imagePath = $this->uploadImage($request, 'avatar');

        if (empty($imagePath)) {
/*            return back()
                ->with([
                    'flashLevel' => 'warning',
                    'flashMes'   => 'Upload Avatar Fail'
                ]);*/

            return response()->json([
                'flashLevel' => 'warning',
                'flashMes' => 'Upload Avatar Fail',
            ]);
        }

        $avatar = ProductImage::where([
                'product_id' => $productid,
                'type_image' => 1
            ])
            ->first();

        if (empty($avatar)) {
            ProductImage::create([
                'product_id' => $productid,
                'image'      => $imagePath,
                'type_image' => 1
            ]);

            /*return \Redirect::back()
                ->with([
                    'flashLevel' => 'success',
                    'flashMes'   => 'Upload Avatar Success'
                ]);*/

            return response()->json([
                'flashLevel' => 'success',
                'flashMes' => 'Upload Avatar Success',
            ]);
        }

        $avatar->update([
            'image' => $imagePath
        ]);

        /*return \Redirect::back()
            ->with([
                'flashLevel' => 'success',
                'flashMes'   => 'Change Avatar Success'
            ]);*/

        return response()->json([
            'flashLevel' => 'success',
            'flashMes' => 'Change Avatar Success',
        ]);
    }

    public function updateDefaultImage(Request $request, $productid)
    {
        $item = Product::find($productid);

        if (empty($item)) {
            return \Redirect::route('admin.product.index')
                ->with([
                    'flashLevel' => 'warning',
                    'flashMes'   => 'Not Found The Product'
                ]);
        }

        $validator = Validator::make($request->all(), [
            'default_image' => 'required|image|mimes:jpeg,png,jpg',
        ], [
            'default_image.required' => 'Please choose a image',
            'default_image.image'    => 'Please upload file in these format only (jpg, jpeg, png)',
            'default_image.mimes'    => 'Please upload file in these format only (jpg, jpeg, png)',
        ]);

        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator)->withInput();
        }

        $imagePath = $this->uploadImage($request, 'default_image');

        if (empty($imagePath)) {
            return back()
                ->with([
                    'flashLevel' => 'warning',
                    'flashMes'   => 'Upload Default Image Fail'
                ]);
        }

        ProductImage::create([
            'product_id' => $productid,
            'image'      => $imagePath,
            'type_image' => 0
        ]);

        return \Redirect::back()
            ->with([
                'flashLevel' => 'success',
                'flashMes'   => 'Upload Default Image Success'
            ]);
    }

    public function uploadImage($request, $input)
    {
        if ($request->hasFile($input)) {
            $imageFile = $request->$input;

            $uploadFolder = 'uploads';
            if (!is_dir ($uploadFolder)) {
                mkdir($uploadFolder, 0777);
            }

            $folderName = 'uploads/' . date("Y") . '-' . date("m");
            $folderFullName = public_path($folderName);
            if (!is_dir ($folderFullName)) {
                mkdir($folderFullName, 0777);
            }

            $imageName = 'product_image_' . time().'.'.$imageFile->extension();

            $imageFile->move($folderFullName, $imageName);

            return $folderName . '/' . $imageName;
        }

        return null;
    }
}
