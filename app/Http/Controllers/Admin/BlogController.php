<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Blog;

class BlogController extends Controller
{
    public function index()
    {
        $lists = Blog::orderBy('id', 'DESC')->paginate(20);

        //return view('admin.pages.blogs.index', compact('lists'));

        return response()->json([
            'status' => 1,
            'message'   => 'Load Blog List success',
            'data' => $lists
        ]);
    }

//    public function create()
//    {
//        return view('admin.pages.blogs.create');
//    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'required|max:255',
            'content' => 'required',
            'avatar'  => 'required|image|mimes:jpeg,png,jpg',
        ], [
            'title.required'   => 'Please enter a title',
            'title.max'        => 'The title can only be up to 255 characters long',
            'content.required' => 'Please write content',
            'avatar.required'  => 'Please choose a image',
            'avatar.image'     => 'Please upload file in these format only (jpg, jpeg, png)',
            'avatar.mimes'     => 'Please upload file in these format only (jpg, jpeg, png)',
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
            /*return \Redirect::route('admin.blog.index')
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

        $blog = Blog::create([
            'title'   => $request->title,
            'content' => $request->content,
            'image'   => $imagePath
        ]);

        /*return \Redirect::route('admin.blog.index')
            ->with([
                'flashLevel' => 'success',
                'flashMes'   => 'Create Blog Success'
            ]);*/

        return response()->json([
            'status' => 1,
            'data' => $blog,
            'flashLevel' => 'success',
            'flashMes' => "Create Blog Successful!",
        ]);
    }

/*    public function edit($id)
    {
        $item = Blog::find($id);

        if (empty($item)) {
            return \Redirect::route('admin.blog.index')
                ->with([
                    'flashLevel' => 'warning',
                    'flashMes'   => 'Not Found The Blog'
                ]);
        }

        return view ('admin.pages.blogs.edit', compact('item'));
    }*/

    public function update(Request $request, $id)
    {
        $item = Blog::find($id);

        if (empty($item)) {
            /*return \Redirect::route('admin.product_type.index')
                ->with([
                    'flashLevel' => 'warning',
                    'flashMes'   => 'Not Found The Blog'
                ]);*/

            return response()->json([
                'status' => -1,
                'flashLevel' => 'warning',
                'flashMes' => 'Not Found The Blog',
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
                'title'   => $request->title,
                'content' => $request->content,
                'image'   => $imagePath
            ]);
        }
        else {
            $item->update([
                'title'   => $request->title,
                'content' => $request->content
            ]);
        }

        /*return \Redirect::route('admin.blog.index')
            ->with([
                'flashLevel' => 'success',
                'flashMes'   => 'Update Blog ' .$request->name . ' Success'
            ]);*/

        return response()->json([
            'status' => 1,
            'flashLevel' => 'success',
            'flashMes' => 'Update Blog ' .$item->name . ' Success',
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

            $imageName = 'blog_' . time().'.'.$imageFile->extension();

            $imageFile->move($folderFullName, $imageName);

            return $folderName . '/' . $imageName;
        }

        return null;
    }
}
