<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TermTaxonomy extends Model
{
    protected $table = 'term_taxonomy';

    protected $primaryKey = 'term_id';

    public $timestamps = false;

    public function term()
    {
        return $this->belongsTo('App\Model\Term', 'term_id', 'term_id');
    }
}
