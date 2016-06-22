<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		ini_set('memory_limit','256M');
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        $this->call(UsersTableSeeder::class);
		$this->call(CategoriesTableSeeder::class);
        $this->call(StoriesTableSeeder::class);
        $this->call(LikesTableSeeder::class);
		$this->call(AssignmentsTableSeeder::class);
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
