<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OurMachine;
use App\MachineType;
use App\Driver;
use App\OurMachinePart;


class OurMachineController extends Controller
{
     public function index()
    {
    	$our_machines = OurMachine::latest('id')->get();
    	$machine_types = MachineType::latest('id')->get();
        return view('our_machines.index',compact('our_machines','machine_types'));
    }

    public function create(Request $request)
    {
        $machine_type = MachineType::findOrFail($request->machine_type);
        $drivers = Driver::all();
    	return view('our_machines.create',compact('machine_type','drivers'));
    }

    public function store(Request $request)
    {
        $our_machine = new OurMachine;
        $our_machine->tabnum = $request->tabnum;
        $our_machine->machine_type_id = $request->machine_type;
        $our_machine->driver_id = $request->driver;
        $our_machine->save();

        $machine_parts = $request->machine_part;
        $parts = $request->part;
        $init_times = $request->init_time;

        for ($i=0; $i<count($request->machine_part);$i++)
        {
            $our_machine_part = new OurMachinePart;
            $our_machine_part->our_machine_id = $our_machine->id;
            $our_machine_part->machine_part_id = $machine_parts[$i];
            $our_machine_part->part_id = $parts[$i];
            $our_machine_part->init_time = $init_times[$i];
            $our_machine_part->save();
        }
        return redirect('/our_machines');
    }
}
