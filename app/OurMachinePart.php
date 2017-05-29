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
        $x->addMember($x->getInputName(0),'lite',  0, 20, 50 ,LINFINITY);
        $x->addMember($x->getInputName(0),'average'  , 20, 50, 80 ,TRIANGLE);
        $x->addMember($x->getInputName(0),'tough'  , 50, 80, 100,RINFINITY);
        $x->addMember($x->getInputName(1),'low', 0, 20, 50,LINFINITY);
        $x->addMember($x->getInputName(1),'average'  , 20, 50, 80 ,TRIANGLE);
        $x->addMember($x->getInputName(1),'high',50, 80,100,RINFINITY);
        $x->SetOutputNames(array('optimism'));
        $x->addMember($x->getOutputName(0),'verylow',0, 10 ,30 ,LINFINITY);
        $x->addMember($x->getOutputName(0),'low',15, 30 ,45 ,TRIANGLE);
        $x->addMember($x->getOutputName(0),'average',30, 50 ,70 ,TRIANGLE);
        $x->addMember($x->getOutputName(0),'high',55,  70 , 85 ,TRIANGLE);
        $x->addMember($x->getOutputName(0),'veryhigh',70,  90 , 100 ,RINFINITY);
        $x->clearRules();

        $x->addRule('IF kmode.lite AND staff.high THEN optimism.veryhigh');
        $x->addRule('IF kmode.lite AND staff.average THEN optimism.high');
        $x->addRule('IF kmode.lite AND staff.low THEN optimism.average');

        $x->addRule('IF kmode.average AND staff.high THEN optimism.high');
        $x->addRule('IF kmode.average AND staff.average THEN optimism.average');
        $x->addRule('IF kmode.average AND staff.low THEN optimism.low');

        $x->addRule('IF kmode.tough AND staff.high THEN optimism.average');
        $x->addRule('IF kmode.tough AND staff.average THEN optimism.low');
        $x->addRule('IF kmode.tough AND staff.low THEN optimism.verylow');
        
        //$kmode = 65;
        //$staff = 50;

        $x->SetRealInput('kmode',   $kmode  );
        $x->SetRealInput('staff' , $staff );
        $fuzzy_arr = $x->calcFuzzy();
        $optimism = $fuzzy_arr['optimism'];
        return round($optimism);
   }

   public function first_efficiency($nar)
   {
        $a_sr = 0.01612; $a1_ap = 0.00395; $a2_ap = 0.0283;$b_ap = 0.95;
        $k = (-1) * $a_sr / 1000000 * pow($nar,2) + $b_ap;
        return $k;
   }

    public function efficiency($prev_eff, $prev_nar,$nar,CalculationStage $stage)
    {
        $calcs = 1000; $work_arr = array();$work_arr2 = array();
        //if ($this->work_type() == "rotation") {$a1_ap = 0.012; $a2_ap = 0.065; $b_ap = 0.895;}
    	//if ($this->work_type() == "moving") {$a1_ap = 0.008; $a2_ap = 0.050; $b_ap = 0.94;}
        $a1_ap = 0.00395; $a2_ap = 0.0283;  $b_ap = 0.95;
    	//$k1 = $this->kappa($nar,$a1_ap,$b_ap); // верхняя граница в конце периода
		//$k2 = $this->kappa($nar,$a2_ap,$b_ap); // нижняя граница в конце периода
		//$m_o = ($k1 + $k2) / 2;   // мат.ожидание
        //$s_o = $m_o / 3;
            $m_o = ($a1_ap + $a2_ap) / 2;
            $s_o = $m_o / 3;
        $optimism = $this->optimism($stage);
        for ($i = 1;$i<=$calcs;$i++)
        {
            $res = $this->Gauss($m_o,$s_o);
            if ($res > $a2_ap) {$res=$a2_ap;}
            if ($res < $a1_ap) {$res=$a1_ap;}
            array_push($work_arr,$res);
        }
            rsort($work_arr);
        for ($i=0;$i < count($work_arr);$i++)
        {
            if ($i%10 == 0) {array_push($work_arr2,$work_arr[$i]);}
        }
            rsort($work_arr2);
            $work_a = $work_arr2[$optimism];
            $eff = $prev_eff - $work_a / 1000000 * (pow($nar,2) - pow($prev_nar,2));
		return $eff;
    }



    public function kyst($kpd,$nar,$tot_nar,CalculationStage $stage)
    {
        if ($this->work_type() == "rotation")  {$koef = 1;}
        if ($this->work_type() == "moving")  {$koef = 5;}
        $a_sr = 0.01612; $a1_ap = 0.00395; $a2_ap = 0.0283;$b_ap = 0.95;
        $optimism = $this->optimism($stage);
        //$a = $a1_ap+ ($a2_ap - $a1_ap) / 100 * (100-$optimism);
       
        if ($kpd >= 0.7)
        {
             $a = ($b_ap - $kpd) / pow($tot_nar,2) * 1000000;
            $t_max = round(sqrt(($b_ap-0.7)/($a / 1000000)));
            $t_ost = $t_max - $tot_nar;
            if ($t_ost < 200) {$t_ost = 201;}
            $kyst_p = 1 - 200 / $t_ost;
            $mtbf = $this->part->mtbf;
            if ($tot_nar <= $mtbf * $koef ) 
                { $kyst_v = 0.99;}
            else {$kyst_v = 1 - ($tot_nar / ($mtbf * $koef) - 1);}
            $kyst = round($kyst_p*$kyst_v,3);
        }else{
           
            $kyst = "!";
        }
        return $kyst."(".$t_max."/".$t_ost.")";
        //."(".$t_max."/".$t_ost.")"(".round($kyst_p,2).")(".round($kyst_v,2).")";
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
