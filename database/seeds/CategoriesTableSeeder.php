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
				'id'   => 'news',
				'name' => 'News',
				'created_at' => \Carbon\Carbon::now(),
				'updated_at' => \Carbon\Carbon::now(),
			],
			[
				'id'   => 'culture',
				'name' => 'Culture',
				'created_at' => \Carbon\Carbon::now(),
				'updated_at' => \Carbon\Carbon::now(),
			],
			[
				'id'   => 'sport',
				'name' => 'Sport',
				'created_at' => \Carbon\Carbon::now(),
				'updated_at' => \Carbon\Carbon::now(),
			],
			[
				'id'   => 'music',
				'name' => 'Music',
				'created_at' => \Carbon\Carbon::now(),
				'updated_at' => \Carbon\Carbon::now(),
			],
			[
				'id'   => 'fashion',
				'name' => 'Fashion',
				'created_at' => \Carbon\Carbon::now(),
				'updated_at' => \Carbon\Carbon::now(),
			],
			[
				'id'   => 'tech',
				'name' => 'Tech',
				'created_at' => \Carbon\Carbon::now(),
				'updated_at' => \Carbon\Carbon::now(),
			],
		]);
    }
}
