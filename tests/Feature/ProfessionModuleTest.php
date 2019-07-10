<?php

namespace Tests\Feature;

use App\Profession;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestHelpers;

class ProfessionModuleTest extends TestCase
{

    use RefreshDatabase, TestHelpers;

    /** @test */
    function it_shows_professions_list(){

        $this->get('professions')->assertStatus(200)
             ->assertSee('List of Professions')
             ;
    }

    /** @test */
    function it_deletes_a_professions(){
        $profession = factory(Profession::class)->create();
        $response = $this->delete('professions/'.$profession->id)
             ->assertRedirect('professions');
        $response->assertRedirect(route('professions.index'));

        $this->assertDatabaseEmpty('professions');

    }

    /** @test */
    function a_profession_cannot_be_deleted_if_has_an_user_associate(){
        $profession = factory(Profession::class)->create();
        $user = factory(User::class)->create([
            'profession_id' => $profession->id
        ]);

        $response = $this->delete('professions/'.$profession->id);
        $this->assertDatabaseCount('professions',1);
        $this->assertDatabaseCount('professions',1);
    }
}
