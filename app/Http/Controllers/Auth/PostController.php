<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Model;
use App\Logic;
use Auth;

class PostController extends Controller
{
    /**
     * 文章一览
     */
    public function index(Request $request)
    {
        $result = array();
        $result['filter'] = array();
        $query = Model\Post::where('post_author', '=', Auth::id());
        if ($request->has('post_status')) {
            $query = $query->where('post_status', $request->input('post_status'));
            $result['filter']['post_status'] = $request->input('post_status');
        }
        $data = $query->with('user', 'termTaxonomy')
            ->orderBy('post_date', 'desc')
            ->paginate(20);
        $result['data'] = $data;
        foreach (config('const.POST_STATUS') as $value) {
            $post_status_map[$value['value']] = $value['name'];
        }
        $result['post_status_map'] = $post_status_map;

        return view('auth.post.index', $result);
    }

    /**
     * 文章创建页面
     */
    public function create(Request $request, $post_id = null)
    {
        //获取分类
        $result = array();
        if ($post_id) {
            $post = Model\Post::find($post_id);
            $result['post'] = $post;
            $result['post_tag'] = $post->termTaxonomy()->where('taxonomy', 'post_tag')->with('term')->get();

            $post_category = $post->termTaxonomy()->where('taxonomy', 'category')->with('term')->get();
            foreach ($post_category as $value) {
                $result['post_category'][$value->term->term_id] = $value->term->name;
            }
        }
        $post_status_map = array();
        foreach (config('const.POST_STATUS') as $value) {
            $post_status_map[$value['value']] = $value['name'];
        }
        $result['post_status_map'] = $post_status_map;
        $result['category'] = Model\TermTaxonomy::where('taxonomy', 'category')->get();
        $result['tag'] = Model\TermTaxonomy::where('taxonomy', 'post_tag')->get();

        return view('auth.post.create', $result);
    }

    /**
     * 文章添加
     */
    public function add(Request $request)
    {
        $result = array('result' => true);
        try {
            $validator = Validator::make($request->all(), [
                'post_title' => 'required',
                'post_content' => 'required',
            ], [
                'post_content.required' => '内容不能为空',
                'post_title.required' => '标题不能为空',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }
            $post_status = $request->has('post_status') ? $request->input('post_status') : config('const.POST_STATUS.PUBLISH.value');
            $status_array = array_column(config('const.POST_STATUS'), 'value');
            //判断状态是否存在
            if (!in_array($post_status, $status_array)) {
                throw new \Exception('文章状态不存在');
            }
            $post = new Model\Post();
            $post->post_author = auth()->user()->id;
            $post->post_date = $request->has('post_date') ? $request->input('post_date') : date('Y-m-d H:i:s');
            $post->post_content = $request->input('post_content');
            $post->post_title = $request->input('post_title');
            $post->post_status = $post_status;
            $post->post_type = config('const.POST_TYPE.POST');
            $post->save();

            //增加分类标签包含文章数量
            $termTaxonomy = $request->has('termTaxonomy') ? $request->input('termTaxonomy') : [];
            Model\TermTaxonomy::whereIn('term_taxonomy_id', $termTaxonomy)
                ->increment('count', 1);

            //同步关联
            $post->termTaxonomy()->sync($termTaxonomy);
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * 文章编辑
     */
    public function edit(Request $request)
    {
        $result = array('result' => true);
        try {
            $validator = Validator::make($request->all(), [
                'post_id' => 'required',
                'post_title' => 'required',
            ], [
                'post_id.required' => 'ID不能为空',
                'post_title.required' => '标题不能为空',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }
            $post_status = $request->has('post_status') ? $request->input('post_status') : config('const.POST_STATUS.PUBLISH.value');
            $status_array = array_column(config('const.POST_STATUS'), 'value');
            //判断状态是否存在
            if (!in_array($post_status, $status_array)) {
                throw new \Exception('文章状态不存在');
            }

            $post = Model\Post::find($request->input('post_id'));
            $post->post_date = $request->has('post_date') ? $request->input('post_date') : date('Y-m-d H:i:s');
            $post->post_modified = date('Y-m-d H:i:s');
            $post->post_content = $request->input('post_content');
            $post->post_title = $request->input('post_title');
            $post->post_status = $post_status;
            $post->save();

            //删除分类 标签 包含文章数量
            $term_taxonomy_ids = Model\TermRelationships::where('object_id', $request->input('post_id'))->lists('term_taxonomy_id');
            Model\TermTaxonomy::whereIn('term_taxonomy_id', $term_taxonomy_ids)
                ->decrement('count', 1);

            $termTaxonomy = $request->has('termTaxonomy') ? $request->input('termTaxonomy') : [];
            //增加分类标签包含文章数量
            Model\TermTaxonomy::whereIn('term_taxonomy_id', $termTaxonomy)
                ->increment('count', 1);

            //同步关联
            $post->termTaxonomy()->sync($termTaxonomy);
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * 文章删除
     */
    public function delete(Request $request)
    {
        $result = array('result' => true);
        try {
            $post = Model\Post::whereIn('post_id', $request->input('ids'));
            $post->delete();

            $termRelationships = Model\TermRelationships::whereIn('object_id', $request->input('ids'));
            $termRelationships->delete();
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function ueditor(Request $request)
    {
        $action = $request->input('action');
        switch ($action) {
            case 'config':
                $result = config('ueditor');
                break;
            /* 上传涂鸦 */
            case 'uploadscrawl':
                $model = new Logic\Photo();
                $result = $model->uploadscrawl();
                break;
            /* 上传图片 */
            case 'uploadimage':
            /* 上传视频 */
            case 'uploadvideo':
            /* 上传文件 */
            case 'uploadfile':
                $model = new Logic\Photo();
                $result = $model->upload(config('const.PHOTO_TYPE.IMAGE_TYPE_BLOG'));
                break;
            /* 列出图片 */
            case 'listimage':
                $model = new Logic\Photo();
                $result = $model->lists();
                break;
            /* 列出文件 */
            case 'listfile':
                $result = include("action_list.php");
                break;

            /* 抓取远程文件 */
            case 'catchimage':
                $result = include("action_crawler.php");
                break;

            default:
                $result = json_encode(array(
                    'state' => '请求地址出错'
                ));
                break;
        }

        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state' => 'callback参数不合法'
                ));
            }
        } else {
            return response(json_encode($result));
        }
    }
}
