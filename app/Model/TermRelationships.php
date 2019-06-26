<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TermRelationships extends Model
{
    protected $table = 'term_relationships';

    public $timestamps = false;

    public function term()
    {
        return $this->belongsTo('App\Model\Term', 'term_id', 'term_id');
    }
}
