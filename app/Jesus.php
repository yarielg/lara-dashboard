<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jesus extends Model
{
    function saySomething(){
        return 'I said something';
    }
}
