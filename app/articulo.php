<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table = 'tblarticulos';

    protected $fillable = ['fvcnombre','user_id'
    ];


    public function categorias()
    {
        return $this->belongsTo('App\Categoria', 'fvccategoria_id', 'id');
    }

    public function usuarios()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function detalleFactura()
    {
        return $this->hasMany('App\DetalleFactura');
    }
}
