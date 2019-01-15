<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DrivingLicenseImgs;
class DrivingLicenseController extends Controller
{
    //

    public function upfile(Request $request)
    {

        return view('upfile');
    }
    public function upfile_suc(Request $request)
    {
        DrivingLicenseImgs::create([
            'order_src_id'=>$request['order_src_id'],
            'img'=>$request['img'],
        ]);
    }
}
