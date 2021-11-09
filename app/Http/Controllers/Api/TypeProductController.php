<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TypeProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypeProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status = 1;
        $data = TypeProduct::all();
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
            'image' => 'image|max:2048',
        ], [
            //Required
            'name.required' => 'Please enter the name!',
            //Image
            'image.image' => 'Please select an image!',
            //Size
            'image.max' => 'Capacity exceeded :max KB',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => -2,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        //Add Image
        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = $this->saveImage($request->file('image'));
        }

        $typeCustomer = TypeProduct::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'image' => $imageName,
        ]);

        return response()->json([
            'status' => 1,
            'data' => $typeCustomer,
            'message' => "Create Type Product Successful!",
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
        $typeCustomer = TypeProduct::find($id);
        if ($typeCustomer == null) {
            $status = -1;
            $message = "Cannot find this Type Product!";
        }
        else {
            $message = "Successful!";
            return response()->json([
                'status' => $status,
                'message' => $message,
                'data' => $typeCustomer,
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
        $typeCustomer = TypeProduct::find($id);
        if ($typeCustomer == null) {
            $status = -1;
            $message = "Cannot find this Type Product!";
        }
        else {
            $typeCustomer->update($request->all());
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
        $typeCustomer = TypeProduct::find($id);
        if ($typeCustomer == null) {
            $status = -1;
            $message = "Cannot find this Type Product!";
        }
        else {
            $this->deleteImage($typeCustomer->image);
            $typeCustomer->delete();
            $message = "Delete Successful!";
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }

    public function saveImage ($image) {
        if (!empty($image) && public_path('uploads')) {
            $folderName = date('Y-m');
            $fileNameWithTimestamp = md5($image->getClientOriginalName() . time());
            $fileName = $fileNameWithTimestamp . '.' . $image->getClientOriginalExtension();
            if (!file_exists(public_path('uploads/' . $folderName))) {
                mkdir(public_path('uploads/' . $folderName), 0755);
            }
            //Di chuyển file vào folder Uploads
            $imageName = "$folderName/$fileName";
            $image->move(public_path('uploads/' . $folderName), $fileName);

            return $imageName;
        }
    }

    public function deleteImage($path) {
        if (!is_dir(public_path('uploads/' . $path)) && file_exists(public_path('uploads/' . $path))) {
            unlink(public_path('uploads/' . $path));
        }
    }
}
