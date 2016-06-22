<?php

use Illuminate\Database\Seeder;

class AssignmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('assignments')->truncate();

		$this->command->info('Seeding assignments...');

		factory(App\Assignment::class, 10)->create();
    }
}
