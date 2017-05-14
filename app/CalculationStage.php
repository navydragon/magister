<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\OurMachine;
use App\OurMachinePart;

class CalculationStage extends Model
{
    public function our_machines_pivot()
    {
    	return $this->belongsToMany('App\OurMachine', 'calculation_stages_our_machines')->withPivot('kiv'  ,'moving_kmode','rotation_kmode');
    }

    public function narabotka(OurMachine $our_machine,OurMachinePart $part)
    {
    	$kiv = $this->our_machines_pivot($our_machine->id)->get()->first()->pivot->kiv;
		//$rotation_perc = $this->our_machines_pivot($our_machine->id)->get()->first()->pivot->rotation_perc;
		//$moving_perc = $this->our_machines_pivot($our_machine->id)->get()->first()->pivot->moving_perc;
		//if ($part->work_type() == "moving"){$nar = 200 * $kiv * $moving_perc / 100;}
		//if ($part->work_type() == "rotation"){$nar = 200 * $kiv * $rotation_perc / 100;}
        $nar = 200 * $kiv;
		return $nar;
    }
}
