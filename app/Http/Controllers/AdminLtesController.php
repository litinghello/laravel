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
//        $result = parent::_list(DB::table('user_order_info'));
//        return view('adminlte.home', $result);


//        return view('adminlte.home');


        $result = DB::table('user_order_info');

        $get = $request->input();

        foreach (['order_number'] as $key)
        {

            (isset($get[$key]) && $get[$key] !== '') && $result->where($key,'like','%'.$get[$key].'%');
        }


        if (isset($get['date']) && $get['date'] !== '') {
            list($start, $end) = explode(' - ', $get['date']);
            $result->whereBetween('created_at', ["{$start} 00:00:00", "{$end} 23:59:59"]);
        }


        isset($get['limit']) && $limit = $get['limit'];

        $result  = $result->paginate($limit?:10)->toArray();

        $data = [
            'code' => 0,
            'msg' => '正在请求中...',
            'count' => $result['total'],
            'data' => $result['data']
        ];
        return response()->json($data);

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
        $rs = UserOrderInfo::where('id',$request['id'])->where('order_number',$request['order_number'])->update(['order_status' => 'completed']);

        if($rs)
        {

            return response()->json(['state'=>0,'data'=>$rs]);
        }
        return response()->json(['state'=>1,'data'=>'请再次提交']);
//        return view('adminlte.home');

//        return redirect()->back();
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
