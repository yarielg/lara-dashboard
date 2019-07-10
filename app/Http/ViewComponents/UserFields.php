<?php

namespace App\Http\ViewComponents;

use App\Profession;
use App\User;
use Illuminate\Contracts\Support\Htmlable;

/** READ THIS IS VERY IMPORTANT
 * {{}} Escapa el html
 * {!! No escapa el html !!}
 *
 * Using Htmlable interface we can call {{ new UserFields }} and it going to return the method toHtml() that it going to return
 * a view, we dont need scape the html, see why below ↓↓↓↓
 *
 * function e($value, $doubleEncode = true)
        {
        if ($value instanceof Htmlable) {
        return $value->toHtml();
        }

        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', $doubleEncode);
        }
 *
 * Also we can call {!! new UserFields !!} of this way it going to return the magical method __toString()
 */

    class UserFields implements Htmlable{

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    function toHtml()
    {
        $professions = Profession::orderBy('title','ASC')->get();
        return view('common._fields',['professions' => $professions, 'user' => $this->user ])->render();
    }

    function __toString()
    {
        return $this->toHtml();
    }
}