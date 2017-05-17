<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Driver;
class DriverController extends Controller
{
    public function index()
    {
    	$drivers = Driver::all();
    	return view('drivers.index',compact('drivers'));
    }

    public function store(Request $request)
    {
    	$driver =  new Driver;
    	$driver->name = $request->name;
    	$driver->graduate = $request->graduate;
    	$driver->standing = $request->standing;
    	$driver->save();
    	return back();
    }
}
