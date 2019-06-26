<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;
use Route;

class MenusController extends Controller {

    /**
     * 菜单一览
     */
    public function index(Request $request)
    {
        $result = array('menus' => array());
        $result['menus'] = DB::table('SYS_MENU')->paginate(10);

        return view('admin.menus.index', array('result' => $result));
    }

    /**
     * 菜单添加
     */
    public function add(Request $request)
    {
        $result = array('result' => true);
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'title' => 'required',
                'url' => 'required',
            ], [
                'id.required' => 'ID不能为空',
                'title.required' => '名称不能为空',
                'url.required' => '链接不能为空',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }
            //验证菜单ID是否存在
            $menu_exist = DB::table('SYS_MENU')
                ->where('id', '=', $request->input('id'))
                ->count('id');
            if ($menu_exist > 0) {
                throw new \Exception('菜单ID已经存在!');
            }
            //验证url是否存在
            if (!Route::has($request->input('url'))) {
                throw new \Exception('url不存在请查询');
            }

            DB::table('SYS_MENU')->insert([
                'id' => $request->input('id'),
                'title' => $request->input('title'),
                'url' => $request->input('url'),
            ]);
        } catch(\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * 菜单编辑
     */
    public function edit(Request $request)
    {
        $result = array('result' => true);
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'title' => 'required',
                'url' => 'required',
            ], [
                'id.required' => 'ID不能为空',
                'title.required' => '名称不能为空',
                'url.required' => '链接不能为空',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }
            //验证url是否存在
            if (!Route::has($request->input('url'))) {
                throw new \Exception('url不存在请查询');
            }

            $data = [
                'id' => $request->input('id'),
                'title' => $request->input('title'),
                'url' => $request->input('url'),
            ];

            DB::table('SYS_MENU')
                ->where('id', $request->input('id'))
                ->update($data);
        } catch(\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * 操作员删除
     */
    public function delete(Request $request)
    {
        $result = array('result' => true);
        try {
            DB::table('SYS_MENU')
                ->whereIn('id', $request->input('ids'))
                ->delete();
        } catch(\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }
}
