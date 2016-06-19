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
        $this->get('/stories')
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

        $this->get('/stories')
            ->seeJson([
                'total' => 2,
            ]);
    }

    /**
     * @test
     */
    public function it_should_tell_if_you_didnt_liked_the_story()
    {
        factory(App\Story::class)->create();

        $this->get('/stories')
            ->seeJson([
                'total'       => 1,
                'liked_count' => 0,
            ]);
    }

    /**
     * @test
     */
    public function it_should_tell_if_you_liked_the_story()
    {
        $user = factory(App\User::class)->create();
        $story = factory(App\Story::class)->create();
        factory(App\Like::class)->create([
            'user_id'  => $user->id,
            'story_id' => $story->id,
        ]);

        $this->authenticate()->get('/stories')
            ->seeJson([
                'total'       => 1,
                'liked_count' => 1,
            ]);
    }

    /**
     * @test
     */
    public function it_should_return_a_single_story()
    {
        $story = factory(App\Story::class)->create([
            'title' => 'foo',
        ]);

        $this->get('/stories/1')
            ->seeJson([
                'title' => 'foo',
            ]);
    }

    /**
     * @test
     */
    public function it_should_throw_a_404_if_a_story_does_not_exist()
    {
        $this->get('/stories/1')
            ->seeStatusCode(404);
    }

    /**
     * @test
     */
    public function it_should_post_a_new_story()
    {
        $this->authenticate()->post('/stories', [
            'title' => 'foo',
            'body'  => 'bar',
        ])->seeStatusCode(201);

        $this->get('/stories/1')
            ->seeJson([
                'title' => 'foo',
                'body'  => 'bar',
            ]);
    }

    /**
     * @test
     */
    public function it_should_return_a_401_if_not_authenticated_when_posting_a_story()
    {
        $this->post('/stories')->seeStatusCode(401);
    }

    /**
     * @test
     */
    public function it_should_update_a_story()
    {
        factory(App\Story::class)->create();
        $this->authenticate()->put('/stories/1', ['title' => 'foo'])
             ->seeStatusCode(204);

        $this->get('/stories/1')->seeJson(['title' => 'foo']);
    }

    /**
     * @test
     */
    public function it_should_return_a_401_if_not_authenticated_when_updating_a_story()
    {
        factory(App\Story::class)->create();
        $this->put('/stories/1')->seeStatusCode(401);
    }
}
