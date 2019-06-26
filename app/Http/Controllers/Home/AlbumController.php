<?php
namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model;
use View;

class AlbumController extends Controller {

    /**
     * 相册。
     */
    public function index(Request $request)
    {
        return view('home.album.index');
    }

    public function detail($album_id)
    {
        // 判断相册是否存在
        $album = Model\Album::find($album_id);
        if (is_null($album)) {
            abort(404);
        }

        return view('home.album.detail', array('album_id' => $album_id));
    }

    public function item(Request $request)
    {
        $result = array('result' => true);
        try {
            $data['album'] = Model\Album::paginate(20);
            $result['content'] = View::make('home.album.item', $data)->render();
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }
}