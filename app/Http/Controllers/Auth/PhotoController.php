<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Model;
use App\Logic;

class PhotoController extends Controller
{
    /**
     * 图片上传
     */
    public function upload()
    {
        $result = array('result' => true);
        try {
            $model = new Logic\Photo();
            $result = $model->upload(config('const.PHOTO_TYPE.IMAGE_TYPE_PHOTO'));
        } catch(\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * 图片删除
     */
    public function delete(Request $request)
    {
        $result = array('result' => true);
        try {
            $model = new Logic\Photo();
            $result = $model->delete();
        } catch(\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }
}
