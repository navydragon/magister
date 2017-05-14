<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    public function staff()
    {
    	$year = date("Y") - date("Y",strtotime($this->standing));

    	if ($this->graduate == "высшее") 
    	{
    		if ($year <= 9) {$st = 0.25;}
    		if ( (($year > 9)&&($year <= 17)) || ($year > 29) ) {$st = 0.5;} //9-17, свыше 29
    		if ( ($year > 17)&&($year <= 25) ) {$st = 0.75;} //17-25
    		if ( ($year > 25)&&($year <= 29) ) {$st = 1;} //26-29
    		$grad = 2;
    	}else{
    		if ($year <= 9) {$st = 0.25;}
    		if ( (($year > 9)&&($year <= 13)) || ($year > 29) ) {$st = 0.5;} //9-13, свыше 29
    		if ( ($year > 13)&&($year <= 17) ) {$st = 0.75;} //13-17, 21-29
    		if ( ($year > 21)&&($year <= 29) ) {$st = 0.75;}
    		if ( ($year > 17)&&($year <= 21) ) {$st = 1;} //17-21
    		$grad = 1;
    	}
    	$staff = ($st+$grad)/3;
    	return round($staff,2);
    }
}
