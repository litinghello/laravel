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

        $perPage = 10;

//        $request->get('page') && $p = $request->get('page');


        $page = DB::table('user_order_info')->paginate($perPage);

        if (($totalNum = $page->total()) > 0) {
            list($curPage, $maxNum) = [$page->currentPage(), $page->lastPage()];
            list($pattern, $replacement) = [['/href="(.*?)"/', '/pagination/'], ['href="$1"', 'pagination pull-right']];
            $html = "<span class='pagination-trigger nowrap'>共 {$totalNum} 条记录，每页显示 $perPage 条，共 {$maxNum} 页当前显示第 {$curPage} 页。</span>";
            list($result['total'], $result['list'], $result['page']) = [$totalNum, $page->all(), $html . preg_replace($pattern, $replacement, $page->render())];
        }

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
