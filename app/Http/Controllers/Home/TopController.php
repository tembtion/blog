<?php
namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model;
use App\Logic\Post;
use View;

class TopController extends Controller
{

    public $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * 首页。
     */
    public function index(Request $request)
    {
        $result = array();
        $result['posts'] = $this->post->getLast();

        return view('home.top.index', $result);
    }

    /**
     * 首页ajax数据。
     */
    public function ajax(Request $request)
    {
        $result = array();
        $result['posts'] = $this->post->getLast();

        return View::make('home.common.content', $result)->render();
    }
}