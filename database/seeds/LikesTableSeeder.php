<?php

use App\Like;
use Illuminate\Database\Seeder;
use Illuminate\Database\QueryException;
use Symfony\Component\Console\Helper\ProgressBar;

class LikesTableSeeder extends Seeder
{

    protected $likes = [];

    protected $userCount = 0;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('likes')->truncate();
        $this->likes = [];
        $this->userCount = App\User::count();

        App\Story::all()->each(function($story) {
            $this->likes[] = $this->makeLike($story->id, rand(0, 20));
        });

        foreach ($this->likes as $like) {
            $this->seedLike($like);
        }
    }

    public function seedLike(Like $like)
    {
        try {
            $like->save();
        } catch(QueryException $e) {
            $like = $this->makeLike($like->story_id);
            $this->seedLike($like);
        }
    }

    protected function makeLike($story_id, $amount = 1)
    {
        return factory(App\Like::class, $amount)->make([
            'story_id' => $story_id,
            'user_id'  => rand(0, $this->userCount),
        ]);
    }
}
