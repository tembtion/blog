<?php
namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model;
use View;

class PhotoController extends Controller {

    public function item(Request $request)
    {
        $result = array('result' => true);
        try {
            // 判断相册是否存在
            $album = Model\Album::find($request->input('album_id'));
            if (is_null($album)) {
                throw new \Exception('相册信息不存在');
            }
            $data['photos'] = Model\Photo::where('album_id', $album->album_id)
                ->where('photo_type', config('const.PHOTO_TYPE.IMAGE_TYPE_PHOTO'))
                ->paginate(20);
            $result['content'] = View::make('home.photo.item', $data)->render();
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }
}