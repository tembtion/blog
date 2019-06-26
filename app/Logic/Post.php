<?php
namespace App\Logic;

use DB;
use App\Model;
use Request;

class Post
{

    /*
     * 判断POST是否存在
     */
    public function isExist($postId)
    {
        $result = Model\Post::where('post_type', config('const.POST_TYPE.POST'))
            ->where('post_status', config('const.POST_STATUS.PUBLISH.value'))
            ->find($postId);
        if (is_null($result)) {
            return false;
        }

        return true;
    }

    public function getSearch()
    {
        $keyword = trim(Request::input('s'));

        $result = Model\Post::where('post_title', 'like', '%' . $keyword . '%')
            ->publish()
            ->with('termTaxonomy')
            ->paginate(10);

        return $result;
    }


    public function getLast()
    {
        $result = Model\Post::publish()->with('termTaxonomy')->paginate(10);

        return $result;
    }

    public function getPostByTerm($term_taxonomy_id)
    {
        $post_ids = Model\TermRelationships::where('term_taxonomy_id', $term_taxonomy_id)
            ->lists('object_id');

        $result = Model\Post::publish()->whereIn('post_id', $post_ids)
            ->paginate(10);

        return $result;
    }
}