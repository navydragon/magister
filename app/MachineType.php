<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MachineType extends Model
{
    public function machine_parts()
    {
        return $this->hasMany('App\MachinePart');
    }
}
