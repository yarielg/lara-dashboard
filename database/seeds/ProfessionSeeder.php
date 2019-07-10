<?php

use App\Profession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


/*      DB::table('professions')->insert([
            'title' => 'Desarrollador Php'
        ]);*/

        factory(App\Profession::class,10)->create();

    }
}
