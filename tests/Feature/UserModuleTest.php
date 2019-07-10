<?php

namespace Tests\Feature;

use App\Profession;
use App\Skill;
use App\User;
use App\UserProfile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestHelpers;

class UserModuleTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
  use RefreshDatabase, TestHelpers;

    /**  @test */
    public function it_shows_the_users_list()
    {
        factory(User::class)->create($this->getValidateData());

        $this->get('/users')
             ->assertStatus(200)
             ->assertSee('Yariel Gordillo')
             ->assertSee('Web Developer');
    }

    /** @test */
    function it_loads_new_user_page(){
        $this->withoutExceptionHandling();
        $this->get('users/create')
             ->assertStatus(200)
             ->assertSee('Create User');
            // ->assertViewHas('professions'); We are not passing the var profession to the view /users/create anymore, no'w we are passing this var to common._fields
    }

    /**  @test */
    public function it_shows_empty_user_list()
    {
        $this->get('/users')
            ->assertStatus(200)
            ->assertSee('There are not users');
    }

    /**  @test */
    public function it_shows_specify_user_page(){

        $user = factory(User::class)->create($this->getValidateData());

        $this->get('/users/' . $user->id)
             ->assertStatus(200)
             ->assertSee('Yariel Gordillo');
    }

    /**  @test */
    public function it_shows_a_404_error_if_the_users_isnt_found(){

        $this->get('/users/999')
            ->assertStatus(404)
            ->assertSee('User not found');
    }

    /** @test */
    public function it_creates_a_new_user(){

        $this->withoutExceptionHandling();
        $skillA = factory(Skill::class)->create(['description' => 'Html']);
        $skillB = factory(Skill::class)->create(['description' => 'Css']);
        $skillC = factory(Skill::class)->create(['description' => 'JS']);

        $this->post('/users/',$this->getValidateData([
            'bio' => 'This is a description (bio)',
            'twitter' => 'https://twitter.com/yarielgordillo',
            'skills' => [$skillA->id,$skillB->id]
        ]))->assertRedirect(route('users.index'));



        $this->assertCredentials([
            'name' => 'Yariel Gordillo',
            'email' => 'yariko0529@gmail.com',
            'password' => '123456',
        ]);

        $user = User::where('email','yariko0529@gmail.com')->first();

        $this->assertDatabaseHas('users',[
            'profession_id' => User::first()->profession->id
        ]);

        $this->assertDatabaseHas('user_skill',[
            'user_id' => $user->id,
            'skill_id' => $skillA->id
        ]);

        $this->assertDatabaseMissing('user_skill',[
          'skill_id' => $skillC->id
        ]);

        $this->assertDatabaseHas('user_profiles',[
            'user_id' => User::first()->id,
            'bio' => 'This is a description (bio)',
            'twitter' => 'https://twitter.com/yarielgordillo',

        ]);

        $this->assertDatabaseCount('users',1);
    }

    /** @test */
    public function the_name_is_required(){
       // $this->withoutExceptionHandling();

        //validating the user should be required
        $this->from('/users/create')->post('/users/', //el metodo from es para decirle de donde venemos, si no especificamos esto las prueba no sabra de donde venemos, por lo tanto no sabra redireccinarnos hacu=ia atras porque no sabe de donde venemos
        $this->getValidateData([
            'name' => '',
        ]))->assertRedirect('/users/create')
          ->assertSessionHasErrors(['name'=>'The name field is required']);

        /*$this->assertDatabaseMissing('users',[
            'email' => 'yariko0529@gmail.com'
        ]);*/

        $this->assertEquals(0,User::count());
    }

    /** @test */
    public function the_email_is_required(){
        //$this->withoutExceptionHandling(); //es muy util cuando nos da un error 500 en la prueba

        //validating the user should be required
        $this->from('/users/create')->post('/users/',$this->getValidateData([
            'email' => '',
        ]))->assertRedirect('/users/create')
            ->assertSessionHasErrors(['email'=>'The email field is required']);


        $this->assertDatabaseEmpty('users');

        //$this->assertEquals(0,User::count());
    }

    /** @test */
    public function the_password_is_required(){
        //$this->withoutExceptionHandling(); //es muy util cuando nos da un error 500 en la prueba

        //validating the user should be required
        $this->from('/users/create')->post('/users/',$this->getValidateData([
            'password' => ''
        ]))->assertRedirect('/users/create')
            ->assertSessionHasErrors(['password'=>'The password field is required']);

        $this->assertEquals(0,User::count());
    }

    /** @test */
     function email_must_be_valid(){

             $this->from('users/create')->post('/users/',$this->getValidateData([
                 'email' => 'no-correct-email',
             ]))->assertRedirect('users/create')
               ->assertSessionHasErrors(['email' => 'The email provided is not a valid email']);

             $this->assertEquals(0,User::count());
     }

    /** @test */
    function email_must_be_unique(){

        factory(User::class)->create([
            'email' => 'yariko0529@gmail.com',
        ]);
        $this->from('users/create')->post('/users/',$this->getValidateData([
            'email' => 'yariko0529@gmail.com',
        ]))->assertRedirect('users/create')
                ->assertSessionHasErrors(['email' => 'The email provided already exists']);

        $this->assertEquals(1,User::count());
    }

    /** @test */
    function password_min_6_characters(){

        $password = '12345';
        $this->from('users/create')->post('/users/',$this->getValidateData([
            'password' => $password
        ]))->assertRedirect('users/create')
          ->assertSessionHasErrors(['password' => 'The password should have at least 6 characters']);

        $this->assertGreaterThanOrEqual(6,$password);


        $this->assertEquals(0,User::count());
    }

    /** @test */
    function skills_must_be_an_array(){


        $this->from('users/create')->post('/users/',$this->getValidateData([
            'skills' => '1,2',

        ]))->assertRedirect('users/create')
            ->assertSessionHasErrors(['skills']);

        $this->assertEquals(0,User::count());
    }

    /** @test */
    function skills_must_be_a_valid_array(){

        //$this->withoutExceptionHandling();

        $skillA = factory(Skill::class)->create(['description' => 'Html']);
        $skillB = factory(Skill::class)->create(['description' => 'Css']);

        $this->from('users/create')->post('/users/',$this->getValidateData([
            'skills' => [$skillA->id, $skillB->id + 100],

        ]))->assertRedirect('users/create')
            ->assertSessionHasErrors(['skills']);

        $this->assertEquals(0,User::count());
    }


    /** @test */
    function it_loads_the_update_user_page(){
       //$this->withoutExceptionHandling();
        $user = factory(User::class)->create($this->getValidateData());

        $this->get('users/'. $user->id . '/edit')
             ->assertStatus(200)
             ->assertViewIs('users.edit')
             ->assertViewHas('user',function($userView) use ($user){
                 return $userView->id == $user->id;
             })
             ->assertSee('Edit User')
             ->assertSee('Yariel Gordillo')
             ->assertSee('yariko0529@gmail.com');
    }

    /** @test */
    function update_user_data_form(){
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        factory(UserProfile::class)->create([
            'user_id' => $user->id
        ]);


        $skill = factory(Skill::class)->create();

        $this->put('/users/' . $user->id,$this->getValidateData([
            'bio' => 'test',
            'twitter' => 'http://asdasd.sd',
            'skills' => [$skill->id]
        ]))->assertRedirect('/users/'.$user->id);

        $this->assertDatabaseHas('user_profiles',[
            'bio' => 'test',
            'twitter' => 'http://asdasd.sd'
        ]);
    }

    /** @test */
    public function the_name_is_required_updating(){
        //$this->withoutExceptionHandling();
        $user = factory(User::class)->create();

        $this->from('users/'.$user->id.'/edit')
             ->put('/users/' . $user->id,$this->getValidateData([
                 'name' => '',
             ]))->assertRedirect('/users/'.$user->id .'/edit')
            ->assertSessionHasErrors(['name'=>'The name field is required']);

        $this->assertDatabaseMissing('users' , ['email' => 'yariko0529@gmail.com']);
    }

    /** @test */
    public function the_email_is_required_updating(){
        //$this->withoutExceptionHandling(); //es muy util cuando nos da un error 500 en la prueba
        $user = factory(User::class)->create();
        //validating the user should be required
        $this->from('/users/'. $user->id .'/edit')->put('/users/'.$user->id,$this->getValidateData([
            'email' => '',
        ]))->assertRedirect('/users/'.$user->id.'/edit')
            ->assertSessionHasErrors(['email'=>'The email field is required']);

        $this->assertDatabaseMissing('users' , ['name' => 'Yariel Gordillo']);
    }

    /** @test */
    public function the_password_is_required_updating(){
        //$this->withoutExceptionHandling(); //es muy util cuando nos da un error 500 en la prueba
        $user = factory(User::class)->create();
        //validating the user should be required
        $this->from('/users/'.$user->id.'/edit')->put('/users/'.$user->id,[
            'password' => ''
        ])->assertRedirect('/users/'.$user->id.'/edit')
            ->assertSessionHasErrors(['password'=>'The password field is required']);

        /*$this->assertDatabaseMissing('users',[
            'email' => 'yariko0529@gmail.com'
        ]);*/

        $this->assertDatabaseMissing('users' , ['name' => 'Yariel Gordillo']);
    }

    /** @test */
    function email_must_be_valid_updating(){
        $user = factory(User::class)->create();
        $this->from('users/'.$user->id.'/edit')->put('/users/'.$user->id,$this->getValidateData([
            'email' => 'no-correct-email',
        ]))->assertRedirect('users/'.$user->id.'/edit')
            ->assertSessionHasErrors(['email' => 'The email provided is not a valid email']);

        $this->assertDatabaseMissing('users' , ['name' => 'Yariel Gordillo']);
    }

    /** @test */
    function email_must_be_unique_updating(){

        $user = factory(User::class)->create([
            'email' => 'other@gmail.com',
        ]);
        $user1 = factory(User::class)->create([
            'email' => 'yariko0529@gmail.com',
        ]);
        $this->from('users/'.$user->id.'/edit')->put('/users/'.$user1->id,$this->getValidateData([
            'email' => 'other@gmail.com',
        ]))->assertRedirect('users/'.$user->id.'/edit')
            ->assertSessionHasErrors(['email' => 'The email provided already exists']);


    }


    /** @test */
    function password_min_6_characters_updating(){
        $user = factory(User::class)->create();
        $password = '12345';
        $this->from('users/'.$user->id.'/edit')->post('/users/',$this->getValidateData([
            'password' => $password
        ]))->assertRedirect('users/'.$user->id.'/edit')
            ->assertSessionHasErrors(['password' => 'The password should have at least 6 characters']);

        $this->assertGreaterThanOrEqual(6,$password);
        $this->assertDatabaseMissing('users' , ['name' => 'Yariel Gordillo']);
    }


    /** @test */
    function the_profession_must_be_valid(){

        $this->from('users/create')->post('users',$this->getValidateData([
            'profession_id' => 5968
        ]))->assertRedirect('users/create')
           ->assertSessionHasErrors(['profession_id']) ;

    }

    private function getValidateData($customAttributes = []){

        $profession = factory(Profession::class)->create([
            'title' => 'Web Developer'
        ]);

        return array_merge([
            'name' => 'Yariel Gordillo',
            'email' => 'yariko0529@gmail.com',
            'password' => '123456',
            'profession_id' => $profession->id,
            'role' => 'user',
            'active' => true

        ],$customAttributes);
    }
}
