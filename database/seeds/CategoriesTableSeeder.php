<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('categories')->truncate();

        $this->command->info('Seeding categories...');

		DB::table('categories')->insert([
			[
				'id'   => 'headlines',
				'name' => 'Headlines',
				'created_at' => \Carbon\Carbon::now(),
				'updated_at' => \Carbon\Carbon::now(),
			],
			[
				'id'   => 'cultuur',
				'name' => 'Cultuur',
				'created_at' => \Carbon\Carbon::now(),
				'updated_at' => \Carbon\Carbon::now(),
			],
			[
				'id'   => 'sport',
				'name' => 'Sport',
				'created_at' => \Carbon\Carbon::now(),
				'updated_at' => \Carbon\Carbon::now(),
			],
		]);
    }
}
