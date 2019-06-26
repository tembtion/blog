<?php
namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model;
use App\Logic\Post;
use View;

class PostController extends Controller {

    public $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * 文章检索
     */
    public function search(Request $request)
    {
        $result = array();
        $result['keyword'] = $request->input('s');
        $result['posts'] = $this->post->getSearch();

        return view('home.post.search', $result);
    }

    /**
     * 文章检索
     */
    public function searchAjax(Request $request)
    {
        $result = array();
        $result['posts'] = $this->post->getSearch();

        return View::make('home.common.content', $result)->render();
    }

    /**
     * 文章详细
     */
    public function index(Request $request, $post_id)
    {
        $result = array();
        $post = Model\Post::where('post_type', 'post')
            ->where('post_status', config('const.POST_STATUS.PUBLISH.value'))
            ->find($post_id);
        if (is_null($post)) {
            abort(404);
        }
        $result['post'] = $post;
        //增加浏览次数
        $post->increment('visitor_count', 1);
        //获取该文章的前一个
        $result['prev'] = Model\Post::where('post_date', '<', $post->post_date)
            ->where('post_type', 'post')
            ->where('post_status', config('const.POST_STATUS.PUBLISH.value'))
            ->orderBy('post_date', 'desc')
            ->first();
        //获取该文章的后一个
        $result['next'] = Model\Post::where('post_date', '>', $post->post_date)
            ->where('post_type', 'post')
            ->where('post_status', config('const.POST_STATUS.PUBLISH.value'))
            ->orderBy('post_date', 'asc')
            ->first();

        return view('home.post.index', $result);
    }


}