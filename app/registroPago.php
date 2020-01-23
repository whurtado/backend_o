<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class registroPago extends Model
{
    protected $table = 'tblregistropago';

    protected $fillable = [
        'fvcfactura',
        'flngvalorFactura',
        'fvcfechaPagoFactura',
        'flngvalorDeduccion',
        'flngvalorPagar',
    ];
}
