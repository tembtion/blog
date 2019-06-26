<?php
namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model;
use App\Logic\Post;
use View;

class TermController extends Controller {

    public $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * 分类。
     */
    public function category(Request $request, $term_id)
    {
        $result = array();
        //判断分类是否存在
        $term_taxonomy_id = Model\TermTaxonomy::where('taxonomy', 'category')
            ->where('term_id', $term_id)
            ->first();
        if (is_null($term_taxonomy_id)) {
            abort(404);
        }

        $result['posts'] = $this->post->getPostByTerm($term_taxonomy_id->term_taxonomy_id);
        $result['category'] = $term_taxonomy_id;

        return view('home.term.category', $result);
    }

    /**
     * 分类ajax文章。
     */
    public function categoryAjax(Request $request)
    {
        $result = array();
        //判断分类是否存在
        $term_taxonomy_id = Model\TermTaxonomy::where('taxonomy', 'category')
            ->where('term_id', $request->input('term_id'))
            ->first();
        $result['posts'] = array();
        if (!is_null($term_taxonomy_id)) {
            $result['posts'] = $this->post->getPostByTerm($term_taxonomy_id->term_taxonomy_id);
        }

        return View::make('home.common.content', $result)->render();
    }

    /**
     * 标签。
     */
    public function tag(Request $request, $term_id)
    {
        $result = array();
        //判断标签是否存在
        $term_taxonomy_id = Model\TermTaxonomy::where('taxonomy', 'post_tag')
            ->where('term_id', $term_id)
            ->first();
        if (is_null($term_taxonomy_id)) {
            abort(404);
        }
        $result['posts'] = $this->post->getPostByTerm($term_taxonomy_id->term_taxonomy_id);
        $result['tag'] = $term_taxonomy_id;

        return view('home.term.tag', $result);
    }

    /**
     * 标签ajax文章。
     */
    public function tagAjax(Request $request)
    {
        $result = array();
        //判断分类是否存在
        $term_taxonomy_id = Model\TermTaxonomy::where('taxonomy', 'post_tag')
            ->where('term_id', $request->input('term_id'))
            ->first();
        $result['posts'] = array();
        if (!is_null($term_taxonomy_id)) {
            $result['posts'] = $this->post->getPostByTerm($term_taxonomy_id->term_taxonomy_id);
        }

        return View::make('home.common.content', $result)->render();
    }
}