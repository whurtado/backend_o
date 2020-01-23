<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    //
    protected $table = 'tblvendedor';

    protected $fillable = [
        'fvcnombre'
    ];
}
