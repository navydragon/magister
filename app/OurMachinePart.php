<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Vendor\phpclasses\fuzzylogicclass;
use App\CalculationStage;
use App\OurMachine;

class OurMachinePart extends Model
{
    public function machine_part()
    {
    	return $this->belongsTo('App\MachinePart');
    }

    public function our_machine()
    {
        return $this->belongsTo('App\OurMachine');
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

   public function Gauss($Mx, $Sigma)
   {
    $r=10;
    while ($r>1) 
    {
      $a= 2*(rand(0,1000)/1000) - 1;
      $b= 2*(rand(0,1000)/1000) - 1;
      $r= pow($a,2) + pow($b,2);
    }
     $Sq = sqrt(-2*log($r)/$r);
     $Result = $Mx + $Sigma * $a * $Sq;
    return $Result;
    }

   public function kappa($t,$a,$b)
   {
		$result = -$a / 1000000 * pow($t,2) + $b;
		return $result;
   }

   public function optimism(CalculationStage $stage)
   {
        $moving_kmode = $stage->our_machines_pivot($this->our_machine)->first()->pivot->moving_kmode;
        $rotation_kmode = $stage->our_machines_pivot($this->our_machine)->first()->pivot->rotation_kmode;
        if ($this->work_type() == "rotation") {$kmode = $rotation_kmode * 100;}
        if ($this->work_type() == "moving") {$kmode = $moving_kmode * 100;}

        $staff = $this->our_machine->driver->staff() * 100;

       $x = new Fuzzy_Logic();
        $x->clearMembers(); 
        $x->SetInputNames(array('kmode','staff'));
        $x->addMember($x->getInputName(0),'lite',  0, 20, 40 ,LINFINITY);
        $x->addMember($x->getInputName(0),'average'  , 20, 50, 80 ,TRIANGLE);
        $x->addMember($x->getInputName(0),'tough'  , 60, 80, 100,RINFINITY);
        $x->addMember($x->getInputName(1),'low', 0, 20, 70,LINFINITY);
        $x->addMember($x->getInputName(1),'high',30, 80,100,RINFINITY);
        $x->SetOutputNames(array('optimism'));
        $x->addMember($x->getOutputName(0),'low',0, 20 ,40 ,LINFINITY);
        $x->addMember($x->getOutputName(0),'normal',20, 50 ,80 ,TRIANGLE);
        $x->addMember($x->getOutputName(0),'high',60,  80 , 100 ,RINFINITY);
        $x->clearRules();
        $x->addRule('IF kmode.lite AND staff.high THEN optimism.high');
        $x->addRule('IF kmode.lite AND staff.low THEN optimism.normal');
        $x->addRule('IF kmode.average AND staff.high THEN optimism.normal');
        $x->addRule('IF kmode.average AND staff.low THEN  optimism.low');
        $x->addRule('IF kmode.tough THEN optimism.low');

        //$kmode = 65;
        //$staff = 50;

        $x->SetRealInput('kmode',   $kmode  );
        $x->SetRealInput('staff' , $staff );
        $fuzzy_arr = $x->calcFuzzy();
        $optimism = $fuzzy_arr['optimism'];
        return round($optimism);
   }

    public function efficiency($nar,CalculationStage $stage)
    {
        $calcs = 1000; $work_arr = array();$work_arr2 = array();
        //if ($this->work_type() == "rotation") {$a1_ap = 0.012; $a2_ap = 0.065; $b_ap = 0.895;}
    	//if ($this->work_type() == "moving") {$a1_ap = 0.008; $a2_ap = 0.050; $b_ap = 0.94;}
        $a1_ap = 0.00395; $a2_ap = 0.0283;  $b_ap = 0.95;
    	$k1 = $this->kappa($nar,$a1_ap,$b_ap); // верхняя граница в конце периода
		$k2 = $this->kappa($nar,$a2_ap,$b_ap); // нижняя граница в конце периода
		$m_o = ($k1 + $k2) / 2;   // мат.ожидание
        $s_o = $m_o / 3;
        $optimism = $this->optimism($stage);
        for ($i = 1;$i<=$calcs;$i++)
        {
            $res = $this->Gauss($m_o,$s_o);
            if ($res > $k1) {$res=$k1;}
            if ($res < $k2) {$res=$k2;}
            array_push($work_arr,$res);
        }
            sort($work_arr);
        for ($i=0;$i < count($work_arr);$i++)
        {
            if ($i%10 == 0) {array_push($work_arr2,$work_arr[$i]);}
        }
            sort($work_arr2);
		return $work_arr2[$optimism];
    }



    public function kyst($kpd,$nar,$tot_nar,CalculationStage $stage)
    {
        //if ($this->work_type() == "rotation")  {$a_sr = 0.0385;}
        //if ($this->work_type() == "moving")  {$a_sr = 0.029;}
        $a_sr = 0.01612; $a1_ap = 0.00395; $a2_ap = 0.0283;$b_ap = 0.95;
        $optimism = $this->optimism($stage);
        $a = $a1_ap+ ($a2_ap - $a1_ap) / 100 * (100-$optimism);
        if ($kpd >= 0.7)
        {
            $t_max = round(sqrt(($b_ap-0.7)/($a/1000000)));
            $t_ost = $t_max - $tot_nar;
            $kyst_p = 1 - 200 / $t_ost;
            $mtbf = $this->part->mtbf;
            if ($tot_nar <= $mtbf ) 
                { $kyst_v = 0.99;}
            else {$kyst_v = 1 - ($tot_nar / $mtbf - 1);}
            $kyst = round($kyst_p*$kyst_v,3);
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
