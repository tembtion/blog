<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class IndexController extends Controller {

    /**
     * 显示所给定的用户个人数据。
     *
     * @param  int  $id
     * @return Response
     */
    public function index()
    {
        $result['controller'] = 'top';
        $result['action'] = 'index';
        return view('admin.top.index', $result);
    }

}
