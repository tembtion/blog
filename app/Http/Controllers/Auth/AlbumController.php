<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Model;
use App\Logic;

class AlbumController extends Controller
{
    /**
     * 相册一览
     */
    public function index()
    {
        $result = array();
        $result['album'] = Model\Album::paginate(50);
        return view('auth.album.index', $result);
    }

    /**
     * 相册添加
     */
    public function add(Request $request)
    {
        $result = array('result' => true);
        try {
            $validator = Validator::make($request->all(), [
                'album_name' => 'required',
                'album_desc' => 'required',
            ], [
                'album_name.required' => '相册名称不能为空',
                'album_desc.required' => '相册描述不能为空',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            $album = new Model\Album();
            $album->album_name = $request->input('album_name');
            $album->album_desc = $request->input('album_desc');
            $album->created_at = date('Y-m-d H:i:s');
            $album->save();
        } catch(\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * 相册编辑
     */
    public function edit(Request $request)
    {
        $result = array('result' => true);
        try {
            $validator = Validator::make($request->all(), [
                'album_id' => 'required',
                'album_name' => 'required',
                'album_desc' => 'required',
            ], [
                'album_id.required' => '分类ID不能为空',
                'album_name.required' => '相册名称不能为空',
                'album_desc.required' => '相册描述不能为空',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            $album = Model\Album::find($request->input('album_id'));
            if (is_null($album)) {
                throw new \Exception('相册信息不存在');
            }

            $album->album_name = $request->input('album_name');
            $album->album_desc = $request->input('album_desc');
            $album->updated_at = date('Y-m-d H:i:s');
            $album->save();

        } catch(\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * 相册删除
     */
    public function delete(Request $request)
    {
        $result = array('result' => true);
        try {
            $term = Model\Term::whereIn('term_id', $request->input('ids'));
            $term->delete();

            $termTaxonomy = Model\TermTaxonomy::whereIn('term_id', $request->input('ids'));
            $termTaxonomy->delete();
        } catch(\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * 相册详细
     */
    public function detail($album_id)
    {
        $result = array();
        $album = Model\Album::find($album_id);
        if (is_null($album)) {
            abort(404);
        }
        $result['album'] = $album;
        $result['photo'] = Model\Photo::where('album_id', $album_id)->paginate(10);

        return view('auth.album.detail', $result);
    }

    /**
     * 多媒体上传
     */
    public function upload(Request $request)
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
}
