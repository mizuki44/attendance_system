<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;

class LoginController extends Controller
{
    public function getIndex()
    {
        return view('auth.login');
    }

    public function postIndex(LoginRequest $request)
    {
        $email = $request->email;
        $password = $request->password;
        if (Auth::attempt([
            'email' => $email,
            'password' => $password
        ])) {
            return redirect('/');
        } else {
            return redirect('/login');
        }
    }
}
