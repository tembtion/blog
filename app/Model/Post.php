<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    protected $primaryKey = 'post_id';

    public $timestamps = false;

    public function termTaxonomy()
    {
        return $this->belongsToMany('App\Model\TermTaxonomy', 'term_relationships', 'object_id', 'term_taxonomy_id');
    }

    public function user()
    {
        return $this->hasOne('App\Model\User', 'id', 'post_author');
    }

    /**
     * 状态为PUBLISH的文章
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublish($query)
    {
        return $query->where('post_type', config('const.POST_TYPE.POST'))
            ->where('post_status', config('const.POST_STATUS.PUBLISH.value'))
            ->orderBy('post_date', 'desc');
    }
}
