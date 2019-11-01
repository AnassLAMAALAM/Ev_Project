<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Type extends Model
{
    public function evs()
    {
        return $this->hasMany('App\Ev');
    }
}
