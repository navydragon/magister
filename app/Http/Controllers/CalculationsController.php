<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Calculation;
use App\OurMachine;
use App\RiskLevel;

class CalculationsController extends Controller
{
    public function index()
    {
    	$calculations = Calculation::all();
    	return view('calculations.index',compact('calculations'));
    }

    public function create()
    {
        $our_machines = OurMachine::all();
        $risk_levels = RiskLevel::where('kyst_border', '<=', 0.6)->get();
        return view('calculations.create',compact('our_machines','risk_levels'));
    }

    public function store(Request $request)
    {
        $calculation = new Calculation;
        $calculation->name = "Расчет №";
        $calculation->start_date="2017-01-01";
        $calculation->risk_level_id = $request->risk_level;
        $calculation->save();
        foreach ($request->our_machines as $our_machine) 
        {
            $calculation->our_machines_pivot()->attach($our_machine);
        }

        return redirect('calculations/'.$calculation->id);
    }
    public function show(Calculation $calculation)
    {
    	
    	return view('calculations.show',compact('calculation'));
    }

    public function predict(Calculation $calculation)
    {
        return view('calculations.predict',compact('calculation'));
    }
}
