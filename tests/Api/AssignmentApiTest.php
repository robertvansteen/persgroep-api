<?php

use App\Assignment;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AssignmentApiTest extends ApiTestCase
{
	use DatabaseMigrations;

	/**
	 * @test
	 */
	public function it_should_return_all_the_open_assignments()
	{
		factory(App\Assignment::class, 3)->create(['status' => 'open']);
		factory(App\Assignment::class, 2)->create(['status' => 'assigned']);

		$this->get('/assignments?status=open');
	}

	/**
	 * @test
	 */
	public function it_should_return_all_the_closed_assignments()
	{
		factory(App\Assignment::class, 3)->create(['status' => 'open']);
		factory(App\Assignment::class, 2)->create(['status' => 'assigned']);

		$this->get('/assignments?status=assigned');
	}

	/**
	 * @test
	 */
	public function it_should_subscribe_auth_user_to_assignment()
	{
		factory(App\Assignment::class)->create();

		$this->authenticate()
			 ->post('/assignments/1/subscribe')
			 ->seeStatusCode(201);
	}

	/**
	 * @test
	 */
	public function it_should_return_the_assignments_of_auth_user()
	{
		$user = factory(App\User::class)->create();
		$assignment = factory(App\Assignment::class)->create();
		$user->assignments()->save($assignment);

		$this->authenticate($user->id)
			 ->get('/me/assignments')
			 ->seeJson(['title' => $assignment->title]);
	}
}
