<?php

use Illuminate\Database\Seeder;

class StoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stories')->truncate();

        $this->command->info('Seeding a random amount of stories for every user...');

        App\User::all()->each(function($user) {
            $amount = mt_rand(-20, 10);

            if ($amount < 0) return;

            factory(App\Story::class, mt_rand(-15, 10))->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
