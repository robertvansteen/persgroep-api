<?php

use App\User;

class ApiTestCase extends TestCase
{
	protected $headers = [];

	/**
	 * Authenticate a user for testing purposes.
	 *
	 * @param String userId
	 * @return this
	 */
	protected function authenticate($userId = 1)
	{
		$user = User::find($userId);
		$token = JWTAuth::fromUser($user);

		$this->headers['Authorization'] = "Bearer: $token";

		return $this;
	}

	/**
	 * Make a get request.
	 *
	 * @param  String $uri
	 * @param  Array $headers
	 *
	 * @return this
	 */
	public function get($url, array $headers = [])
	{
		$headers = array_merge($headers, $this->headers);

		return parent::get($url, $headers);
	}

	/**
	 * Make a post request.
	 *
	 * @param  String $uri
	 * @param  Array $data
	 * @param  Array $headers
	 *
	 * @return this
	 */
	public function post($uri, array $data = [], array $headers = [])
	{
		$headers = array_merge($headers, $this->headers);

		return parent::post($uri, $data, $headers);
	}

}
