<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResetControler extends Controller
{
    public function index(){
        return view("auth.reset-password");
    }

    public function update(Request $request){
        dd($request->all());
    }
}
