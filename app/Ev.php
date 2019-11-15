<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ev extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];


    public function type()
    {
        return $this->belongsTo('App\Type');
    }
}
