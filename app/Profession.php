<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Profession extends Model
{
    protected $guarded = [];
    #
    public function users(){
        return $this->hasMany(User::class);
    }
}
