<?php

namespace App\Logic;

use DB;
use App\Model;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
use Request;

class Photo
{
    /*
     * 图片上传
     */
    public function upload($image_type)
    {
        try{
            if (!Request::hasFile('file')) {
                throw new \Exception('请选择要上传的图片');
            }
            $imageFile = Request::file('file');
            $imageFileName = $imageFile->getClientOriginalName();

            $auth = new Auth(config('config.qiniu.access_key'), config('config.qiniu.secret_key'));
            $token = $auth->uploadToken(config('config.qiniu.bucket'));
            $uploadManager = new UploadManager();

            list($ret, $err) = $uploadManager->putFile($token, null, $imageFile);

            if ($err != null) {
                throw new \Exception($err->message());
            }

            $photo = Model\Photo::create(['user_id' => auth()->user()->id,
                'photo_type' => $image_type,
                'album_id' => Request::input('album_id'),
                'photo_name' => $imageFileName,
                'photo_key' => $ret["key"]]);

            if (!$photo) {
                throw new \Exception('图片插入数据库失败');
            }
            $result['state'] = "SUCCESS";
            $result['url'] = config('config.qiniu.url') . $ret["key"];
            $result['key'] = $ret["key"];
        } catch(\Exception $e) {
            $result['state'] = $e->getMessage();
        }

        return $result;
    }

    /*
     * 图片删除
     */
    public function delete()
    {
        $result = array('result' => true);
        try{
            //初始化Auth状态
            $auth = new Auth(config('config.qiniu.access_key'), config('config.qiniu.secret_key'));
            //初始化BucketManager
            $bucketMgr = new BucketManager($auth);

            foreach (Request::input('ids') as $value) {
                $photo = Model\Photo::find($value);
                if (is_null($photo)) {
                    continue;
                }
                //判断其他相册是否存在该图片
                $photoCount = Model\Photo::where('photo_key', $photo->photo_key)
                    ->where('photo_id', '<>', $value)
                    ->count();
                if ($photoCount == 0) {
                    //删除七牛文件
                    $err = $bucketMgr->delete(config('config.qiniu.bucket'), $photo->photo_key);
//                    if ($err !== null) {
//                        $errorMsg .= $photo->photo_name . $err->message();
//                    }
                }
                //删除数据库
                $photo->delete();
            }
        } catch(\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return $result;
    }

    /*
     * 图片列表
     */
    public function lists()
    {
        $files = array();
        $size = Request::has('size') ? htmlspecialchars(Request::input('size')) : config('ueditor.imageManagerListSize');
        $start = Request::has('start') ? htmlspecialchars(Request::input('start')) : 0;
        $list = Model\Photo::where('user_id', auth()->user()->id)
            ->orderBy('photo_id', 'desc')
            ->skip($start)
            ->take($size)
            ->get();
        $count = Model\Photo::where('user_id', auth()->user()->id)->count();
        foreach ($list as $value) {
            $files[] = [
                'url' => config('config.qiniu.url') . $value["photo_key"] . '?imageView2/1/w/150/h/113/q/100',
                'url_orign' => config('config.qiniu.url') . $value["photo_key"],
                'mtime' => $value["crated_at"]
            ];
        }

        $result['state'] = "SUCCESS";
        $result['list'] = $files;
        $result['start'] = $start;
        $result['total'] = $count;

        return $result;
    }

    /*
     * 上传涂鸦
     */
    public function uploadscrawl()
    {
        try{
            if (!Request::has('file')) {
                throw new \Exception('请选择要上传的图片');
            }
            $imageFile = base64_decode(Request::input('file'));
            $imageFileName = rand(1, 10000000000) . rand(1, 10000000000) . time();

            $auth = new Auth(config('config.qiniu.access_key'), config('config.qiniu.secret_key'));
            $token = $auth->uploadToken(config('config.qiniu.bucket'));
            $uploadManager = new UploadManager();

            list($ret, $err) = $uploadManager->put($token, $imageFileName, $imageFile,null, 'image/png');

            if ($err != null) {
                throw new \Exception($err->message());
            }

            $photo = Model\Photo::create(['user_id' => auth()->user()->id,
                'photo_type' => config('const.PHOTO_TYPE.IMAGE_TYPE_BLOG'),
                'photo_name' => time(),
                'photo_key' => $ret["key"]]);

            if (!$photo) {
                throw new \Exception('图片插入数据库失败');
            }
            $result['state'] = "SUCCESS";
            $result['url'] = config('config.qiniu.url') . $ret["key"];
            $result['key'] = $ret["key"];
        } catch(\Exception $e) {
            $result['state'] = $e->getMessage();
        }

        return $result;
    }
}
