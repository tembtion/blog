<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Model;
use App\Http\Controllers\Controller;
use App\Logic;

class MediaController extends Controller
{
    /**
     * 多媒体一览
     */
    public function index()
    {
        $result = array();
        $result['photos'] = Model\Photo::where(['user_id'=>Auth::getUser()->id,'photo_type'=>config('const.PHOTO_TYPE.IMAGE_TYPE_PHOTO')])
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        return view('auth.media.index', $result);
    }

    /**
     * 多媒体添加
     */
    public function add()
    {
        return view('auth.media.add');
    }

    /**
     * 多媒体上传
     */
    public function upload()
    {
        $model = new Logic\Photo();
        $result = $model->upload(config('const.PHOTO_TYPE.IMAGE_TYPE_PHOTO'));

        return response(json_encode($result));
    }
}
