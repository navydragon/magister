<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartType extends Model
{
	public function parts()
    {
    	return $this->hasMany('App\Part');
    }
}
