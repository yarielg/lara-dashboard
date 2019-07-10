<?php

namespace Tests\Feature;

use App\Team;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestHelpers;

class SearchUserTest extends TestCase
{
    use RefreshDatabase, TestHelpers;

    /**  @test */
    public function search_users_by_name()
    {
        $user1 = factory(User::class)->create([
            'name' => 'user1'
        ]);

        $user2 = factory(User::class)->create([
            'name' => 'user2'
        ]);

        $this->get('/users?search=user1')
            ->assertStatus(200)
            ->assertViewHas('users', function($users) use ($user1,$user2){
                return $users->contains($user1) && !$users->contains($user2);
            });
    }

    /**  @test */
    public function search_users_by_partial_name()
    {
        $user1 = factory(User::class)->create([
            'name' => 'user1'
        ]);

        $user2 = factory(User::class)->create([
            'name' => 'user2'
        ]);

        $user3 = factory(User::class)->create([
            'name' => 'xxxxxx'
        ]);

        $this->get('/users?search=user')
            ->assertStatus(200)
            ->assertViewHas('users', function($users) use ($user1,$user2,$user3){
                return $users->contains($user1) && $users->contains($user2) && !$users->contains($user3);
            });
    }

    /**  @test */
    public function search_users_by_team()
    {
        $user1 = factory(User::class)->create([
            'name' => 'user1',
            'team_id' => factory(Team::class)->create(['name' => 'Team A'])->id
        ]);

        $user2 = factory(User::class)->create([
            'name' => 'user2',
            'team_id' => factory(Team::class)->create(['name' => 'Team B'])->id
        ]);

        $this->get('/users?team=all&search=Team+A')
            ->assertStatus(200)
            ->assertViewHas('users', function($users) use ($user1,$user2){
                return $users->contains($user1) && !$users->contains($user2);
            });
    }

}
