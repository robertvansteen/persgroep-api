<?php

use App\Like;
use Illuminate\Database\Seeder;
use Illuminate\Database\QueryException;
use Symfony\Component\Console\Helper\ProgressBar;

class LikesTableSeeder extends Seeder
{

    /**
     * The array holding the generated likes.
     *
     * @type Array
     */
    protected $likes = [];

    /**
     * The amount of users in the database.
     *
     * @type Number
     */
    protected $userCount = 0;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('likes')->truncate();

        $this->command->info('Seeding a random amount of likes per story from random users...');

        $this->userCount = App\User::count();

        App\Story::all()->each(function($story) {
            $amount = mt_rand(round($this->userCount * -0.1), round($this->userCount * 0.25));

            if ($amount < 1) return;

            $likes = $this->makeLikes($story->id, $amount);

            if ($likes instanceof App\Like) {
				$this->seedLike($likes);
            }

			if ($likes instanceof Illuminate\Database\Eloquent\Collection) {
	            foreach($likes as $like) {
					$this->seedLike($like);
	            }
			}
        });
    }

    /**
     * Attempt to seed the like in the database.
     *
     * @param  Like $like
     * @return void
     */
    protected function seedLike(Like $like)
    {
        try {
            $like->save();
        } catch(QueryException $e) {
            $like = $this->makeLikes($like->story_id);
            $this->seedLike($like);
        }
    }

    /**
     * Generate a like or multiple likes.
     *
     * @param  String $story_id
     * @param  Integer $amount
     *
     * @return Illuminate\Database\Eloquent\Collection | App\Like
     */
    protected function makeLikes($storyId, $amount = 1)
    {
        return factory(App\Like::class, $amount)->make([
            'story_id' => $storyId,
            'user_id'  => mt_rand(0, $this->userCount),
        ]);
    }
}
