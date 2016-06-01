<?php

use Illuminate\Database\Seeder;

class LikesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('likes')->truncate();
        $likes = factory(App\Like::class, 250)->make();

        foreach ($likes as $like) {
            repeat:
            try {
                $like->save();
            } catch (\Illuminate\Database\QueryException $e) {
                $like = factory(App\Like::class)->make();
                goto repeat;
            }
        }
    }
}
