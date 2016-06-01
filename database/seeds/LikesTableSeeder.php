<?php

use App\Like;
use Illuminate\Database\Seeder;
use Illuminate\Database\QueryException;

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
        $likes = factory(Like::class, 250)->make();

        foreach ($likes as $like) {
            $this->seedLike($like);
        }
    }

    public function seedLike(Like $like)
    {
        try {
            $like->save();
        } catch(QueryException $e) {
            $like = factory(Like::class)->make();
            $this->seedLike($like);
        }
    }
}
