<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {   
        var_dump('index!');
        //ログインページを表示
        return view('index');
    }
}
