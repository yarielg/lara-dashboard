<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $guarded = [];

    function users(){
        $this->hasMany(App\User::class );
    }
}
