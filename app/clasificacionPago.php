<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class clasificacionPago extends Model
{
    protected $table = 'tblclasificacionpago';

    protected $fillable = [
        'fvcnombre'
    ];
}
