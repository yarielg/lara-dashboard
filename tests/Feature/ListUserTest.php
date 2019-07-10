<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListUserTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    function it_shows_the_deleted_users(){
        $this->withoutExceptionHandling();
        factory(User::class)->create([
            'name' => 'User1',
            'deleted_at' => now()
        ]);
        factory(User::class)->create([
            'name' => 'User2',
        ]);

        $this->get('/users/trash')
             ->assertStatus(200)
             ->assertSee('Users Deleted Temporary')
             ->assertSee('User1')
             ->assertDontSee('User2');

    }
}
