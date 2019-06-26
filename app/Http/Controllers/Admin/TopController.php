<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use DB;
use Auth;

class TopController extends Controller {

    /**
     * 显示所给定的用户个人数据。
     *
     * @param  int  $id
     * @return Response
     */
    public function home()
    {
        $result = array('menu' => array());
        $user = Auth::user()->getAttributes();

        //admin用户判断
        if ($user['is_sys'] == 1) {
            $menu_result = DB::table('SYS_MENU')
                ->select('id', 'title', 'url')
                ->orderBy('id', 'asc')
                ->get();

            foreach ($menu_result as $key => $value) {
                $id = $value->id;
                $parent = substr($id, 1, 3);
                $level = substr($id, 4, 3);
                if ($level == '000') {
                    $result['menu'][$parent]['level1'] = $value;
                } else {
                    $result['menu'][$parent]['level2'][] = $value;
                }
            }
        } else {
            //用户菜单获取
            $action_list_result = DB::table('SYS_ROLE')
                ->select('action_list')
                ->where('role_id', '=', $user['role_id'])
                ->first();

            if (!empty($action_list_result)) {
                $action_lists = explode(",", $action_list_result->action_list);
                $menu_result = DB::table('SYS_MENU')
                    ->select('id', 'title', 'url')
                    ->whereIn('id', $action_lists)
                    ->orderBy('id', 'asc')
                    ->get();

                foreach ($menu_result as $key => $value) {
                    $id = $value->id;
                    $parent = substr($id, 1, 3);
                    $level = substr($id, 4, 3);
                    if ($level == '000') {
                        $result['menu'][$parent]['level1'] = $value;
                    } else {
                        $result['menu'][$parent]['level2'][] = $value;
                    }
                }
            }
        }


        return view('admin.top.home', array('result' => $result));
    }

    /**
     * 显示所给定的用户个人数据。
     *
     * @param  int  $id
     * @return Response
     */
    public function index()
    {
        return view('admin.top.index');
    }
}
