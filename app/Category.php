<?php

namespace App;

use DB;
use Cache;

class Category extends Model
{
	/**
	 * Specify the hidden properties.
	 *
	 * @type {Array}
	 */
	protected $hidden = [
		'pivot',
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id' => 'string',
	];

	/**
	 * Defines the stories relationship.
	 *
	 * @return Illuminate\Database\Eloquent\BelongsToMany
	 */
	public function stories() {
		return $this->belongsToMany('App\Story');
	}

	/**
	 * Eager load the most popular stories.
	 *
	 * @param  Illuminate\Database\Query $query
	 * @return Illuminate\Database\Query
	 */
    public function scopeWithPopularStories($query)
	{
		return $query->with(['stories' => function ($query) {
			return $query
				->orderBy('score', 'DESC')
				->take(3);
		}]);
	}
}
