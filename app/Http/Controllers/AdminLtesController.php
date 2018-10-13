<?php

namespace App\Http\Controllers;

use App\UserOrderInfo;
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
    public function order_table_home(Request $request)
    {
//        $table = UserOrderInfo::all();

//        $result = parent::_list(UserOrderInfo::paginate(10));

//        $result = parent::_list(UserOrderInfo::class);

        $result = parent::_list(DB::table('user_order_info'));
        return view('adminlte.home', $result);

    }
    public function get_order_data(){
//        $table = UserOrderInfo::all();
//        return Datatables::of($table)
//            ->addColumn('action', function ($table) {
//                return '<a href="'.route("adminltes.table.complete", ['id'=>$table->id,'order_number'=>$table->order_number]).'" class="btn btn-xs btn-primary">完成</a>';
//            })
//            ->make(true);

        return redirect()->route("adminltes.table.home");
    }

    public function set_order_data(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|alpha_num',
            'order_number' => 'required|alpha_num',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->first();
        }
        UserOrderInfo::where('id',$request['id'])->where('order_number',$request['order_number'])->update(['order_status' => 'completed']);
//        return view('adminlte.home');

        return redirect()->back();
    }

    public function get_order_detail(Request $request){
        $validator = Validator::make($request->all(), [
            'order_src_id' => 'required|alpha_num',
            'order_src_type' => 'required|alpha_num',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 1, 'data' => $validator->errors()->first()]);
        }
        switch($request['order_src_type']){
            case 'penalty':
                $order_info = UserOrderInfo::where('order_src_id', $request['order_src_id'])->first()->penalty_info;
                if ($order_info != null) {
                    return response()->json(['status' => 0, 'data' => $order_info]);
                }
                break;
            case 'violate':
                $order_info = UserOrderInfo::where('order_src_id', $request['order_src_id'])->first()->violate_info;
                if ($order_info != null) {
                    return response()->json(['status' => 0, 'data' => $order_info]);
                }
                break;
            default:
                break;
        }
        return response()->json(['status' => 1, 'data' => "请求数据失败"]);
    }

}
