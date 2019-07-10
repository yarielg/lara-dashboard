<?php

use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Skill::class)->create(['description' => 'HTML']);
        factory(App\Skill::class)->create(['description' => 'CSS']);
        factory(App\Skill::class)->create(['description' => 'PHP']);
        factory(App\Skill::class)->create(['description' => 'JS']);
    }
}
