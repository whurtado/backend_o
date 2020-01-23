<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleFactura extends Model
{
    protected $table = 'tbldetallefactura';

    protected $fillable = [
    ];

    public function articulos()
    {
        return $this->belongsTo('App\Articulo', 'fvcarticulo_id', 'id');
    }
}
