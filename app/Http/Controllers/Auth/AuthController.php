<?php
namespace App\Http\Controllers\Auth;

use Auth;
use App\Model\Photo;
use App\Model\User;
use Illuminate\Database\Eloquent\Model;
use Validator;
use Illuminate\Http\Request;
use Request as HttpRequest;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Qiniu\Auth as QiniuAuth;
use Qiniu\Storage\BucketManager;

class AuthController extends Controller

{

    protected $redirectPath = '/auth';
    protected $redirectAfterLogout = '/auth/login';
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }



    public function loginUsername()
    {
        return 'email';
    }



    public function getLogin(Request $request)
    {
        if (view()->exists('auth.authenticate')) {
            return view('auth.authenticate');
        }

        if ($request->has('return_url')) {
            $request->session()->put('url.intended', $request->input('return_url'));
        }

        $result = array();
        $wbAkey = config('config.weibo.app_key');
        $wbSkey = config('config.weibo.app_secret');
        $auth = new \SaeTOAuthV2($wbAkey, $wbSkey);
        $result['weiboAuthorizeUrl'] = $auth->getAuthorizeURL(route('authAuthLoginByWeibo'));

        return view('auth.login', $result);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }



    /**

     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Get the failed login message.
     *
     * @return string
     */

    protected function getFailedLoginMessage()
    {
        return '账号或密码不正确.';
    }



    protected function loginByWeibo(Request $request)
    {
        try{
            $returnUrl = $request->session()->pull('url.intended', $this->redirectPath);
            $wbAkey = config('config.weibo.app_key');
            $wbSkey = config('config.weibo.app_secret');
            $auth = new \SaeTOAuthV2($wbAkey, $wbSkey);

            if (!$request->has('code')) {
                throw new \Exception('code获取失败');
            }

            //通过code获取access_token
            $param = ['code' => $request->input('code'), 'redirect_uri' => config('config.weibo.redirect_uri')];
            $accessToken = $auth->getAccessToken('code', $param);
            //查询用户是否存在
            $uid = $accessToken['uid'];
            $user = User::where('weibo_id', $uid)->first();
            if (is_null($user)) {
                //获取微博用户信息
                $client = new \SaeTClientV2($wbAkey, $wbSkey, $accessToken['access_token'] );
                $userInfo = $client->show_user_by_id($uid);
                if (isset($userInfo['error_code'])) {
                    throw new \Exception($userInfo['error']);
                }
                $user = new User();
                $user->name = $userInfo['screen_name'];
                $user->email = $userInfo['id'];
                $user->password = bcrypt('123456');
                $user->created_at = time();
                $user->weibo_id = $userInfo['id'];
                $user->save();
                //用户头像本地化
                if ($userInfo['avatar_hd']) {
                    $auth = new QiniuAuth(config('config.qiniu.access_key'), config('config.qiniu.secret_key'));
                    $bucketManager = new BucketManager($auth);
                    list($ret, $err) = $bucketManager->fetch($userInfo['avatar_hd'], config('config.qiniu.bucket'), $userInfo['screen_name']);
                    if ($err != null) {
                        throw new \Exception($err->message());
                    }

                    $photo = Photo::create(['user_id' => $user->id,
                        'photo_type' => config('const.PHOTO_TYPE.IMAGE_TYPE_AVATAR'),
                        'photo_name' => time(),
                        'photo_key' => $ret["key"]]);

                    if (!$photo) {
                        throw new \Exception('图片插入数据库失败');
                    }
                    $user->avatar = $ret["key"];
                    $user->save();
                }
            }
            Auth::login($user);
            return redirect($returnUrl);
        }catch (\Exception $e){
            abort(404);
        }
    }
}

