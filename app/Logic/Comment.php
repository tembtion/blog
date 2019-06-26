<?php
namespace App\Logic;

use DB;
use App\Model;
use Request;
use URL;

class Comment
{
    /*
     * 判断POST是否存在
     */
    public function isExist($commentId)
    {
        $result = Model\Comment::where('comment_approved', 1)
            ->find($commentId);
        if (is_null($result)) {
            return false;
        }

        return true;
    }

    public function getList($post_id)
    {
        $result = Model\Comment::where('comment_post_ID', $post_id)
            ->where('comment_parent', 0)
            ->where('comment_approved', config('const.COMMENT_APPROVED.ALLOW.value'))
            ->with('user', 'comment')
            ->orderBy('comment_date', 'desc')
            ->paginate(10);
        $result->setPath(URL::route('homeCommentIndex'));

        return $result;
    }
}