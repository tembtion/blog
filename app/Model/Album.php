<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $table = 'album';

    protected $primaryKey = 'album_id';

    public $timestamps = false;

    public function photo()
    {
        return $this->hasMany('App\Model\Photo', 'album_id', 'album_id');
    }
}
