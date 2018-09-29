<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return string
     */
    public function views_home(){
        if (Auth::check()) {
            return view('home');
        }else{
            return view('auth.login');
        }
    }
    public function views_penalty_inquire(){
        if (Auth::check()) {
            return view('penalty.inquire');
        }else{
            return view('auth.login');
        }
    }
    public function views_penalty_pay(){
        if (Auth::check()) {
            return view('penalty.pay');
        }else{
            return view('auth.login');
        }
    }
    public function views_violate_inquire(){
        if (Auth::check()) {
            return view('violate.inquire');
        }else{
            return view('auth.login');
        }
    }
}
