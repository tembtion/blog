<?php
namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model;
use App\Logic\Comment;
use App\Logic\Post;
use Auth;
use View;

class CommentController extends Controller
{

    public $comment;

    public $post;

    public function __construct(Comment $comment, Post $post)
    {
        $this->comment = $comment;
        $this->post = $post;
    }

    /**
     * 评论一览
     */
    public function index(Request $request)
    {
        $result = array();
        $result['comment'] = $this->comment->getList($request->input('post_id'));
        $result['post'] = Model\Post::find($request->input('post_id'));

        return View::make('home.post.comments', $result)->render();
    }

    /**
     * 评论添加
     */
    public function post(Request $request)
    {
        $result = array('result' => true);
        try {
            //判断是否登录
            if (!Auth::check()) {
                throw new \Exception('您还未登录，请先登录');
            }
            //判断文章是否存在
            if (!$this->post->isExist($request->input('post_id'))) {
                throw new \Exception('评论的文章不存在，或不允许评论');
            }
            //判断父评论是否存在
            if ($request->has('comment_parent') && !$this->comment->isExist($request->input('comment_parent'))) {
                throw new \Exception('父评论不存在，或已被删除');
            }
            //判断评论字数
            if (mb_strlen($request->input('comment_content')) < 15) {
                throw new \Exception('评论的内容至少15字');
            }
            //插入数据库
            $comment = new Model\Comment();
            $comment->comment_post_ID = $request->input('post_id');
            $comment->comment_date = date('Y-m-d H:i:s');
            $comment->comment_date_gmt = gmdate('Y-m-d H:i:s');
            $comment->comment_content = $request->input('comment_content');
            $comment->comment_author_IP = $request->ip();
            $comment->comment_agent = $request->server('HTTP_USER_AGENT');
            $comment->comment_approved = config('const.COMMENT_APPROVED.ALLOW.value');
            $comment->comment_parent = $request->input('comment_parent', 0);
            $comment->user_id = Auth::id();
            $comment->save();
            //更新文章评论数
            Model\Post::where('post_id', $request->input('post_id'))
                ->increment('comment_count', 1);

            if ($comment->comment_parent == 0) {
                $data['comment'] = $this->comment->getList($comment->comment_post_ID);
                $data['post'] = Model\Post::find($comment->comment_post_ID);
                $view = 'home.post.comments';
            } else {
                $data['comment'] = Model\Comment::find($comment->comment_parent);
                $view = 'home.post.comment';
            }
            $result['comment_id'] = $comment->comment_ID;
            $result['comment_parent'] = $comment->comment_parent;
            $result['content'] = View::make($view, $data)->render();
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * 评论删除
     */
    public function delete(Request $request)
    {
        $result = array('result' => true);
        try {
            //判断是否登录
            if (!Auth::check()) {
                throw new \Exception('您还未登录，请先登录');
            }
            //判断该评论是否属于登录者
            $comment = Model\Comment::where('user_id', Auth::id())
                ->find($request->input('comment_ID'));
            if (is_null($comment)) {
                throw new \Exception('该评论不属于您，无法删除');
            }

            //删除该条评论
            Model\Comment::where('comment_ID', $request->input('comment_ID'))
                ->update(['comment_approved' => config('const.COMMENT_APPROVED.TRASH.value')]);
            //更新文章评论数
            Model\Post::where('post_id', $comment->comment_post_ID)
                ->decrement('comment_count', 1);

        } catch (\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * 评论编辑
     */
    public function edit(Request $request)
    {
        $result = array('result' => true);
        try {
            //判断是否登录
            if (!Auth::check()) {
                throw new \Exception('您还未登录，请先登录');
            }
            //判断该评论是否属于登录者
            $comment = Model\Comment::where('user_id', Auth::id())
                ->find($request->input('comment_ID'));
            if (is_null($comment)) {
                throw new \Exception('该评论不属于您，无法编辑');
            }
            //判断评论字数
            if (mb_strlen($request->input('comment_content')) < 15) {
                throw new \Exception('评论的内容至少15字');
            }
            //编辑该条评论
            $comment->comment_content = $request->input('comment_content');
            $comment->save();

            $result['content'] = View::make('home.post.comment', ['comment' => $comment])->render();
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }
}