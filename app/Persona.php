<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Persona extends Model
{
    public function users(){
        return $this-> hasMany('App/User');
    }
}
