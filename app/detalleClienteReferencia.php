<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleClienteReferencia extends Model
{
    protected $table = 'tbldetalleclientereferencias';

    protected $fillable = [
        'dfvid',
        'dfvnombre_referencia',
        'dfvtelefono_referencia',
        'cliente_id'
    ];

    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }
}
