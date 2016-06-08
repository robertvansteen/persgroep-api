<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoryApiTest extends ApiTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_should_return_an_empty_collection_of_stories_if_there_is_none()
    {
        $this->api()->get('stories')
            ->seeJson([
                'total' => 0,
            ]);
    }

    /**
     * @test
     */
    public function it_should_return_a_collection_of_stories()
    {
        factory(App\Story::class, 2)->create();

        $this->api()->get('stories')
            ->seeJson([
                'total' => 2,
            ]);
    }

    /**
     * @test
     */
    public function it_should_tell_if_you_didnt_liked_the_story()
    {
        $story = factory(App\Story::class)->create();

        $this->api()->get('stories')
            ->seeJson([
                'total' => 1,
                'liked' => false
            ]);
    }

    /**
     * @test
     */
    public function it_should_tell_if_you_liked_the_story()
    {
        $user = factory(App\User::class)->create();
        $story = factory(App\Story::class)->create();
        $like = factory(App\Like::class)->create([
            'user_id'  => $user->id,
            'story_id' => $story->id,
        ]);

        $this->api()->authenticate()->get('stories')
            ->seeJson([
                'total'  => 1,
                'liked'  => true,
            ]);
    }
}
