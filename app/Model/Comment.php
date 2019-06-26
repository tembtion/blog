<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $primaryKey = 'comment_ID';

    public $timestamps = false;

    protected $guarded = ['comment_ID'];

    public function user()
    {
        return $this->belongsTo('App\Model\User', 'user_id', 'id');
    }

    public function post()
    {
        return $this->belongsTo('App\Model\Post', 'comment_post_ID', 'post_id');
    }

    public function comment()
    {
        return $this->belongsTo('App\Model\Comment', 'comment_ID', 'comment_parent');
    }

    public function parent()
    {
        return $this->hasOne('App\Model\Comment', 'comment_ID', 'comment_parent');
    }
}
