<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
//use Illuminate\Auth\RequestGuard;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    public $successStatus = 200;
    public $typeLogin = true; //true : admin, false: client
    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request){

//        $condition = [
//            'username' => request('username'),
//            'password' => request('password')
//        ];

        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);


        if(Auth::attempt(['username' => request('username'), 'password' => request('password')])){
            $user = Auth::user();
            if ($user['type'] == 0) {
                $this->typeLogin = false;
            }

            $success['token'] =  $user->createToken('MyApp')->accessToken;
            return response()->json(['type' => $this->typeLogin], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'role_id' => 'required|exists:roles,id',
        ], [
            //Required
            'username.required' => 'Please enter the username!',
            'password.required' => 'Please enter the password!',
            'c_password.same' => 'The c_password and password must match',
            //Exists
            'role_id.exists' => 'Please enter a correct Role!',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')-> accessToken;
        return response()->json(['success'=>$success], $this-> successStatus);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['success' => true], 201);
        // return redirect()->route('getLogin');
    }


}
