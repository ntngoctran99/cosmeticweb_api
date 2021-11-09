<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index() {
        return view('contact');
    }

    public function sendMail(Request $request) {
        $request->all();

        \Mail::send('mail', array(
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'form_message' => $request->get('message'),
        ), function($message) use ($request){
            $message->from($request->get('email'));
            $message->to("itsj@gmail.com", "Hello Admin")->subject("Send Mail Contact");
        });

        return redirect()->route("contact")->with('send_success', 'Thanks for contacting!');
    }
}
