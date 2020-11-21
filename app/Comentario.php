<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    public function users(){
        return $this-> belongsTo('App/User');
    }
    public function publicaciones(){
        return $this-> belongsTo('App/Publicacion');
    }
}
