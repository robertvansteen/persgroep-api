<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoryApiTest extends TestCase
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
        $stories = factory(App\Story::class, 2)->create();

        $this->api()->get('stories')
            ->seeJson([
                'total' => 2,
            ]);
    }
}
