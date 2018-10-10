<?php

namespace App\Http\Controllers;

use App\WechatOrder;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AdminLtesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function penalty_order_table_home(){
        return view('adminlte.home');
    }

    public function get_penalty_order_data(){
        $table = WechatOrder::all();
        return Datatables::of($table)
            ->addColumn('action', function ($table) {
                return '<a href="'.route("adminltes.table.complete", ['id'=>$table->id,'order_number'=>$table->order_number]).'" class="btn btn-xs btn-primary">完成</a>';
            })
            ->make(true);
        //        return Datatables::of(PenaltyOrder::all())->make(true);
    }

    public function set_penalty_order_data(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|alpha_num',
            'order_number' => 'required|alpha_num',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->first();
        }
        WechatOrder::where('id',$request['id'])->where('order_number',$request['order_number'])->update(['order_status' => 'completed']);
        return view('adminlte.home');
    }
}
