<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calculation extends Model
{
    protected $guarded =[];

    public function our_machines_pivot()
    {
    	return $this->belongsToMany('App\OurMachine', 'calculations_our_machines');
    }
    public function risk_level()
    {
    	return $this->belongsTo('App\RiskLevel');
    }

    public function calculation_stages()
    {
    	return $this->hasMany('App\CalculationStage');
    }
}
