<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PartType;
class PartTypeController extends Controller
{
    public function index()
    {
    	$part_types = PartType::all();
    	return view('part_types.index',compact('part_types'));
    }

    public function store(Request $request)
    {
    	$part_type =  new PartType;
    	$part_type->name = $request->name;
    	$part_type->save();
    	return back();
    }
}
