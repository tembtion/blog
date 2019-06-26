<?php

namespace App\Http\Middleware;

use Closure;
use Route;
use DB;
use Auth;
use Illuminate\Contracts\Auth\Guard;

class Purview
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user()->getAttributes();
        //admin用户判断
        if ($user['is_sys'] == 1) {
            return $next($request);
        }
        $actions = $request->route()->getAction();
        $allowAccess = false;
        //获取当前url对应的menu_id
        $menu_result = DB::table('SYS_MENU')
            ->select('id')
            ->where('url', '=', $actions['permissions'])
            ->first();

        if ($menu_result && $menu_result->id) {
            $menu_id = $menu_result->id;
            //获取登录用户的menu列表

            $role_result = DB::table('SYS_ROLE')
                ->select('action_list')
                ->where('role_id', '=', $user['role_id'])
                ->first();
            if ($role_result) {
                $action_list = $role_result->action_list;
                $allowAccess = strpos($action_list, $menu_id);
            }
        }

        if ($allowAccess === false) {
            if ($request->ajax()) {
                return response()->json(array('result' => false, 'message' => '您没有该操作的权限.'));
            } else {
                return abort(403);
            }
        }

        return $next($request);
    }
}
