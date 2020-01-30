<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class clienteNovedad extends Model
{
    protected $table = 'tblclientenovedad';

    public function usuarios()
    {
        return $this->belongsTo('App\User', 'fvcusuario_id', 'id');
    }

}
