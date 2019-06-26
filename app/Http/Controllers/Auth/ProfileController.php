<?php 
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Logic;
use Auth;
use Validator;
use Hash;

class ProfileController extends Controller {

    /**
     * 个人资料
     */
    public function index(Request $request)
    {
        return view('auth.profile.index');
    }

    /**
     * 个人资料
     */
    public function avatarUpload(Request $request)
    {
        $model = new Logic\Photo();
        $result = $model->upload(config('const.PHOTO_TYPE.IMAGE_TYPE_AVATAR'));

        return response(json_encode($result));
    }

    /**
     * 编辑资料
     */
    public function edit(Request $request)
    {
        $result = array('result' => true);
        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|numeric',
                'email' => 'required|email',
            ], [
                'phone.required' => '电话号码不能为空',
                'phone.numeric' => '电话号码格式不正确',
                'email.required' => '邮箱不能为空',
                'email.required' => '邮箱格式不正确',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            $user = Auth::getUser();
            $user->phone = $request->input('phone');
            $user->email = $request->input('email');
            if ($request->has('avatar') && !empty($request->input('avatar'))) {
                $user->avatar = $request->input('avatar');
            }
            $user->save();

        } catch(\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * 修改密码
     */
    public function passwordreset(Request $request)
    {
        $result = array('result' => true);
        try {
            $validator = Validator::make($request->all(), [
                'password_login' => 'required',
                'password' => 'required|min:6|max:20|confirmed',
                'password_confirmation' => 'required',
            ], [
                'password_login.required' => '登录密码不能为空',
                'password.required' => '新密码不能为空',
                'password.min' => '密码长度不能小于6位',
                'password.max' => '密码长度不能大于20位',
                'password_confirmation.required' => '确认密码不能为空',
                'password.confirmed' => '输入的密码不一致',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            if (!Hash::check($request->input('password_login'), Auth::getUser()->password)) {
                throw new \Exception('登录密码不正确');
            }

            $user = Auth::getUser();
            $user->password = bcrypt($request->input('password'));
            $user->save();

        } catch(\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }
}
