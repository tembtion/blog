<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Auth;
use App\Model;
use App\Http\Controllers\Controller;
use App\Logic;

class CommentController extends Controller
{
    /**
     * 评论一览
     */
    public function index(Request $request)
    {
        $result = array();
        $result['filter'] = array();
        $query = Model\Comment::with('user', 'post', 'parent');
        if ($request->has('comment_approved')) {
            $query = $query->where('comment_approved', $request->input('comment_approved'));
            $result['filter']['comment_approved'] = $request->input('comment_approved');
        }
        $result['comments'] = $query->orderBy('comment_date', 'desc')
            ->paginate(20);
        return view('auth.comment.index', $result);
    }
    /**
     * 评论删除
     */
    public function delete(Request $request)
    {
        $result = array('result' => true);
        try {
            Model\Comment::whereIn('comment_ID', $request->input('ids'))
                ->update(['comment_approved' => config('const.COMMENT_APPROVED.TRASH.value')]);

        } catch(\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }
}
