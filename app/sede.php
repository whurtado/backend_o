<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sede extends Model
{
    protected $table = 'tblsede';

    protected $fillable = [
        'fvcnombre'
    ];



    public function user()
    {
        return $this->hasMany('App\User');
    }
}
