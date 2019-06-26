<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;

class RolesController extends Controller {

    /**
     * 角色列表
     */
    public function index(Request $request)
    {
        $result = array('roles' => array(), 'menu' => array());
        $roles = DB::table('SYS_ROLE')->paginate(10);
        $result['data'] = $roles;

        $menu_result = DB::table('SYS_MENU')
            ->select('id', 'title')
            ->where('is_show_role', '=', 1)
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

        return view('admin.roles.index', array('result' => $result));
    }

    /**
     * 角色添加
     */
    public function add(Request $request)
    {
        $result = array('result' => true);
        try {
            $validator = Validator::make($request->all(), [
                'role_name' => 'required',
                'role_describe' => 'required',
            ], [
                'role_name.required' => '角色名称不能为空',
                'role_describe.required' => '角色描述不能为空',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }
            $action_list = empty($request->input('action')) ? '' : implode(',', $request->input('action'));

            DB::table('SYS_ROLE')->insert([
                'role_name' => $request->input('role_name'),
                'role_describe' => $request->input('role_describe'),
                'action_list' => $action_list,
            ]);
        } catch(\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * 角色编辑
     */
    public function edit(Request $request)
    {
        $result = array('result' => true);
        try {
            $validator = Validator::make($request->all(), [
                'role_id' => 'required',
                'role_name' => 'required',
                'role_describe' => 'required',
            ], [
                'role_id.required' => 'ID不能为空',
                'role_name.required' => '角色名称不能为空',
                'role_describe.required' => '角色描述不能为空',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }
            $action_list = empty($request->input('action')) ? '' : implode(',', $request->input('action'));

            DB::table('SYS_ROLE')
                ->where('role_id', $request->input('role_id'))
                ->update([
                'role_name' => $request->input('role_name'),
                'role_describe' => $request->input('role_describe'),
                'action_list' => $action_list,
            ]);
        } catch(\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * 角色删除
     */
    public function delete(Request $request)
    {
        $result = array('result' => true);
        try {
            DB::table('SYS_ROLE')
                ->whereIn('role_id', $request->input('ids'))
                ->where('is_sys', '<>', 1)
                ->delete();

            DB::table('SYS_ADMIN_USER')
                ->whereIn('role_id', $request->input('ids'))
                ->update([
                'role_id' => 0
            ]);
        } catch(\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }
}
