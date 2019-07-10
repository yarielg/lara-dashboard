<?php

namespace App\Http\ViewComposers;

use App\Profession;
use Illuminate\Contracts\View\View;

class UserFieldsComposer {

    public function compose(View $view){
        $professions = Profession::orderBy('title','ASC')->get();
        $view->with(compact('professions'));
    }
}