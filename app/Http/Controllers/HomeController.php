<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index(){


        $response =  [
            'status'=>'success',
            'message' => "Request Send Successful",
           ];
           return response($response,200);
    }
}
