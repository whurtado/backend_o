<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClienteEstado extends Model
{
    protected $table = 'tblclienteestado';

    protected $fillable = [

    ];

    public function usuarios()
    {
        return $this->belongsTo('App\User', 'fvcusuario_id', 'id');
    }
}
