<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    public function part_type()
    {
    	return $this->belongsTo('App\PartType');
    }
}
