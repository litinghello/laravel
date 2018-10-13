<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function _list($db=null,$result=[]){

        if(is_null($db))
            return $result;


        $perPage = 10;

        $page = $db->paginate($perPage);


        if (($totalNum = $page->total()) > 0) {
            list($curPage, $maxNum) = [$page->currentPage(), $page->lastPage()];
            list($pattern, $replacement) = [['/href="(.*?)"/', '/pagination/'], ['href="$1"', 'pagination pull-right']];
            $html = "<span class='pagination-trigger nowrap'>共 {$totalNum} 条记录，每页显示 $perPage 条，共 {$maxNum} 页当前显示第 {$curPage} 页。</span>";
            list($result['total'], $result['list'], $result['page']) = [$totalNum, $page->all(), $html . preg_replace($pattern, $replacement, $page->render())];
        }else{
            $result['list']=[];
        }

        return $result;
    }


}
