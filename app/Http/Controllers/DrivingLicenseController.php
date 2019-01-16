<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DrivingLicenseImgs;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class DrivingLicenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function upfile(Request $request){
        return view('layouts.upfile');
    }
    public function upfile_suc(Request $request){
        DrivingLicenseImgs::create([
            'order_src_id'=>$request['order_src_id'],
            'img'=>$request['img'],
        ]);
    }
    public function get_upfile_url(Request $request){
        $validator = Validator::make($request->all(), [
            'order_src_type' => 'required|alpha_num',
            'order_src_id' => 'required|alpha_num',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 1,'data' => $validator->errors()->first()]);
        }
        $url = DrivingLicenseImgs::where("order_src_id",$request['order_src_id'])->first();
        return $url?$url->img:null;
    }
}
