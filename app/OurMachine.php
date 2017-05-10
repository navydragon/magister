<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\MachineType;
use App\Driver;
use App\OurMachinePart;

class OurMachine extends Model
{
    protected $guarded = [];

    public function machine_type()
    {
    	return $this->belongsTo('App\MachineType');
    }

    public function driver()
    {
    	return $this->belongsTo('App\Driver');
    }

    public function parts()
    {
    	return $this->hasMany('App\OurMachinePart');
    }
}
