<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OurMachinePart extends Model
{
    public function machine_part()
    {
    	return $this->belongsTo('App\MachinePart');
    }
    public function part()
    {
        return $this->belongsTo('App\Part');
    }

    public function work_type()
    {
    	if (strpos($this->machine_part->name,"передвижения") === false)
    	{
    		return "rotation";
    	}else{
    		return "moving";
    	}
    }

   public function kappa($t,$a,$b)
   {
		$result = -$a / 1000000 * pow($t,2) + $b;
		return $result;
   }

    public function efficiency($nar)
    {
        if ($this->work_type() == "rotation") {$a1_ap = 0.012; $a2_ap = 0.065; $b_ap = 0.895;}
    	if ($this->work_type() == "moving") {$a1_ap = 0.008; $a2_ap = 0.050; $b_ap = 0.94;}
    	$k1 = $this->kappa($nar,$a1_ap,$b_ap); // верхняя граница в конце периода
		$k2 = $this->kappa($nar,$a2_ap,$b_ap); // нижняя граница в конце периода
		$m_o = ($k1 + $k2) / 2;   // мат.ожидание
		return $m_o;
    }

    public function kyst($kpd,$nar,$tot_nar)
    {
        if ($this->work_type() == "rotation")  {$a_sr = 0.0385;}
        if ($this->work_type() == "moving")  {$a_sr = 0.029;}
        if ($kpd >= 0.7)
        {
            $t_ost = round(sqrt(($kpd-0.7)/($a_sr/1000000)));
            $kyst_p = 1 - 200 / $t_ost;
            $mtbf = $this->part->mtbf;
            if ($tot_nar <= $mtbf ) 
                { $kyst_v = 0.99;}
            else {$kyst_v = 1 - ($tot_nar / $mtbf - 1);}
            $kyst = $kyst_p * $kyst_v;
        }else{
           
            $kyst = "!";
        }
        return $kyst;
    }

    public function bgcolor($kyst)
    {
        $bgcolor = "#b00000";
        if ($kyst >= 0.15) {$bgcolor="#d95030";}
        if ($kyst >= 0.30) {$bgcolor="#ff9900";}
        if ($kyst >= 0.45) {$bgcolor="#e1cc4f";}
        if ($kyst >= 0.60) {$bgcolor="#71bc78";}
        if ($kyst >= 0.75) {$bgcolor="#008000";}
        if ($kyst >= 0.90) {$bgcolor="#009B77";}
        return $bgcolor;
    }
}
