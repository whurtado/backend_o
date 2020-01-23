<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'tblcategorias';

    protected $fillable = [
    ];

    public function articulos()
    {
        return $this->hasMany('App\Articulo');
    }
}
