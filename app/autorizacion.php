<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class autorizacion extends Model
{
    protected $table = 'tblautorizacion';

    protected $fillable = [
        'fvcdescripcion',
        'fvcfechaAutorizacion'
    ];


    public function tipoAutorizacion()
    {
        return $this->belongsTo('App\tipoAutorizacion', 'fvctipoautorizacion_id', 'id');
    }
}
