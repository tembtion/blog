<?php
namespace App\Services;

use App\Model;

class Widget
{

    /*
     * 获取标签
     */
    public function getTag()
    {
        return Model\TermTaxonomy::where('taxonomy', 'post_tag')->with('term')->get();
    }

    /*
     * 获取分类
     */
    public function getCategory()
    {
        return Model\TermTaxonomy::where('taxonomy', 'category')->with('term')->get();
    }

    /*
     * 获取最新文章
     */
    public function getPost()
    {
        return Model\Post::where('post_type', 'post')
            ->where('post_status', 'publish')
            ->orderBy('post_date', 'desc')
            ->take(10)
            ->get();
    }

    /*
     * 获取图片
     */
    public function getPhoto()
    {
        return Model\Photo::orderBy('photo_id', 'desc')->take(10)->get();
    }
}
