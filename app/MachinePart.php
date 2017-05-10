<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MachinePart extends Model
{
    public function machine_type()
    {
    	return $this->belongsTo('App\MachineType');
    }

    public function part_type()
    {
    	return $this->belongsTo('App\PartType');
    }


}
