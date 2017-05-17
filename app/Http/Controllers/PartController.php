<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Part;
use App\PartType;

class PartController extends Controller
{
    public function index()
    {
    	$parts = Part::all();
    	$part_types = PartType::all();
    	return view('parts.index',compact('parts','part_types'));
    }

    public function store(Request $request)
    {
    	$part =  new Part;
    	$part->name = $request->name;
    	$part->part_type_id = $request->part_type;
    	$part->mtbf = $request->mtbf;
    	$part->save();
    	return back();
    }
}
