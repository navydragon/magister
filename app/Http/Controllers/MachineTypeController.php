<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MachineType;

class MachineTypeController extends Controller
{
     public function index()
    {
    	$machine_types = MachineType::latest('id')->get();
        return view('machine_types.index',compact('machine_types'));
    }

    public function show(MachineType $machine_type)
    {
        return view('machine_types.show',compact('machine_type')); 
    }

    public function create()
    {
    	return view('machine_types.create');
    }

    public function store(Request $request)
    {
    	$machine_type = new MachineType;
    	$machine_type->name = $request->name;
    	$machine_type->description = $request->description;
    	$machine_type->save();
    }
}
