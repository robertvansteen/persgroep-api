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
        App\User::all()->each(function($user) {
            factory(App\Story::class, rand(0, 5))->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
