<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $table = 'terms';

    public $primaryKey = 'term_id';

    public $timestamps = false;

    public function termTaxonomy()
    {
        return $this->hasOne('App\Model\TermTaxonomy', 'term_id', 'term_id');
    }
}
