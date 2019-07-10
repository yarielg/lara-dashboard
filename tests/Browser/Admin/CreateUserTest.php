<?php

namespace Tests\Browser\Admin;

use App\Profession;
use App\Skill;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateUserTest extends DuskTestCase
{
    use DatabaseMigrations; //No olvidarse de este trait

    /** @test */
    function a_user_can_be_created(){

        $profession = factory(Profession::class)->create([
            'title' => 'Web Developer'
        ]);
        $skillA = factory(Skill::class)->create([
            'description' => 'CSS'
        ]);
        $skillB = factory(Skill::class)->create([
            'description' => 'HTML'
        ]);

        $this->browse(function(Browser $browser) use ($profession,$skillA,$skillB){
            $browser->visit('users/create')
                    ->assertSeeIn('h5','Create User')
                    ->type('name','Yariel Gordillo')
                    ->type('email','yariko0529@gmail.com')
                    ->type('password','secret')
                    ->type('bio','This a small bio')
                    ->select('profession_id',$profession->id)
                    ->type('twitter' ,'https://twitter.com/yariko')
                    ->check("skills[{$skillA->id}]")
                    ->check("skills[{$skillB->id}]")
                    ->radio('role', 'user')
                    ->press('Create User')
                    ->assertPathIs('/users')
                     ->assertSee('Yariel Gordillo')
                     ->assertSee('yariko0529@gmail.com');
        });

    }

    /** @test */
    function form_validation_create_user(){


        $this->browse(function(Browser $browser){
            $browser->visit('users/create')
                ->type('name','')
                ->type('email','')
                ->type('password','')
                ->type('bio','')
                ->type('twitter' ,'https://twitter.com/yariko')
                ->press('Create User')
                ->assertPathIs('/users/create')
                ->assertSee('The name field is required')
                ->assertSee('The email field is required')
                ->assertSee('The password field is required')
                ->assertSee('The bio field is required.');
        });

    }

    /** @test */
    function table_data_is_correct(){



        $this->browse(function(Browser $browser){
            $browser->visit('/users');
            $browser->with('.table', function ($table) {
                $table->assertSee('Name');
            });
        });



    }
}
