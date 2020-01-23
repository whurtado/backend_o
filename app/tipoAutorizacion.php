<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tipoAutorizacion extends Model
{
    protected $table = 'tbltipoautorizacion';

    protected $fillable = [
        'fvcnombre'
    ];

    public function autorizaciones()
    {
        return $this->hasMany('App\tipoAutorizacion');
    }
}
