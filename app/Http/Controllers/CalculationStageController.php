<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Calculation;
use App\CalculationStage;

class CalculationStageController extends Controller
{
   public function store(Calculation $calculation, Request $request)
   {
   		$stages_count = count($calculation->calculation_stages()->get());
   		$stage = new CalculationStage;
   		$stage->calculation_id = $calculation->id;
   		$stage->stage_num = $stages_count+1;
   		$stage->save();

   		foreach($request->kiv as $key => $value)
   		{
   			$stage->our_machines_pivot()->attach($key, ['kiv' => $request->kiv[$key], 'kmode' => $request->kmode[$key], 'moving_perc' => $request->moving_perc[$key], 'rotation_perc' => $request->rotation_perc[$key]]);
   		}
	   	return back();
	}
}
