<?php

namespace App;

class Assignment extends Model
{
	/**
	 * Specify the hidden properties.
	 *
	 * @type {Array}
	 */
	protected $hidden = [
		'users',
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id'          => 'string',
	];

	/**
	 * Defines the users relationship.
	 *
	 * @return Illuminate\Database\Eloquent\BelongsToMany
	 */
	public function users() {
		return $this->belongsToMany('App\User')->withPivot('status');
	}

	/**
	 * Scope the query to show the statuses.
	 *
	 * @param  Query $query
	 * @param  User|null $user
	 * @return Query
	 */
	public function scopeWithUser($query, $user = null) {
		return $query->with(['users' => function ($query) use ($user) {
			return $query->where('user_id', $user ? $user->id : null);
		}]);
	}
}
