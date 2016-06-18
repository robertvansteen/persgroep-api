<?php

namespace App;

use DB;

class Story extends Model
{
	/**
	 * Specify the fillable properties.
	 *
	 * @type {Array}
	 */
	protected $fillable = [
		'title',
		'excerpt',
		'body',
	];

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
		'id'          => 'string',
		'score'       => 'float',
		'likes_count' => 'integer',
		'liked_count' => 'integer',
	];

	/**
	 * Define the user (author) relationship.
	 *
	 * @return \Illuminate\Database\Eloquent\BelongsTo
	 */
	public function author() {
		return $this->belongsTo('App\User', 'user_id');
	}

	/**
	 * Define the likes relationship.
	 *
	 * @return \Illuminate\Database\Eloquent\HasMany
	 */
	public function likes() {
		return $this->hasMany('App\Like');
	}

	/**
	 * Define the likes relationship.
	 *
	 * @return \Illuminate\Database\Eloquent\HasMany
	 */
	public function liked() {
		return $this->hasMany('App\Like');
	}

	/**
	 * Defines the categories relationship.
	 *
	 * @return Illuminate\Database\Eloquent\BelongsToMany
	 */
	public function categories() {
		return $this->belongsToMany('App\Category');
	}

	/**
	 * Scope the query to show the most popular stories.
	 *
	 * @param  Illuminate\Database\Query $query
	 * @return Illuminate\Database\Query
	 */
	public function scopeRawScore($query) {
		return $query
			->select([
				'stories.*',
				DB::raw(
				'COUNT(DISTINCT(story_likes.id)) + (COUNT(user_stories_likes.story_id) * 0.2) AS raw_score'
				)
			])
			->leftJoin('likes AS story_likes', 'stories.id', '=', 'story_likes.story_id')
			->leftJoin('users', 'story_likes.user_id', '=', 'users.id')
			->leftJoin('stories AS user_stories', 'users.id', '=', 'user_stories.user_id')
			->leftJoin('likes AS user_stories_likes', 'user_stories.id', '=', 'user_stories_likes.story_id')
			->groupBy('stories.id');
	}

	public function scopePopular($query) {
		return $query->orderBy('score', 'DESC');
	}

	/**
	 * Scope the query to include the like for the current user.
	 *
	 * @param  Illuminate\Database\Query $query
	 * @return  Illuminate\Database\Query
	 */
	public function scopeWithLikes($query, $user = null) {
		return $query
			->withCount('likes')
			 ->withCount(['liked' => function ($query) use ($user) {
	 		 	$query->where('user_id', $user ? $user->id : null);
			}]);
	}
}
