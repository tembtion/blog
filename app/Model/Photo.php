<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'photos';

    protected $primaryKey = 'photo_id';

    protected $fillable = ['user_id', 'photo_name', 'photo_key', 'photo_type', 'album_id'];

}
