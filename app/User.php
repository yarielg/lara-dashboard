<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','profession_id','team_id','role','active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'active' => 'bool'
    ];

    public function profession(){
        return $this->belongsTo(Profession::class);
    }

    public function profile(){
        return $this->hasOne(UserProfile::class)->withDefault();
    }

    function skills(){
        return $this->belongsToMany(Skill::class,'user_skill');
    }

    function team(){
        return $this->belongsTo(Team::class)->withDefault();
    }

    function scopeSearch($query,$searchText){

       $query-> when($searchText,function($query, $searchText){
            if($searchText == 'with_team'){
                $query->has('team');
            }elseif($searchText == 'without_team' ){
                $query->doesntHave('team');
            }
        })->when(request('search'), function($query , $searchText){
                $query->where(function($query) use ($searchText){   // WHERE (users.name like '$name$' or users.email like '%email%') is in order to include the parenthesis.
                    $query->where('name','like',"%$searchText%")
                        ->orWhere('email','like',"%$searchText%")
                        ->orWhereHas('team',function($query) use ($searchText){
                            $query->where('name','like',"%$searchText%");
                        });
                });
            });

    }


    function scopeByState($query, $stateText ){
        $query->when($stateText, function($query, $stateText){
            if($stateText == 'active'){
                $query->where('active',1);
            }elseif($stateText == 'inactive'){
                $query->where('active',0);
            }
        });
    }
   /* static function createUser($data){

        DB::transaction(function() use($data){
            $user =  User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password'])
            ]);

            $user->profile()->create([
                'bio' => $data['bio'],
                'twitter' => $data['twitter']
            ]);
        });

    }*/
}
