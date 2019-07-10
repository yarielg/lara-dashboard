<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $skills = App\Skill::all();
        factory(User::class,20)->create()
            ->each(function(User $user) use ($skills){
            $user->profile()->create(
              factory(\App\UserProfile::class)->raw()
            );
            $user->skills()->attach($skills->random(1,4));
            $user->update([
               'profession_id' => random_int(1,10),
               'team_id' => random_int(1,10)
            ]);

        });
    }
}
