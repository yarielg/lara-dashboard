<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    function users(){

        return $this->belongsToMany(User::class , 'user_skill');

    }}
