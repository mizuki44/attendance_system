<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorRequest;


class AuthController extends Controller
{
     public function create()
    {
        return view('auth.login');
    }

    public function store(AuthorRequest $request)
    {
        var_dump('ok');
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    // public function index()
    // {
    //     return view('index');
    // }
    
}
