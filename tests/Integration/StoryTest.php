<?php

use App\Story;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoryTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function score_without_likes_is_zero()
    {
		factory(App\Story::class)->create();

		$story = Story::score()->first();

		$this->assertEquals($story->score, 0);
    }


	/**
	 * @test
	 */
	public function it_should_have_a_score_when_it_has_likes()
	{
		$story = factory(App\Story::class)->create();
		factory(App\Like::class)->create(['story_id' => $story->id]);

		$assertStory = Story::score()->first();

		$this->assertEquals($assertStory->score, 1.0);
	}


	/**
	 * @test
	 */
	public function it_should_have_extra_score_when_user_liking_it_has_likes()
	{
		$author = factory(App\User::class)->create();
		$liker = factory(App\User::class)->create();

		$authorStory = factory(App\Story::class)->create(['user_id' => $author->id]);
		$likerStory = factory(App\Story::class)->create(['user_id' => $liker->id]);

		factory(App\Like::class)->create([
			'user_id'  => $liker->id,
			'story_id' => $authorStory->id,
		]);

		factory(App\Like::class)->create(['story_id' => $likerStory->id]);

		$assertStory = Story::score()
			->where('stories.id', '=', $authorStory->id)
			->first();

		$this->assertEquals($assertStory->score, 1.2);
	}
}
