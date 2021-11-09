<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomAuthController extends Controller
{
    public function registration()
    {
        return view('user.registration');
    }

    public function customRegistration(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6',
            'fullname' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
            'email' => 'required|email|unique:users',
        ]);

        $validated = $validator->validated();


        $data = $request->all();
        // checkuser registed
//        $isExist = DB::table('user')->where([
//            ''
//        ])
        $check = $this->create($data);

        return redirect("/")->withSuccess('You have signed-in');
    }

    public function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'type' => 0,
            'fullname' => 'aaaa',
            'phone_number' => $data['phone_number'],
            'gender' => 0,
            'address' => $data['address'],
            'email' => $data['email']
        ]);
    }
}
