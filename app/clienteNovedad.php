<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClienteNovedad extends Model
{
    protected $table = 'tblclientenovedad';

    public function usuarios()
    {
        return $this->belongsTo('App\User', 'fvcusuario_id', 'id');
    }

}
