<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function evs()
    {
        return $this->hasMany('App\Ev');
    }
}
