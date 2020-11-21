<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Publicacion extends Model
{
    public function users(){
        return $this-> belongsTo('App/Users');
    }
    public function comentarios(){
        return $this-> hasMany('App/Comentarios');
    }
}
