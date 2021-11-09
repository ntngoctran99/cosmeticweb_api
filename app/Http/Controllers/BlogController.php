<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index() {
        $blogs = Blog::latest()->paginate(20);
        //return view('blog.index', ['blogs' => $blogs]);
        return response()->json([
            'status' => 1,
            'message'   => 'Load blog list success',
            'data' => $blogs
        ]);
    }

    public function detail($id) {
        $blog = Blog::find($id);
        $blogs = Blog::latest()->take(3)->get();
        //return view('blog.detail', ['blog' => $blog, 'blogs' => $blogs]);

        return response()->json([
            'status' => 1,
            'message'   => 'Load detail blog success',
            'blog' => $blog,
            'blogs' => $blogs
        ]);
    }
}
