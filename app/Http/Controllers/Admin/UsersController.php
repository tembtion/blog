<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;

class UsersController extends Controller {

    /**
     * 操作员一览
     */
    public function index(Request $request)
    {
        $result = array('users' => array(), 'roles' => array());
        $result['users'] = DB::table('SYS_ADMIN_USER')->paginate(10);
        $result['roles'] = DB::table('SYS_ROLE')->lists('role_name', 'role_id');

        return view('admin.user.index', array('result' => $result));
    }

    /**
     * 操作员添加
     */
    public function add(Request $request)
    {
        $result = array('result' => true);
        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required',
                'email' => 'email',
                'user_name' => 'required',
                'password' => 'required|min:6|max:20|confirmed',
                'password_confirmation' => 'required',
            ], [
                'phone.required' => '电话号码不能为空',
                'email.email' => '邮箱格式错误',
                'user_name.required' => '用户名不能为空',
                'password.required' => '密码不能为空',
                'password.min' => '密码长度不能小于6位',
                'password.max' => '密码长度不能大于20位',
                'password_confirmation.required' => '确认密码不能为空',
                'password.confirmed' => '输入的密码不一致',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }
            //验证用户名是否存在
            $user_exist = DB::table('SYS_ADMIN_USER')
                ->where('user_name', '=', $request->input('user_name'))
                ->count('user_name');
            if ($user_exist > 0) {
                throw new \Exception('该用户已经存在，请更改用户名!');
            }
            //角色是否存在
            $role_exist = DB::table('SYS_ROLE')
                ->select('role_id')
                ->where('role_id', '=', $request->input('role_id'))
                ->first();
            if (empty($role_exist)) {
                throw new \Exception('选择的角色不存在，请修改角色!');
            }

            DB::table('SYS_ADMIN_USER')->insert([
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'role_id' => $request->input('role_id'),
                'user_name' => $request->input('user_name'),
                'password' => bcrypt($request->input('password')),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        } catch(\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * 操作员编辑
     */
    public function edit(Request $request)
    {
        $result = array('result' => true);
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'phone' => 'required',
                'email' => 'email',
                'user_name' => 'required',
                'password' => 'min:6|max:20',
                'password_confirmation' => 'confirmed',
            ], [
                'id.required' => 'ID不能为空',
                'phone.required' => '电话号码不能为空',
                'email.email' => '邮箱格式错误',
                'user_name.required' => '用户名不能为空',
                'password.min' => '密码长度不能小于6位',
                'password.max' => '密码长度不能大于20位',
                'password.confirmed' => '输入的密码不一致',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }
            //验证用户名是否存在
            $user_exist = DB::table('SYS_ADMIN_USER')
                ->where('user_name', '=', $request->input('user_name'))
                ->where('id', '<>', $request->input('id'))
                ->count('user_name');
            if ($user_exist > 0) {
                throw new \Exception('该用户已经存在，请更改用户名!');
            }
            //角色是否存在
            $role_exist = DB::table('SYS_ROLE')
                ->select('role_id')
                ->where('role_id', '=', $request->input('role_id'))
                ->first();
            if (empty($role_exist)) {
                throw new \Exception('选择的角色不存在，请修改角色!');
            }

            $data = [
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'role_id' => $request->input('role_id'),
                'user_name' => $request->input('user_name'),
            ];
            if ($request->input('password')) {
                $data['password'] = bcrypt($request->input('password'));
            }

            DB::table('SYS_ADMIN_USER')
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
            DB::table('SYS_ADMIN_USER')
                ->whereIn('id', $request->input('ids'))
                ->where('is_sys', '<>', 1)
                ->delete();
        } catch(\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }
}
