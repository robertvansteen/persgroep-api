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
	public function authenticate($userId = 1)
	{
		$user = User::find($userId);

		if (!$user) {
			$user = factory(App\User::class)->create();
		}

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
	public function get($uri, array $headers = [])
	{
		$headers = array_merge($headers, $this->headers);

		return parent::get($this->getUri($uri), $headers);
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

		return parent::post($this->getUri($uri), $data, $headers);
	}

	/**
	 * Make a PUT request.
	 *
	 * @param  String $uri
	 * @param  Array $data
	 * @param  Array $headers
	 *
	 * @return this
	 */
	public function put($uri, array $data = [], array $headers = [])
	{
		$headers = array_merge($headers, $this->headers);

		return parent::put($this->getUri($uri), $data, $headers);
	}

	/**
	 * Get URI with API prefix.
	 *
	 * @param String $uri
	 * @return String
	 */
	protected function getUri($uri)
	{
		return '/' . env('API_PREFIX') . $uri;
	}

}
