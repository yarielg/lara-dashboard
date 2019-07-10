<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestHelpers;

class DeleteUserTest extends TestCase
{

    use RefreshDatabase, TestHelpers;

    /** @test */
   /* function it_deletes_user(){
        $user =   factory(User::class)->create();

        $this->delete('users/' . $user->id)
             ->assertRedirect('users');
        dd($user);
        $this->assertTrue($user->deleted_at);
    }*/


    /** @test */
    function it_sends_a_user_to_trash(){

        $user = factory(User::class)->create();

        $this->patch('users/' . $user->id . '/trash')
             ->assertRedirect('users');

        $this->assertSoftDeleted('users',[
            'id' => $user->id
        ]);
    }

   /** @test */
   function a_user_is_deleted_permanently(){
     $this->withoutExceptionhandling();
       $user = factory(User::class)->create([
           'name' => 'User1',
           'deleted_at' => now(),
       ]);

       $this->delete('/users/' . $user->id)
            ->assertRedirect('users/trash');

       $this->assertDatabaseEmpty('users');
   }
}
